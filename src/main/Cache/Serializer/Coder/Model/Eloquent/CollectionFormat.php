<?php
/*
 * Copyright (c) Shingo OKAWA <shingo.okawa.n.a@gmail.com>
 */
namespace Illuminate\YetAnother\Cache\Serializer\Coder\Model\Eloquent;

use \Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\YetAnother\Cache\Serializer\Coder\CodecAgnosticTrait;
use Illuminate\YetAnother\Cache\Serializer\Coder\CodecInterface;
use Illuminate\YetAnother\Cache\Serializer\Coder\CoderDrivenTrait;

/**
 * [Yet Another Implementation]
 * Provides functionality to encode/decode Eloquent Collections.
 *
 * @author Shingo OKAWA
 */
class CollectionFormat implements CodecInterface
{
    /** Enables to access to the driving coder. */
    use CoderDrivenTrait;

    /** Enables to access to the driving coder. */
    use CodecAgnosticTrait;

    /**
     * Holds key string which refers collection items.
     *
     * @const string the key string to refer collection items in cache.
     */
    const KEY_OF_ITEMS = 'items';

    /**
     * Formats given Collecyion instance into the canonical form.
     *
     * @param  Collection $collection the handling Collection instance.
     * @return mixed                  the resulting intermediate Format instance.
     */
    public function format($collection)
    {
        $formatting = [
            self::KEY_OF_ITEMS => []
        ];

        foreach ($collecion as $item) {
            $formatting[self::KEY_OF_ITEMS][] = $this->encodeAny($item);
        }

        return $formatting;
    }

    /**
     * Instanciates Collection instance from the canonical intermediate form.
     *
     * @param  mixed $record the handling record.
     * @return Collection    the resulting instanciated Collection instance.
     */
    public function parse($record)
    {
        if (!isset($record[self::KEY_OF_ITEMS])) {
            // TODO: Implement appropriate exception.
            throw new Exception(
                "attempt to deserialize damaged collection (no 'items' data): "
            );
        }

        $collection = [];
        foreach ($record[self::KEY_OF_ITEMS] as $item) {
            $collection[] = $this->decodeAny($item);
        }

        return new Collection($collection);
    }
}