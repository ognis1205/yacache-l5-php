<?php
/*
 * Copyright (c) Shingo OKAWA <shingo.okawa.n.a@gmail.com>
 */
namespace Illuminate\YetAnother\Cache\Configuration;

/**
 * [Yet Another Implementation]
 * Provides functionality to define options.
 *
 * @author Shingo OKAWA
 */
interface OptionsInterface
{
    /**
     * Returns the default value for the given option.
     *
     * @param  string     $option the name of the option.
     * @return mixed|null the resulting default value.
     */
    public function getDefault($option);

    /**
     * Checks if thespecified option has been set by the user upon
     * initialization.
     *
     * @param  string $option the name of the option.
     * @return bool   true if option of the specified name is defined.
     */
    public function defined($option);

    /**
     * Returns the value of the specified option.
     *
     * @param  string $option the name of the option.
     * @return bool   true if the specified option has been set.
     */
    public function __isset($option);

    /**
     * Returns the value of the given option.
     *
     * @param  string     $option the name of the option.
     * @return mixed|null the assigned value of the specified option.
     */
    public function __get($option);
}