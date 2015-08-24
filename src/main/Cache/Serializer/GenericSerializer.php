<?php
/*
 * Copyright (c) Shingo OKAWA <shingo.okawa.n.a@gmail.com>
 */
namespace Illuminate\YetAnother\Cache\Serializer;

use Illuminate\YetAnother\Cache\Serializer\Coder\GenericCoder;
use Illuminate\YetAnother\Cache\Serializer\Record\DefaultRecord;
use Illuminate\YetAnother\Utility\Time;

/**
 * [Yet Another Implementation]
 * Provides functionality to serialize/deserialize objects.
 *
 * @author Shingo OKAWA
 */
class GenericSerializer implements SerializerInterface
{
    /**
     * Holds coder instance with user defined codec for
     * serialization/deserialization.
     *
     * @var CoderInterface
     */
    protected $coder;

    /**
     * Constructor.
     *
     * @param array $options the user defined options.
     */
    public function __construct($codec)
    {
        $this->coder = new GenericCoder($codec);
    }

    /**
     * Prepares data to be sent to the cache server.
     *
     * @oaram mixed $data    data to be serialized.
     * @oaram int   $minutes expiration duration in minutes.
     * @param array|string   serialized data.
     */
    public function serialize($data, $minutes=null)
    {
        return DefaultRecord::marshalize([
            'data' => $this->coder->encode($data)
        ]);
    }

    /**
     * Prepares data to be received from the cache server.
     *
     * @param  array $records record to be deserialized.
     * @return array          unserealized datas.
     */
    public function deserialize($record)
    {
        return DefaultRecord::unmarshalize(
            $record,
            $this->coder
        );
    }
}