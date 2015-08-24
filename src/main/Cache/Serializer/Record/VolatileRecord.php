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
class VolatileRecord implements RecordInterface
{
    /** Enables to marshalize/unmarshalize objects. */
    use MarshalizerTrait;

    /**
     * Holds key string which refers expiration time.
     *
     * @const string the key string to refer expiration time in cache.
     */
    const KEY_OF_EXPIRATION = 'expiration';

    /**
     * Holds key string which refers TTL duration.
     *
     * @const string the key string to refer TTL.
     */
    const KEY_OF_TTL = 'ttl';

    /**
     * Holds key string which refers current time.
     *
     * @const string the key string to refer current time.
     */
    const KEY_OF_NOW = 'now';

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
     * Holds expiration time.
     *
     * @var int the expiration time.
     */
    protected $expiration;

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

        $ttl = (
            isset($data[self::KEY_OF_TTL])
            || array_key_exists(self::KEY_OF_TTL, $data)
        ) ? $data[self::KEY_OF_TTL] : null;

        $now = (
            isset($data[self::KEY_OF_NOW])
            || array_key_exists(self::KEY_OF_NOW, $data)
        ) ? $data[self::KEY_OF_NOW] : null;

        if (!is_null($ttl)) {
            $this->setExpiration(($now ?: time()) + $ttl);
        } else {
            $this->setExpiration(0);
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

        if (
            isset($record[self::KEY_OF_EXPIRATION])
            || array_key_exists(self::KEY_OF_EXPIRATION, $record)
        ) {
            $this->setExpiration($record[self::KEY_OF_EXPIRATION]);
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
            self::KEY_OF_EXPIRATION => $this->expiration,
            self::KEY_OF_DATA       => $this->data
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

    /**
     * Sets the specified expiration time to the instance.
     *
     * @param int $expiration the expiration time.
     */
    public function setExpiration($expiration)
    {
        $this->expiration = $expiration;
    }

    /**
     * Returns the expiration time for this instance.
     *
     * @return int the assigned expiration time.
     */
    public function getExpiration()
    {
        return $this->expiration;
    }

    /**
     * Returns true if the currently handling object is expired.
     *
     * @return bool returns true if the instance is expired.
     */
    public function isExpired()
    {
        if (empty($this->expiration)) {
            return false;
        }
        return $this->expiration < time();
    }
}