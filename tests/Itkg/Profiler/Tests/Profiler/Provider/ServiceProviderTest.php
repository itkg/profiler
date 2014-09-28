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

        $stub = $this->getMock('Itkg\Profiler\Provider\ServiceProvider');
        $container->register(new \Itkg\Profiler\Provider\ServiceProvider());

        // profiler & collectors
        $this->assertInstanceOf('Itkg\Profiler\ProfilerInterface', $container['profiler']['profiler']);
        $this->assertInstanceOf('Itkg\Profiler\DataCollector\CacheDataCollector', $container['profiler']['collector.cache']);
        $this->assertInstanceOf('Itkg\Profiler\DataCollector\DatabaseDataCollector', $container['profiler']['collector.database']);
        $this->assertInstanceOf('Itkg\Profiler\Manager\ProfilerManagerInterface', $container['profiler']['profiler_manager']);

        // bar
        $this->assertInstanceOf('Itkg\Profiler\Listener\ProfilerListener', $container['profiler']['listener']);

        // renderer
        $this->assertInstanceOf('Itkg\Profiler\Template\Finder', $container['profiler']['template_finder']);

        $stub->expects($this->once())
            ->method('register');
        $container->register($stub);
    }
}
