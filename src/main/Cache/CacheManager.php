<?php
/*
 * Copyright (c) Shingo OKAWA <shingo.okawa.n.a@gmail.com>
 */
namespace Illuminate\YetAnother\Cache;

use \Closure;
use Illuminate\Cache\CacheManager as BaseManager;
use Illuminate\Contracts\Cache\Store;
use Illuminate\YetAnother\Cache\Configuration\Options;

/**
 * [Yet Another Implementation]
 * Provides functionality to create cache driver objects.
 *
 * @author Shingo OKAWA
 */
class CacheManager extends BaseManager
{
    /**
     * Creates an instance of the file cache driver.
     *
     * @param  array $config the configuration array.
     * @return FileStore     the resulting file cache instance.
     */
    protected function createFileDriver(array $config)
    {
        $files = $this->app['files'];
        $path  = array_get($config, 'path');
        $codec = array_get($config, 'codec', 'default') ?: 'default';

        $options = new Options([
            FileStore::OPTION_OF_FILES => $files,
            FileStore::OPTION_OF_PATH  => $path,
            FileStore::OPTION_OF_CODEC => $codec
        ]);

        return $this->repository(
            new FileStore($options)
        );
    }

    /**
     * Creates an instance of the Redis cache driver.
     *
     * @param  array $config the configuration array.
     * @return RedisStore    the resulting redis cache instance.
     */
    protected function createRedisDriver(array $config)
    {
        $redis      = $this->app['redis'];
        $prefix     = $this->getPrefix($config);
        $prefix     = strlen($prefix) > 0 ? $prefix . ':' : '';
        $connection = array_get($config, 'connection', 'default') ?: 'default';
        $codec      = array_get($config, 'codec', 'default') ?: 'default';

        $options = new Options([
            RedisStore::OPTION_OF_REDIS      => $redis,
            RedisStore::OPTION_OF_PREFIX     => $prefix,
            RedisStore::OPTION_OF_CONNECTION => $connection,
            RedisStore::OPTION_OF_CODEC      => $codec
        ]);

        return $this->repository(
            new RedisStore($options)
        );
    }
}