<?php
/*
 * Copyright (c) Shingo OKAWA <shingo.okawa.n.a@gmail.com>
 */
namespace Illuminate\YetAnother\Cache\Serializer\Coder;

/**
 * [Yet Another Implementation]
 * Provides functionality to encode/decode values.
 *
 * @author Shingo OKAWA
 */
interface CodecInterface
{
    /**
     * Sets the Coder instance which handles management
     * operations of codecs.
     *
     * @param CoderInterface $coder coder instance which is
     *                              responsible for handling
     *                              codecs.
     */
    public function setCoder(CoderInterface $coder);

    /**
     * Encodes given value into the serialized codes in accordance with
     * the predefined codec.
     *
     * @param  mixed $value value to be encoded.
     * @return array array of the encoded strings.
     */
    public function encode($value);

    /**
     * Decodes given value into the on-memory instance in accordance with
     * the predefined codec.
     *
     * @param  array $value value to be decoded.
     * @return mixed instance which is decoded.
     */
    public function decode($value);
}