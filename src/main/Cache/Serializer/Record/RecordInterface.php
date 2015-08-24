<?php
/*
 * Copyright (c) Shingo OKAWA <shingo.okawa.n.a@gmail.com>
 */
namespace Illuminate\YetAnother\Cache\Serializer\Record;

use Illuminate\YetAnother\Cache\Serializer\Coder\CoderInterface;

/**
 * [Yet Another Implementation]
 * Represents internal, i.e., buffering entities to handle serialization.
 *
 * @author Shingo OKAWA
 */
interface RecordInterface
{
    /**
     * Assembles handling data into a canonical cache record form.
     *
     * @param  array $data    the handling data to be canonicalized.
     * @return VolatileRecord the resulting canonical data.
     */
    public function format(array $data);

    /**
     * Parses raw data into the record instance.
     *
     * @param  array $data            the raw data to be parsed.
     * @param  CoderInterface &$coder the raw data to be parsed.
     * @return VolatileRecord         the resulting parsed Record instance.
     */
    public function parse(array $record, CoderInterface &$coder);

    /**
     * Sets the specified data to the instance.
     *
     * @param string $data the assigning data.
     */
    public function setData($data);

    /**
     * Returns currently holding data.
     *
     * @return string the currently handling data.
     */
    public function getData();
}