<?php
/*
 * Copyright (c) Shingo OKAWA <shingo.okawa.n.a@gmail.com>
 */
namespace Illuminate\YetAnother\Cache\Serializer\Coder\Model\Eloquent;

use \Exception;
use \ReflectionClass;
use Illuminate\Database\Eloquent\Model;
use Illuminate\YetAnother\Cache\Serializer\Coder\CodecAgnosticTrait;
use Illuminate\YetAnother\Cache\Serializer\Coder\CodecInterface;
use Illuminate\YetAnother\Cache\Serializer\Coder\CoderDrivenTrait;

/**
 * [Yet Another Implementation]
 * Provides functionality to encode/decode Eloquent Models.
 *
 * @author Shingo OKAWA
 */
class ModelFormat implements CodecInterface
{
    /** Enables to access to the driving coder. */
    use CoderDrivenTrait;

    /** Enables to access to the driving coder. */
    use CodecAgnosticTrait;

    /**
     * Holds key string which refers class.
     *
     * @const string the key string to refer class in cache.
     */
    const KEY_OF_CLASS = 'class';

    /**
     * Holds key string which refers attributes.
     *
     * @const string the key string to refer attributes in cache.
     */
    const KEY_OF_ATTRIBUTES = 'attributes';

    /**
     * Holds key string which refers original.
     *
     * @const string the key string to refer original in cache.
     */
    const KEY_OF_ORIGINAL = 'original';

    /**
     * Holds key string which refers relations.
     *
     * @const string the key string to refer relations in cache.
     */
    const KEY_OF_RELATIONS = 'relations';

    /**
     * Formats given Model instance into the canonical form.
     *
     * @param  Model $model the handling Model instance.
     * @return mixed        the resulting intermediate Format instance.
     */
    public function format($model)
    {
        if (!$model instanceof Model) {
            // TODO: Implement appropriate exception.
            throw new Exception(
                "cannot encode value of class '".get_class($model)."'"
            );
        }

        $formatting = [
            self::KEY_OF_CLASS      => get_class($model),
            self::KEY_OF_ATTRIBUTES => $model->getAttributes(),
            self::KEY_OF_ORIGINAL   => $model->getOriginal($model),
            self::KEY_OF_RELATIONS  => []
        ];

        foreach ($model->getRelations() as $key => $value) {
            $formatting[self::KEY_OF_RELATIONS][$key] = $this->encodeAny($value);
        }

        return $this->aggregate($formatting, $model);
    }

    /**
     * Instanciates Model instance from the canonical intermediate form.
     *
     * @param  mixed $record the handling record.
     * @return Model         the resulting instanciated Model instance.
     */
    public function parse($record)
    {
        $class = $record[self::KEY_OF_CLASS];

        if (!class_exists($class)) {
            // TODO: Implement appropriate exception.
            throw new Exception(
                "cannot instantiate model '$class': cannot load class"
            );
        }

        if (!is_subclass_of($modelClass, Model::class)) {
            // TODO: Implement appropriate exception.
            throw new Exception(
                "'$class' is not a subclass of Eloquent Model"
            );
        }

        $model = $this->instantiate($class, $record);
        $model->setRawAttributes(
            $record[self::KEY_OF_ATTRIBUTES],
            true
        );
        $model->exists = true;
        $this->dispatchOriginal($record, $model);

        $this->coder->pushDecoded($model);
        foreach ($record[self::KEY_OF_RELATIONS] as $key => $value) {
            $model->setRelation($key, $this->decodeAny($value));
        }
        $this->coder->popDecoded();

        return $model;
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
        return new $class;
    }

    /**
     * Dispatches original attributes.
     *
     * @param mixed $data  the handling internediate record instance.
     * @param Model $model the tagrget Model instance.
     */
    protected function dispatchOriginal($data, Model $model)
    {
        $original = $data[self::KEY_OF_ORIGINAL];
        $reflection = new ReflectionClass($model);
        $property = $reflection->getProperty("original");
        $property->setAccessible(true);
        $property->setValue($model, $original);
        $property->setAccessible(false);
    }
}