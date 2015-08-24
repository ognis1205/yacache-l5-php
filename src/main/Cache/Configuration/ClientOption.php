<?php
/*
 * Copyright (c) Shingo OKAWA <shingo.okawa.n.a@gmail.com>
 */
namespace Illuminate\YetAnother\Cache\Configuration;

use \Exception;
use Illuminate\YetAnother\Cache\Serializer\Codec\CodecInterface;
use Illuminate\YetAnother\Cache\Serializer\Codec\DefaultCodec;
use Predis\Client as DefaultClient;
use Predis\YetAnother\Client;

/**
 * [Yet Another Implementation]
 * Represents injection of the redis client instance by the
 *
 * @author Shingo OKAWA
 */
class ClientOption implements OptionInterface
{
    /**
     * {@inheritdoc}
     */
    public function filter(OptionsInterface $option, $value)
    {
        if (!$this->_validate($value)) {
            // TODO: Implement appropriate exception.
            throw new Exception('invalid type for client option.');
        }

        return $value;
    }

    /**
     * {@inheritdoc}
     */
    public function getDefault(OptionsInterface $options)
    {
        return new DefaultClient();
    }

    /**
     * Checks if the assigned instance is an appropriate client.
     *
     * @param  mixed $candidate the possible redis cache client.
     * @return bool  returns true if the specified instance is an
     *                       appropriate client.
     */
    private function _validate($candidate)
    {
        return $candidate instanceof Client || $candidate instanceof DefaultClient;
    }
}