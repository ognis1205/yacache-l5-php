<?php
/*
 * Copyright (c) Shingo OKAWA <shingo.okawa.n.a@gmail.com>
 */
namespace Illuminate\YetAnother\Cache\Configuration;

/**
 * [Yet Another Implementation]
 * Provides functionality to configure cache manager.
 *
 * @author Shingo OKAWA
 */
interface OptionInterface
{
    /**
     * Filters and validates the specified value.
     *
     * @param  OptionsInterface $options the predefined options.
     * @param  mixed            $value   the input value.
     * @return mixed            the resulting filtered value.
     */
    public function filter(OptionsInterface $options, $value);

    /**
     * Returns the default value for the option.
     *
     * @param  OptionsInterface $options the predefined options.
     * @return mixed            the predifned default value.
     */
    public function getDefault(OptionsInterface $options);
}