<?php
/*
 * Copyright (c) Shingo OKAWA <shingo.okawa.n.a@gmail.com>
 */
namespace Illuminate\YetAnother\Cache\Console;

use Illuminate\Console\Command;
use Illuminate\YetAnother\Cache\CacheManager;
use Symfony\Component\Console\Input\InputArgument;

/**
 * [Yet Another Implementation]
 * CUI provides functionality to clear the registered all caches.
 *
 * @author Shingo OKAWA
 */
class ClearCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'yacache:clear';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'flush the application cache';

    /**
     * Constructor.
     *
     * @param CacheManager $cache the handling cache manager
     */
    public function __construct(CacheManager $cache)
    {
        parent::__construct();
        $this->cache = $cache;
    }

    /**
     * Flushes all registered cache and fires relating events.
     */
    public function fire()
    {
        $store = $this->argument('store');
        $this->laravel['events']->fire('yacache:clearing', [$store]);
        $this->cache->store($store)->flush();
        $this->laravel['events']->fire('yacache:cleared', [$store]);
        $this->info('application yet another cache cleared.');
    }

    /**
     * Returns the service provided by the provider.
     *
     * @return array
     */
	protected function getArguments()
	{
		return [
			[
                'store',
                InputArgument::OPTIONAL,
                'The name of the store you would like to clear.'
            ]
		];
	}
}