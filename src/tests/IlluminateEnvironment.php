<?php
namespace Illuminate\YetAnother\Tests;

use \Exception;
use \RedisAPI;
use Illuminate\Config\Repository;
use Illuminate\Database\DatabaseServiceProvider;
use Illuminate\Database\SeedServiceProvider;
use Illuminate\Filesystem\FilesystemServiceProvider;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Providers\ArtisanServiceProvider;
use Illuminate\Foundation\Providers\ConsoleSupportServiceProvider;
use \Illuminate\Support\Facades\Redis;
use Orchestra\Database\MigrationServiceProvider;
use Orchestra\Testbench\TestCase;

/**
 * Base class to provide environment for testing yet another Illuminate
 * implementations.
 *
 * @author Shingo OKAWA
 */
class IlluminateEnvironment extends TestCase
{
    /**
     * {@inheritdoc}
     */
    public function tearDown()
    {
        // TODO: Implement somethig neccessary.
    }

    /**
     * Returns the Laravel base directorie's path.
     *
     * @return string the resulting base directorie's path.
     */
    protected function getBasePath()
    {
        $basedir = realpath(__DIR__ . '/../../tests/laravel');
        if (!$basedir) {
            throw new Exception("base directory does not exist");
        }
        return $basedir;
    }

    /**
     * Configures application for test-suite.
     *
     * @param Application $app the Application instance to be configured.
     */
    protected function getEnvironmentSetUp($app)
    {
        $config = $app['config'];
        $this->getDatabaseReadyFor($app);
        $this->getClassesReadyFor($app);
        $this->getCacheConfiguredBy($config);
    }

    /**
     * Configures database for test-suite.
     *
     * @param Application $app the Application instance to be configured.
     */
    protected function getDatabaseReadyFor($app)
    {
        // TODO: Implement somethig neccessary.
    }

    /**
    
 * Configures classes for test-suite.
     *
     * @param Application $app the Application instance to be configured.
     */
    protected function getClassesReadyFor($app)
    {
        // TODO: Implement somethig neccessary.
    }

    /**
     * Configures cache for test-suites.
     *
     * @param Repository $config
     */
    protected function getCacheConfiguredBy(Repository $config)
    {
        // Configuration for Redis.
        $config->set(
//            'database.redis',
            'cache.stores.redis',
            [
                'cluster'         => false,
                'test_connection' => [
                    'host'     => '127.0.0.1',
                    'port'     => 6379,
                    'database' => 0
                ]
            ]
        );

        // Configuration for Redis.
        $config->set(
            'yacache',
            [
                'driver'     => 'redis',
                'default'    => 'redis',
                'connection' => 'test_connection',
                'prefix'     => 'prefix',
                'codec'      => 'Illuminate\YetAnother\Cache\Serializer\Codec\MsgPack'
            ]
        );
    }

    /**
     * Returns class names which will be used for tests.
     *
     * @param  Application $app the handling Application instance.
     * @return array
     */
    protected function getPackageProviders($app)
    {
        return [
            'Illuminate\Foundation\Providers\ArtisanServiceProvider',
            'Illuminate\Filesystem\FilesystemServiceProvider',
            'Illuminate\Foundation\Providers\ConsoleSupportServiceProvider',
            'Illuminate\Database\DatabaseServiceProvider',
            'Illuminate\Redis\RedisServiceProvider',
            'Illuminate\Database\SeedServiceProvider',
            'Illuminate\YetAnother\Cache\CacheServiceProvider',
            'Orchestra\Database\MigrationServiceProvider'
        ];
    }

    /**
     * Debugs out the given data.
     */
    protected function _debug($data)
    {
        fwrite(STDERR, print_r($data, TRUE));
    }
}