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
interface CoderInterface
{
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
     * @param  mixed $value value to be decoded.
     * @return mixed instance which is decoded.
     */
    public function decode($value);

    /**
     * Encoder manages a stack of encoded values for enabling to reconstruct
     * links from parent to child.
     */
    public function encodedAt($index=0);

    /**
     * Encoder manages a stack of decoded values for enabling to reconstruct
     * links from child to parent.
     *
     * @param int $index
     * @return mixed
     */
    public function decodedAt($index=0);

    /**
     * Pushes the last encoded value into the internal stack.
     *
     * @param array $value
     */
    public function pushEncoded($value);

    /**
     * Pushes the last decode value into the internal stack.
     *
     * @param mixed $value
     */
    public function pushDecoded($value);

    /**
     * Pops up the last encoded value from theinternal stack.
     * This method is literaly just pop up things, though, any
     * object will never be returned.
     *
     * @return void
     */
    public function popEncoded();

    /**
     * Pops up the last decoded value from the internal stack.
     * This metyhod isliteraly just pop up things, though, any
     * object will never be returned.
     *
     * @return void
     */
    public function popDecoded();
}