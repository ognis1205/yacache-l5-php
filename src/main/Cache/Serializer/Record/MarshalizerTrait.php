<?php
/*
 * Copyright (c) Shingo OKAWA <shingo.okawa.n.a@gmail.com>
 */
namespace Illuminate\YetAnother\Cache\Serializer\Record;

use Illuminate\YetAnother\Cache\Serializer\Coder\CoderInterface;

/**
 * [Yet Another Implementation]
 * Provides functionality to marshalize/unmarshalize objects.
 *
 * @author Shingo OKAWA
 */
trait MarshalizerTrait
{
    /**
     * Encodes internal record buffer instances into raw strings.
     *
     * @param mixed $data handling raw data.
     */
    public static function marshalize($data)
    {
        $marshalizing = new static();
        return (string)$marshalizing->format($data);
    }

    /**
     * Decodes raw strings into internal record buffer instances.
     *
     * @param  string $data      handling raw data.
     * @param  Coder &$coder handling raw data.
     * @return null|Record       the resulting record buffer instance.
     */
    public static function unmarshalize($data, CoderInterface &$coder)
    {
        $unmarshalizing = new static();
        if ($data === null) {
            return null;
        }
        return $unmarshalizing->parse(unserialize($data), $coder);
    }
}