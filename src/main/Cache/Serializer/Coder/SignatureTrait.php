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
trait SignatureTrait
{
    /**
     * Returns signatured object which is bind to the given object with
     * assigned signature.
     *
     * @param  mixed  $data      the handling data to be signed.
     * @param  string $signature the signature string.
     * @return mixedthe          resulting object which is bundled with
     *                           the specified signature.
     */
    public function putSignature($data, $signature)
    {
        return [
            'signature' => $signature,
            'data'      => $data
        ];
    }

    /**
     * Returns the signature fo the data if it is possible.
     *
     * @param  mixed $data the handling data.
     * @return string      the resulting signature if possible, else null.
     */
    public function getSignature($data)
    {
        if (
            isset($data['signature'])
            || array_key_exists('signature', $data)
        ) {
            return $data['signature'];
        }
        return null;
    }

    /**
     * Returns the signature fo the data if it is possible.
     *
     * @param  mixed $data the handling data.
     * @return string      the resulting signature if possible, else null.
     */
    public function extract($signed)
    {
        if (
            isset($signed['data'])
            || array_key_exists('data', $signed)
        ) {
            return $signed['data'];
        }
        return null;
    }
}