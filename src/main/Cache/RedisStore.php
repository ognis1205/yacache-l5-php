<?php
/*
 * Copyright (c) Shingo OKAWA <shingo.okawa.n.a@gmail.com>
 */
namespace Illuminate\YetAnother\Cache;

use \Exception;
use Illuminate\Cache\RedisTaggedCache;
use Illuminate\Contracts\Cache\Store;
use Illuminate\YetAnother\Cache\Configuration\Options;
use Illuminate\YetAnother\Cache\Configuration\OptionsInterface;
use Illuminate\YetAnother\Cache\Serializer\GenericSerializer;
use Illuminate\YetAnother\Redis\Database as Redis;
//use Illuminate\YetAnother\Redis\Database as Redis;

/**
 * [Yet Another Implementation]
 * Represents redis based storages.
 *
 * @author Shingo OKAWA
 */
class RedisStore extends TaggableStore implements Store
{
    /**
     * Holds key string which refers redis option.
     *
     * @const string the key string to refer redis option.
     */
    const OPTION_OF_REDIS = 'redis';

    /**
     * Holds key string which refers prefix option.
     *
     * @const string the key string to refer prefix option.
     */
    const OPTION_OF_PREFIX = 'prefix';

    /**
     * Holds key string which refers connection option.
     *
     * @const string the key string to refer connection option.
     */
    const OPTION_OF_CONNECTION = 'connection';

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
        $this->serializer = new GenericSerializer(
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
     * @param  string $key the key string which refers to the desired record.
     * @return mixed       resulting reord value.
     */
    public function get($key)
    {
        if (!is_null($record = $this->connection()->get(
            $this->options[self::OPTION_OF_PREFIX].$key
        ))) {
            $record = $this->serializer->deserialize($record);
            return $record->getData();
        }
    }

    /**
     * Store an item in the cache for a given number of minutes.
     *
     * @param string $key     the key string which is bind to the handling data.
     * @param mixed  $value   the value to be cached.
     * @param int    $minutes the expiration duration time in minutes.
     */
    public function put($key, $value, $minutes)
    {
        $data = $this->serializer->serialize($value);
        $minutes = max(1, $minutes);
        $this->connection()->setex(
            $this->options[self::OPTION_OF_PREFIX].$key,
            $minutes * 60,
            $data
        );
    }

    /**
     * Increment the value of an item in the cache.
     *
     * @param  string $key
     * @param  mixed  $value
     * @return int
     */
    public function increment($key, $value = 1)
    {
        return $this->connection()->incrby(
            $this->[self::OPTION_OF_PREFIX].$key,
            $value
        );
    }

    /**
     * Increment the value of an item in the cache.
     *
     * @param  string  $key
     * @param  mixed   $value
     * @return int
     */
    public function decrement($key, $value = 1)
    {
        return $this->connection()->decrby(
            $this->[self::OPTION_OF_PREFIX].$key,
            $value
        );
    }

    /**
     * Store an item in the cache indefinitely.
     *
     * @param  string  $key
     * @param  mixed   $value
     * @return void
     */
    public function forever($key, $value)
    {
        $value = is_numeric($value) ? $value : serialize($value);
        $this->connection()->set(
            $this->[self::OPTION_OF_PREFIX].$key,
            $value
        );
    }

    /**
     * Remove an item from the cache.
     *
     * @param  string  $key
     * @return bool
     */
    public function forget($key)
    {
        return (bool) $this->connection()->del(
            $this->[self::OPTION_OF_PREFIX].$key
        );
    }

    /**
     * Remove all items from the cache.
     *
     * @return void
     */
    public function flush()
    {
        $this->connection()->flushdb();
    }

    /**
     * Begin executing a new tags operation.
     *
     * @param  array|mixed  $names
     * @return RedisTaggedCache
     */
    public function tags($names)
    {
        return new RedisTaggedCache(
            $this,
            new TagSet($this, is_array($names) ? $names : func_get_args())
        );
    }

    /**
     * Get the Redis connection instance.
     *
     * @return \Predis\ClientInterface
     */
    public function connection()
    {
        return $this->options[self::OPTION_OF_REDIS]->connection(
            $this->options[self::OPTION_OF_CONNECTION]
        );
    }

    /**
     * Set the connection name to be used.
     *
     * @param  string  $connection
     * @return void
     */
    public function setConnection($connection)
    {
        $this->options[self::OPTION_OF_CONNECTION] = $connection;
    }

    /**
     * Returns the Redis database instance.
     *
     * @return Database the assigned Redis database instance.
     */
    public function getRedis()
    {
        return $this->options[self::OPTION_OF_REDIS];
    }

    /**
     * Get the cache key prefix.
     *
     * @return string the handling prefix string.
     */
    public function getPrefix()
    {
        return $this->options[self::OPTION_OF_PREFIX];
    }
}