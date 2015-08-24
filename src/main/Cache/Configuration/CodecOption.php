<?php
/*
 * Copyright (c) Shingo OKAWA <shingo.okawa.n.a@gmail.com>
 */
namespace Illuminate\YetAnother\Cache\Configuration;

use \Exception;
use Illuminate\YetAnother\Cache\Serializer\Codec\CodecInterface;
use Illuminate\YetAnother\Cache\Serializer\Codec\DefaultCodec;

/**
 * [Yet Another Implementation]
 * Represents injection of the codec instance by the
 *
 * @author Shingo OKAWA
 */
class CodecOption implements OptionInterface
{
    /**
     * {@inheritdoc}
     */
    public function filter(OptionsInterface $option, $value)
    {
        if (is_string($value)) {
            if (class_exists($value)) {
                $value = new $value();
            } else {
                return $value;
            }
        }

        if (!$value instanceof CodecInterface) {
            // TODO: Implement appropriate exception.
            throw new Exception('invalid type for codec option.');
        }

        return $value;
    }

    /**
     * {@inheritdoc}
     */
    public function getDefault(OptionsInterface $options)
    {
        return new DefaultCodec();
    }
}