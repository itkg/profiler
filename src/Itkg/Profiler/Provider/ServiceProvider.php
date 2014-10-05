<?php

namespace Itkg\Profiler\Provider;

use Itkg\Core\Provider\ServiceProviderInterface;
use Itkg\Profiler\DataCollector\CacheDataCollector;
use Itkg\Profiler\DataCollector\DatabaseDataCollector;
use Itkg\Profiler\Listener\ProfilerListener;
use Itkg\Profiler\Manager\ProfilerManager;
use Itkg\Profiler\Profiler;
use Itkg\Profiler\Storage\FileStorage;
use Itkg\Profiler\Storage\SessionStorage;
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

        $container['profiler'] = $mainContainer->share(
            function ($container) use ($mainContainer) {
                $profiler = new Profiler(
                    new FileStorage($mainContainer['config']->get('profiler_path'))
                );
                $profiler->addCollector($container['collector.cache']);
                $profiler->addCollector($container['collector.database']);

                return $profiler;
            }
        );

        $container['session_storage'] = $mainContainer->share(
            function () use ($mainContainer) {
                return new SessionStorage(
                    $mainContainer['session']
                );
            }
        );

        $container['profiler_manager'] = $mainContainer->share(
            function ($container) use ($mainContainer) {
                return new ProfilerManager(
                    $container['profiler']
                );
            }
        );

        $container['collector.cache'] = $mainContainer->share(
            function () {
                return new CacheDataCollector();
            }
        );

        $container['collector.database'] = $mainContainer->share(
            function () {
                return new DatabaseDataCollector();
            }
        );

        $container['listener'] = $mainContainer->share(
            function () use ($container) {
                return new ProfilerListener($container['profiler']);
            }
        );

        $container['template_finder'] = $mainContainer->share(
            function () {
                return new Finder();
            }
        );

        $mainContainer['profiler'] = $container;
    }
}
