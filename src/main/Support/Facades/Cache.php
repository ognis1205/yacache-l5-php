<?php
/*
 * Copyright (c) Shingo OKAWA <shingo.okawa.n.a@gmail.com>
 */
namespace Illuminate\YetAnother\Support\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * [Yet Another Implementation]
 * Registers Yet Another Cache service to Laravel framework.
 *
 * @author Shingo OKAWA
 */
class Cache extends Facade {
	/**
	 * Get the registered name of the component.
	 *
	 * @return string
	 */
	protected static function getFacadeAccessor()
    {
        return 'yacache';
    }
}