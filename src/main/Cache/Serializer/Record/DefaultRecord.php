<?php
/*
 * Copyright (c) Shingo OKAWA <shingo.okawa.n.a@gmail.com>
 */
namespace Illuminate\YetAnother\Cache\Serializer\Record;

use Illuminate\YetAnother\Cache\Serializer\Coder\CoderInterface;

/**
 * [Yet Another Implementation]
 * Represents internal, i.e., buffering entities to handle serialization.
 *
 * @author Shingo OKAWA
 */
class DefaultRecord implements RecordInterface
{
    /** Enables to marshalize/unmarshalize objects. */
    use MarshalizerTrait;

    /**
     * Holds key string which refers data.
     *
     * @const string the key string to refer data in cache.
     */
    const KEY_OF_DATA = 'data';

    /**
     * Holds handling data.
     *
     * @var string the currently holding data.
     */
    protected $data;

    /**
     * Assembles handling data into a canonical cache record form.
     *
     * @param array $data     the handling data to be canonicalized.
     * @return VolatileRecord the resulting canonical data.
     */
    public function format(array $data)
    {
        if (
            isset($data[self::KEY_OF_DATA])
            || array_key_exists(self::KEY_OF_DATA, $data)
        ) {
            $this->setData($data[self::KEY_OF_DATA]);
        } else {
            return null;
        }
        return $this;
    }

    /**
     * Parses raw data into the record instance.
     *
     * @param  array $data            the raw data to be parsed.
     * @param  CoderInterface &$coder the raw data to be parsed.
     * @return VolatileRecord         the resulting parsed Record instance.
     */
    public function parse(array $record, CoderInterface &$coder)
    {
        if (
            isset($record[self::KEY_OF_DATA])
            || array_key_exists(self::KEY_OF_DATA, $record)
        ) {
            $this->setData($coder->decode($record[self::KEY_OF_DATA]));
        } else {
            return null;
        }
        return $this;
    }

    /**
     * Stringifies the currently handling data.
     *
     * @return string the stringified data.
     */
    public function __toString()
    {
        return serialize([
            self::KEY_OF_DATA => $this->data
        ]);
    }

    /**
     * Sets the specified data to the instance.
     *
     * @param string $data the assigning data.
     */
    public function setData($data)
    {
        $this->data = $data;
    }

    /**
     * Returns currently holding data.
     *
     * @return string the currently handling data.
     */
    public function getData()
    {
        return $this->data;
    }
}