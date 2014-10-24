<?php

namespace Itkg\Profiler\Tests\Profiler;

use Itkg\Profiler\DataCollector\CacheDataCollector;
use Itkg\Profiler\DataCollector\DatabaseDataCollector;
use Itkg\Profiler\Profiler;
use Itkg\Profiler\Storage\FileStorage;
use Symfony\Component\HttpFoundation\Request;

class ProfilerTest extends \PHPUnit_Framework_TestCase
{
    public function testConstruct()
    {
        $storage = new FileStorage(__DIR__);
        $profiler = new Profiler($storage);
        $this->assertEquals($storage, $profiler->getStorage());
        $this->assertEquals($profiler, $profiler->setStorage($storage));
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

    public function testProfile()
    {
        $collectorStub = $this->getMock('Itkg\Profiler\DataCollector\CacheDataCollector');
        $storageStub   = $this->getMock('Itkg\Profiler\Storage\FileStorage', array(), array('data'));

        $profiler = new Profiler($storageStub);
        $profiler->addCollector($collectorStub);

        $collectorStub->expects($this->once())->method('collect');
        $storageStub->expects($this->once())->method('save');
        $profiler->profile(Request::createFromGlobals());
    }

    public function testStart()
    {
        $collectorStub = $this->getMock('Itkg\Profiler\DataCollector\CacheDataCollector');
        $storageStub   = $this->getMock('Itkg\Profiler\Storage\FileStorage', array(), array('data'));

        $profiler = new Profiler($storageStub);
        $profiler->addCollector($collectorStub);

        $collectorStub->expects($this->once())->method('start');
        $storageStub->expects($this->once())->method('load');
        $profiler->start();
    }
} 