<?php

/**
 * @author Pascal DENIS <pascal.denis@businessdecision.com>
 */
class ServiceProviderTest extends \PHPUnit_Framework_TestCase
{
    public function testRegister()
    {
        $container = new \Itkg\Core\ServiceContainer();
        $config = new \Itkg\Core\Config();
        $config['profiler_path'] = 'path/to/profiler/store/directory';
        $container->setConfig($config);
        define ('BASE_DIR', __DIR__);
        $container->register(new \Itkg\Profiler\Provider\ServiceProvider());

        // profiler & collectors
        $this->assertInstanceOf('Itkg\Profiler\ProfilerInterface', $container['profiler']['profiler']);
        $this->assertInstanceOf('Itkg\Profiler\DataCollector\CacheDataCollector', $container['profiler']['collector.cache']);
        $this->assertInstanceOf('Itkg\Profiler\DataCollector\DatabaseDataCollector', $container['profiler']['collector.database']);

        // bar
        $this->assertInstanceOf('Itkg\Profiler\Listener\ProfilerListener', $container['profiler']['listener']);

        // renderer
        $this->assertInstanceOf('Itkg\Profiler\Template\Finder', $container['profiler']['template_finder']);
    }
}
