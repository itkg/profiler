<?php

namespace Itkg\Profiler\Provider;

use Itkg\Core\Provider\ServiceProviderInterface;
use Itkg\Profiler\DataCollector\CacheDataCollector;
use Itkg\Profiler\DataCollector\DatabaseDataCollector;
use Itkg\Profiler\Profiler;
use Itkg\Profiler\Listener\ProfilerListener;
use Itkg\Profiler\Template\Finder;


/**
 * @author Pascal DENIS <pascal.denis@businessdecision.com>
 */
class ServiceProvider implements ServiceProviderInterface
{

    /**
     * Registers services on the given container.
     *
     * This method should only be used to configure services and parameters.
     * It should not get services.
     *
     * @param \Pimple $mainContainer An Container instance
     */
    public function register(\Pimple $mainContainer)
    {
        $container = new \Pimple();

        $container['profiler'] = $mainContainer->share(function() use ($container) {
            $profiler = new Profiler();
            $profiler->addCollector($container['collector.cache']);
            $profiler->addCollector($container['collector.database']);

            return $profiler;
        });

        $container['collector.cache'] = $mainContainer->share(function() use ($mainContainer) {
            return new CacheDataCollector($mainContainer['config']->get('profiler_path'));
        });

        $container['collector.database'] = $mainContainer->share(function() use ($mainContainer) {
            return new DatabaseDataCollector($mainContainer['config']->get('profiler_path'));
        });

        $container['listener'] = $mainContainer->share(function() use ($container) {
            return new ProfilerListener($container['profiler']);
        });

        $container['template_finder'] = $mainContainer->share(function() {
            return new Finder();
        });

        $mainContainer['profiler'] = $container;
    }
}
