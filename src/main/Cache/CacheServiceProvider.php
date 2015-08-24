<?php
/*
 * Copyright (c) Shingo OKAWA <shingo.okawa.n.a@gmail.com>
 */
namespace Illuminate\YetAnother\Cache;

use Illuminate\Cache\CacheServiceProvider as BaseProvider;
use Illuminate\YetAnother\Cache\Console\CacheTableCommand;
use Illuminate\YetAnother\Cache\Console\ClearCommand;

/**
 * [Yet Another Implementation]
 * Provides functionality to provide cache service.
 *
 * @author Shingo OKAWA
 */
class CacheServiceProvider extends BaseProvider
{
    /**
     * Registers the service provider.
     */
    public function register()
    {
        parent::register();

        $this->app->singleton('yacache', function($app) {
            return new CacheManager($app);
        });

        $this->app->singleton('yacache.store', function($app) {
            return $app['yacache']->driver();
        });

        $this->registerCommands();
    }

    /**
     * Registers the cache related console commands.
     */
    public function registerCommands()
    {
        parent::registerCommands();

        $this->app->singleton('command.yacache.clear', function($app) {
            return new ClearCommand($app['yacache']);
        });

        $this->app->singleton('command.yacache.table', function($app) {
            return new CacheTableCommand($app['files'], $app['composer']);
        });

        $this->commands('command.yacache.clear', 'command.yacache.table');
    }

    /**
     * Returns the services provided by the provider.
     */
    public function provides()
    {
        $result = parent::provides();
        return array_merge($result, [
            'yacache', 'yacache.store', 'command.yacache.clear', 'command.yacache.table'
        ]);
    }
}