<?php
/*
 * Copyright (c) Shingo OKAWA <shingo.okawa.n.a@gmail.com>
 */
namespace Illuminate\YetAnother\Cache\Serializer;

/**
 * [Yet Another Implementation]
 * Provides functionality to serialize/deserialize objects.
 *
 * @author Shingo OKAWA
 */
interface SerializerInterface
{
    /**
     * Prepares data to be sent to the cache server.
     *
     * @oaram mixed $data    data to be serialized.
     * @oaram int   $minutes expiration duration in minutes.
     * @param array|string   serialized data.
     */
    public function serialize($data, $minutes);

    /**
     * Prepares data to be received from the cache server.
     *
     * @param  array $records record to be deserialized.
     * @return array          unserealized datas.
     */
    public function deserialize($record);
}