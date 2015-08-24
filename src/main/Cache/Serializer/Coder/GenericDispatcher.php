<?php
/*
 * Copyright (c) Shingo OKAWA <shingo.okawa.n.a@gmail.com>
 */
namespace Illuminate\YetAnother\Cache\Serializer\Coder;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\YetAnother\Cache\Serializer\Coder\Codec\JSON;
use Illuminate\YetAnother\Cache\Serializer\Coder\Codec\MessagePack;
use Illuminate\YetAnother\Cache\Serializer\Coder\Model\Eloquent\CollectionFormat;
use Illuminate\YetAnother\Cache\Serializer\Coder\Model\Eloquent\ModelFormat;
use Illuminate\YetAnother\Cache\Serializer\Coder\Model\Eloquent\PivotFormat;

/**
 * [Yet Another Implementation]
 * Provides functionality to defines codecs.
 *
 * @author Shingo OKAWA
 */
class GenericDispatcher implements DispatcherInterface
{
    /**
     * Holds key string which refers default codec.
     *
     * @const string the key string to refer default codec.
     */
    const KEY_OF_DEFAULT = 'default';

    /**
     * Holds key string which refers encoding codec.
     *
     * @const string the key string to refer encoding codec.
     */
    const KEY_OF_ENCODING = 'encoding';

    /**
     * Holds key string which refers msgpack codec.
     *
     * @const string the key string to refer msgpack codec.
     */
    const KEY_OF_MSGPACK = 'msgpack';

    /**
     * Holds predefined codecs.
     *
     * @var array the predefined codecs.
     */
    protected $codecs;

    /**
     * Holds predefined formats.
     *
     * @var array the predefined formats.
     */
    protected $formats;

    /**
     * Constructor.
     *
     * @param string|CodecInterface
     */
    public function __construct($encoding)
    {
        $this->codecs = $this->getPredefinedCodecs();
        $this->formats = $this->getPredefinedFormats();
        if ($encoding instanceof CodecInterface) {
            $this->codecs[self::KEY_OF_ENCODING] = $encoding;
        } else if (
            is_string($encoding)
            && ($this->codecs[$encoding] || array_key_exists($encoding, $this->codecs)
        )) {
            $this->codecs[self::KEY_OF_ENCODING] = $this->codecs[$encoding];
        } else {
            $this->codecs[self::KEY_OF_ENCODING] = $this->getDefault();
        }
    }

    /**
     * Returns predefined codecs.
     */
    protected function getPredefinedCodecs()
    {
        return [
            self::KEY_OF_DEFAULT => new JSON(),
            self::KEY_OF_MSGPACK => new MessagePack()
        ];
    }

    /**
     * Returns predefined formats.
     */
    protected function getPredefinedFormats()
    {
        // TODO: This implementation is order sensitive, so fix this.
        return [
            Collection::class => new CollectionFormat(),
            Pivot::class      => new PivotFormat(),
            Model::class      => new ModelFormat()
        ];
    }

    /**
     * Returns the default codec for the handling context.
     *
     * @return mixed|null the resulting default codec.
     */
    public function getDefault()
    {
        return $this->codecs[self::KEY_OF_DEFAULT];
    }

    /**
     * Returns the codec for encoding.
     *
     * @return mixed|null the resulting encoding codec.
     */
    public function getEncoding()
    {
        return $this->codecs[self::KEY_OF_ENCODING];
    }

    /**
     * Dispatches the codec/format for the assigned value.
     *
     * @return mixed|null the resulting encoding codec.
     */
    public function dispatch($value)
    {
        if (is_object($value)) {
            $class = get_class($value);
            foreach ($this->formats as $key => $value) {
                if (is_subclass_of($class, $key)) {
                    return $value;
                }
            }
        }
        return null;
    }

    /**
     * Checks if the specified codec has been set by the user upon
     * initialization.
     *
     * @param  string $codec the name of the codec.
     * @return bool   true if codec of the specified name is defined.
     */
    public function defined($codec)
    {
        return (
            array_key_exists($codec, $this->codecs)
            || array_key_exists($codec, $this->options)
            || array_key_exists(get_class($codec), $this->formats)
        );
    }

    /**
     * Checks if the specified codec exists.
     *
     * @param  string $codec the name of the option.
     * @return bool   true if the specified codec has been set.
     */
    public function __isset($codec)
    {
        return (
            array_key_exists($codec, $this->codecs)
            || array_key_exists($codec, $this->options)
            || array_key_exists(get_class($codec), $this->formats)
        ) && $this->__get($codec) !== null;
    }

    /**
     * Returns the codec instance of the specified name.
     *
     * @param  string     $codec the name of the codec.
     * @return mixed|null the codec instance of the specified name.
     */
    public function __get($codec)
    {
        if (isset($this->codecs[$codec]) || array_key_exists($codec, $this->codecs)) {
            return $this->codecs[$codec];
        }

        $class = get_class($codec);
        foreach ($this->formats as $key => $value) {
            if (is_subclass_of($class, $key)) {
                return $value;
            }
        }

        if (isset($this->options[$codec]) || array_key_exists($codec, $this->options)) {
            $value = $this->options[$codec];
            unset($this->options[$codec]);

            if ($value instanceof CodecInterface) {
                return $this->codecs[$codec] = $value;
            }
        }

        return null;
    }
}