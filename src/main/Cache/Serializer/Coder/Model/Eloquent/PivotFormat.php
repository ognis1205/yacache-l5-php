<?php
/*
 * Copyright (c) Shingo OKAWA <shingo.okawa.n.a@gmail.com>
 */
namespace Illuminate\YetAnother\Cache\Serializer\Coder\Model\Eloquent;

use \Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\YetAnother\Cache\Serializer\Coder\CodecInterface;

/**
 * [Yet Another Implementation]
 * Provides functionality to encode/decode Eloquent Pivots.
 *
 * @author Shingo OKAWA
 */
class PivotFormat extends ModelFormat implements CodecInterface
{
    /**
     * Holds key string which refers table name.
     *
     * @const string the key string to refer table name in cache.
     */
    const KEY_OF_TABLE_NAME = 'table_name';

    /**
     * Holds key string which refers foreign key.
     *
     * @const string the key string to refer foreign key in cache.
     */
    const KEY_OF_FOREIGN_KEY = 'foreign_key';

    /**
     * Holds key string which refers other key.
     *
     * @const string the key string to refer other key in cache.
     */
    const KEY_OF_OTHER_KEY = 'other_key';

    /**
     * Formats given Pivot instance into the canonical form.
     *
     * @param  Pivot $pivot the handling Pivot instance.
     * @return mixed        the resulting intermediate Format instance.
     */
    public function format($pivot)
    {
        if (!$pivot instanceof Pivot) {
            // TODO: Implement appropriate exception.
            throw new Exception(
                "cannot encode value of class '".get_class($pivot)."'"
            );
        }

        return parent::format($pivot);
    }

    /**
     * Instanciates Pivot instance from the canonical intermediate form.
     *
     * @param  mixed $record the handling record.
     * @return Pivot         the resulting instanciated Pivot instance.
     */
    public function parse($record)
    {
        return parent::parse($record);
    }

    /**
     * Aggregates data handling to the subclasses.
     *
     * @param  array       $data  the handling internediate data.
     * @param  array|Model $value the handling Model instance.
     * @return array              the resulting intermediate Format instance.
     */
    protected function aggregate(array $data, Model $value)
    {
        $data[self::KEY_OF_TABLE_NAME] = $value->getTable();
        $data[self::KEY_OF_FOREIGN_KEY] = $value->getForeignKey();
        $data[self::KEY_OF_OTHER_KEY] = $value->getOtherKey();
        return $data;
    }

    /**
     * Instanciates the given possible Model subclass.
     *
     * @param  string $class the handling Model class.
     * @param  array  $data  the handling possible Model instance.
     * @return mixed         the resulting instanciated Model isntace.
     */
    protected function instantiate($class, array $data)
    {
        $parent = $this->coder->decodedAt(1);

        $model = new $class(
            $parent,
            $data[self::KEY_OF_ATTRIBUTES],
            $data[self::KEY_OF_TABLE_NAME],
            true
        );

        $model->setPivotKeys(
            $data[self::KEY_OF_FOREIGN_KEY],
            $data[self::KEY_OF_OTHER_KEY]
        );

        return $model;
    }
}