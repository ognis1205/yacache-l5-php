<?php
/*
 * Copyright (c) Shingo OKAWA <shingo.okawa.n.a@gmail.com>
 */
namespace Illuminate\YetAnother\Cache\Serializer\Coder;

use \Exception;

/**
 * [Yet Another Implementation]
 * GenericCoder class used for connecting and executing commands on Redis.
 *
 * @author Shingo OKAWA
 */
class GenericCoder implements CoderInterface
{
    /** Enables to generate signatured objects */
    use SignatureTrait;

    /**
     * Holds the internal dispatcher for encoding/decoding.
     *
     * @var array
     */
    protected $dispatcher;

    /**
     * Holds the internal stack for encoded values.
     *
     * @var array
     */
    protected $encoded = [];

    /**
     * Holds the internal stack for decoded values.
     *
     * @var array
     */
    protected $decoded = [];

    /**
     * Constructor.
     *
     * @param CoderInterface $encoder encoder which handle in/out come datas.
     */
    public function __construct($encoding)
    {
        $this->dispatcher = new GenericDispatcher($encoding);
    }

    /**
     * Returns the codec for the specified value.
     *
     * @parama mixed $value the value which is in current context.
     * @return CodecInterface
     */
    protected function getCodec($codec='')
    {
        if (empty($codec)) {
            return $this->dispatcher->getDefault();
        }
        return $this->dispatcher[$codec];
    }

    /**
     * Encodes given value into the serialized codes in accordance with
     * the predefined codec.
     *
     * @param  mixed $value value to be encoded.
     * @return array array of the encoded strings.
     */
    public function encode($value)
    {
        $codec = $this->dispatcher->dispatch($value) ?: $this->dispatcher->getEncoding();
        $codec->setCoder($this);
        $encoded = $codec->encode($value);
        $codec = get_class($codec);
        return $this->putSignature($encoded, $codec);
    }

    /**
     * Decodes given value into the on-memory instance in accordance with
     * the predefined codec.
     *
     * @param  mixed $value value to be decoded.
     * @return mixed instance which is decoded.
     */
    public function decode($value)
    {
        $codec = $this->getSignature($value);
        if (!class_exists($codec)) {
            // TODO: Implement appropriate exception.
            throw new Exception('invalid format specified.');
        }

        $codec = new $codec();
        $codec->setCoder($this);
        $decoded = $codec->decode(
            $this->extract($value)
        );

        return $decoded;
    }

    /**
     * Encoder manages a stack of encoded values for enabling to reconstruct
     * links from parent to child.
     */
    public function encodedAt($index = 0)
    {
        $cardinality = count($this->encoded);
        $index = $cardinality - $index - 1;
        if ($index < 0 || $index > ($cardinality - 1)) {
            // TODO: Implement appropriate exception.
            throw new Exception('no encoded data available.');
        }
        return $this->encoded[$index];
    }

    /**
     * Encoder manages a stack of decoded values for enabling to reconstruct
     * links from child to parent.
     *
     * @param  int $index
     * @return mixed
     */
    public function decodedAt($index = 0)
    {
        $cardinality = count($this->decoded);
        $index = $cardinality - $index - 1;
        if ($index < 0 || $index > ($cardinality - 1)) {
            // TODO: Implement appropriate exception.
            throw new Exception('no decoded data available.');
        }
        return $this->decoded[$index];
    }

    /**
     * Pushes the last encoded value into the internal stack.
     *
     * @param array $value
     */
    public function pushEncoded($value)
    {
        array_push($this->encoded, $value);
    }

    /**
     * Pushes the last decode value into the internal stack.
     *
     * @param mixed $value
     */
    public function pushDecoded($value)
    {
        array_push($this->decoded, $value);
    }

    /**
     * Pops up the last encoded value from theinternal stack.
     * This method is literaly just pop up things, though, any
     * object will never be returned.
     *
     * @return void
     */
    public function popEncoded()
    {
        array_pop($this->encoded);
    }

    /**
     * Pops up the last decoded value from the internal stack.
     * This metyhod isliteraly just pop up things, though, any
     * object will never be returned.
     *
     * @return void
     */
    public  function popDecoded()
    {
        array_pop($this->decoded);
    }
}
