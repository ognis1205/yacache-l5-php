<?php
/*
 * Copyright (c) Shingo OKAWA <shingo.okawa.n.a@gmail.com>
 */
namespace Illuminate\YetAnother\Cache\Serializer\Coder;

/**
 * [Yet Another Implementation]
 * Provides functionality to dispatch codecs in accordance with the context.
 *
 * @author Shingo OKAWA
 */
interface DispatcherInterface
{
    /**
     * Returns the default codec for the handling context.
     *
     * @return mixed|null the resulting default codec.
     */
    public function getDefault();

    /**
     * Returns the codec for encoding.
     *
     * @return mixed|null the resulting encoding codec.
     */
    public function getEncoding();

    /**
     * Dispatches the codec/format for the assigned value.
     *
     * @return mixed|null the resulting encoding codec.
     */
    public function dispatch($value);

    /**
     * Checks if the specified codec has been set by the user upon
     * initialization.
     *
     * @param  string $codec the name of the codec.
     * @return bool   true if codec of the specified name is defined.
     */
    public function defined($codec);

    /**
     * Checks if the specified codec exists.
     *
     * @param  string $codec the name of the option.
     * @return bool   true if the specified codec has been set.
     */
    public function __isset($codec);

    /**
     * Returns the codec instance of the specified name.
     *
     * @param  string     $codec the name of the codec.
     * @return mixed|null the codec instance of the specified name.
     */
    public function __get($codec);
}