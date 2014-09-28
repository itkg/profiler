<?php

namespace Itkg\Profiler\Tests\Profiler;

use Itkg\Profiler\DataCollector\CacheDataCollector;
use Itkg\Profiler\DataCollector\DatabaseDataCollector;
use Itkg\Profiler\Profiler;
use Itkg\Profiler\Storage\FileStorage;

class ProfilerTest extends \PHPUnit_Framework_TestCase
{
    public function testConstruct()
    {
        $storage = new FileStorage(__DIR__);
        $profiler = new Profiler($storage);
        $this->assertEquals($storage, $profiler->getStorage());
    }

    public function testCollectors()
    {
        $storage = new FileStorage(__DIR__);
        $profiler = new Profiler($storage);

        $collector = new CacheDataCollector();

        // Unique collector by name
        $profiler->addCollector($collector);
        $this->assertEquals(array('cache' => $collector), $profiler->getCollectors());
        $profiler->addCollector($collector);
        $this->assertEquals(array('cache' => $collector), $profiler->getCollectors());

        $collectors = array(
            'cache' => new CacheDataCollector(),
            'database' => new DatabaseDataCollector()
        );

        $profiler->setCollectors($collectors);
        $this->assertEquals($collectors, $profiler->getCollectors());
    }
} 