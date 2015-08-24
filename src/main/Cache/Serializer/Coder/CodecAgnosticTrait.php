<?php
/*
 * Copyright (c) Shingo OKAWA <shingo.okawa.n.a@gmail.com>
 */
namespace Illuminate\YetAnother\Cache\Serializer\Coder;

/**
 * [Yet Another Implementation]
 * Provides functionality to handle the refferable coder.
 *
 * @author Shingo OKAWA
 */
trait CodecAgnosticTrait
{
    /**
     * Encodes given value into the serialized codes in accordance with
     * the predefined codec.
     *
     * @param  mixed $value value to be encoded.
     * @return array array of the encoded strings.
     */
    public function encode($value)
    {
        //return $this->encodeAny($this->format($value));
        return $this->format($value);
    }

    /**
     * Decodes given value into the on-memory instance in accordance with
     * the predefined codec.
     *
     * @param  array $value value to be decoded.
     * @return mixed instance which is decoded.
     */
    public function decode($value)
    {
        //return $this->parse($this->decodeAny($value));
        return $this->parse($value);
    }
}