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
trait CoderDrivenTrait
{
    /**
     * Holds coder.
     *
     * @var CoderInterface
     */
    protected $coder;

    /**
     * Sets the Coder instance which handles management
     * operations of codecs.
     *
     * @param CoderInterface $coder coder instance which is
     *                              responsible for handling
     *                              codecs.
     */
    public function setCoder(CoderInterface $coder)
    {
        $this->coder = $coder;
    }

    /**
     * Encodes given value into the serialized codes in accordance with
     * the predefined codec.
     *
     * @param  mixed $value value to be encoded.
     * @return array array of the encoded strings.
     */
    public function encodeAny($value)
    {
        return $this->coder->encode($value);
    }

    /**
     * Decodes given value into the on-memory instance in accordance with
     * the predefined codec.
     *
     * @param  array $value value to be decoded.
     * @return mixed instance which is decoded.
     */
    public function decodeAny($value)
    {
        return $this->coder->decode($value);
    }
}