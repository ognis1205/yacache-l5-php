<?php
/*
 * Copyright (c) Shingo OKAWA <shingo.okawa.n.a@gmail.com>
 */
namespace Illuminate\YetAnother\Cache;

use \Exception;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Contracts\Cache\Store;
use Illuminate\YetAnother\Cache\Configuration\Options;
use Illuminate\YetAnother\Cache\Configuration\OptionsInterface;
use Illuminate\YetAnother\Cache\Serializer\TimekeepingSerializer;

/**
 * [Yet Another Implementation]
 * Represents file based storages.
 *
 * @author Shingo OKAWA
 */
class FileStore implements Store
{
    /**
     * Holds key string which refers files option.
     *
     * @const string the key string to refer files option.
     */
    const OPTION_OF_FILES = 'files';

    /**
     * Holds key string which refers path option.
     *
     * @const string the key string to refer path option.
     */
    const OPTION_OF_PATH = 'path';

    /**
     * Holds key string which refers codec option.
     *
     * @const string the key string to refer codec option.
     */
    const OPTION_OF_CODEC = 'codec';

    /**
     * Holds user specified options.
     *
     * @var OptionsInterface
     */
    protected $options;

    /**
     * Holds serializer
     *
     * @var SerializerInterface
     */
    protected $serializer;

    /**
     * Constructor.
     *
     * @param  Filesystem  $files
     * @param  string  $path
     */
    public function __construct($options)
    {
        // TODO: Implement corresponding option handlers.
        $this->options = $this->createOptions($options);
        $this->serializer = new TimekeepingSerializer(
            $this->options[self::OPTION_OF_CODEC]
        );
    }

    /**
     * Creates a new instance of Options from different types of
     * arguments or simply returns the passed argument if it is an
     * instance of OptionsInterface.
     *
     * @param  mixed $options   client options.
     * @return OptionsInterface instanciated options.
     * @throws Exception.
     */
    protected function createOptions($options)
    {
        if (is_array($options)) {
            return new Options($options);
        }

        if ($options instanceof OptionsInterface) {
            return $options;
        }

        // TODO: Implement appropriate exception.
        throw new Exception('invalid type for FileStore options.';)
    }

    /**
     * Retrieves an item from the cache by key.
     *
     * @param  string $key the key that refers to the desired record.
     * @return mixed  the record which corresponds to the key.
     */
    public function get($key)
    {
        $path = $this->options[self::OPTION_OF_PATH]($key);
        try {
            $record = $this->options[self::OPTION_OF_FILES]->get($path);
            $record = $this->serializer->deserialize($record);
            if (is_null($record)) {
                $this->forget($key);
                return null
            }
            return $record->getData();
        } catch (Exception $e) {
            return null;
        }
    }

    /**
     * Stores an item in the cache for a given number of minutes.
     *
     * @param  string $key     the assigninig key value.
     * @param  mixed  $value   the caching value.
     * @param  int    $minutes the expiration duration in minutes.
     */
    public function put($key, $value, $minutes)
    {
        $data = $this->serializer->serialize($value, $minutes);
        $this->createCacheDirectory($path=$this->options[self::OPTION_OF_PATH]($key));
        $this->options[self::OPTION_OF_FILES]->put($path, $data);
    }

    /**
     * Creates the file cache directory if necessary.
     *
     * @param  string $path the path to the cache directory.
     */
    protected function createCacheDirectory($path)
    {
        try {
            $this->options[self::OPTION_OF_FILES]->makeDirectory(
                dirname($path),
                0777,
                true,
                true
            );
        } catch (Exception $e) {
            // DO NOTHING.
        }
    }

    /**
     * Increments the value of an item in the cache.
     *
     * @param  string $key   the key that refers to the desired record.
     * @param  mixed  $value the incremental value.
     * @return int           returns resulting value of the record.
     */
    public function increment($key, $value=1)
    {
        $record = $this->get($key);
        if (!is_null($record)) {
            $result = ((int)$record->getData()) + $value;
            $this->put($key, $result, (int)$record->getExpiration());
            return $result;
        }
        return null;
    }

    /**
     * Decrements the value of an item in the cache.
     *
     * @param  string $key   the key that refers to the desired record.
     * @param  mixed  $value the decremental value.
     * @return int           the resulting value fo the record.
     */
    public function decrement($key, $value = 1)
    {
        return $this->increment($key, $value * -1);
    }

    /**
     * Stores an item in the cache indefinitely.
     *
     * @param  string $key   the key that refers to the desired record.
     * @param  mixed  $value the caching value.
     */
    public function forever($key, $value)
    {
        return $this->put($key, $value, 0);
    }

    /**
     * Removes an item from the cache.
     *
     * @param  string $key the key that refers to the desired record.
     * @return bool        true if the handling record is removed successfully.
     */
    public function forget($key)
    {
        $file = $this->options[self::OPTION_OF_PATH]($key);

        if ($this->options[self::OPTION_OF_FILES]->exists($file)) {
            return $this->options[self::OPTION_OF_FILES]->delete($file);
        }

        return false;
    }

    /**
     * Removes all items from the cache.
     */
    public function flush()
    {
        if ($this->options[self::OPTION_OF_FILES]->isDirectory(
            $this->options[self::OPTION_OF_PATH]
        )) {
            foreach ($this->options[self::OPTION_OF_FILES]->directories(
                $this->options[self::OPTION_OF_PATH]
            ) as $directory) {
                $this->options[self::OPTION_OF_FILES]->deleteDirectory($directory);
            }
        }
    }

    /**
     * Gets the full path for the given cache key.
     *
     * @param  string $key the handling key value.
     * @return string      the resulting resolved key value.
     */
    protected function path($key)
    {
        $parts = array_slice(str_split($hash = md5($key), 2), 0, 2);
        return $this->options[self::OPTION_OF_PATH].'/'.join('/', $parts).'/'.$hash;
    }

    /**
     * Gets the expiration time based on the given minutes.
     *
     * @param  int $minutes the caluculating input duration in minutes.
     * @return int          the caluculated duration value in seconds.
     */
    protected function expiration($minutes)
    {
        if ($minutes === 0) {
            return 9999999999;
        }
        return time() + ($minutes * 60);
    }

    /**
     * Gets the Filesystem instance.
     *
     * @return Filesystem the handling Fikesystem instance.
     */
    public function getFilesystem()
    {
        return $this->options[self::OPTION_OF_FILES];
    }

    /**
     * Gets the working directory of the cache.
     *
     * @return string the working directory path.
     */
    public function getDirectory()
    {
        return $this->options[self::OPTION_OF_PATH];
    }

    /**
     * Gets the cache key prefix.
     *
     * @return string the preassigned prefix string.
     */
    public function getPrefix()
    {
        return '';
    }
}
