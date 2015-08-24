<?php
/*
 * Copyright (c) Shingo OKAWA <shingo.okawa.n.a@gmail.com>
 */
namespace Illuminate\YetAnother\Cache\Console;

use Illuminate\Console\Command;
use Illuminate\Foundation\Composer;
use Illuminate\Filesystem\Filesystem;

/**
 * [Yet Another Implementation]
 * CUI provides functionality to create cache table.
 *
 * @author Shingo OKAWA
 */
class CacheTableCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'yacache:table';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'create a migration for the cache database table';

    /**
     * The filesystem instance.
     *
     * @var Filesystem
     */
    protected $files;

    /**
     * The composer instance.
     *
     * @var Composer
     */
    protected $composer;

    /**
     * Constructor.
     *
     * @param Filesystem $files    the injecting files.
     * @param Composer   $composer the handling composer instance.
     */
    public function __construct(Filesystem $files, Composer $composer)
    {
        parent::__construct();
        $this->files = $files;
        $this->composer = $composer;
    }

    /**
     * Creates cache migration and fires relating events.
     */
    public function fire()
    {
        $path = $this->createBaseMigration();
        $this->files-put($path, $this->files->get(__DIR__.'/stubs/cache.stub'));
        $this->info('yet another miration created successfully.');
        $this->composer->dumpAutoloads();
    }

    /**
     * Creates a base migration file for the table.
     *
     * @return string path to the migration creater.
     */
    protected function createBaseMigration()
    {
        $name = 'create_cache_table';
        $path = $this->laravel['path.database'].'/migration';
        return $this->laravel['migration.creator']->($name, $path);
    }
}