<?php

namespace Itkg\Profiler\Tests\Profiler\Manager;


use Itkg\Profiler\DataCollector\CacheDataCollector;
use Itkg\Profiler\Manager\ProfilerManager;
use Itkg\Profiler\Profiler;

class ProfilerManagerTest extends \PHPUnit_Framework_TestCase
{
    public function testClearCollector()
    {
        $storage = $this->getMock('Itkg\Profiler\Storage\FileStorage', array(), array('data'));
        $manager = new ProfilerManager(new Profiler($storage));

        $storage->expects($this->once())->method('clear');

        $manager->clearCollector(new CacheDataCollector());
    }

    public function testArchiveCollector()
    {
        $storage = $this->getMock('Itkg\Profiler\Storage\FileStorage', array(), array('data'));
        $manager = new ProfilerManager(new Profiler($storage));

        $storage->expects($this->once())->method('archive');
        $storage->expects($this->once())->method('saveStats');
        $storage->expects($this->once())->method('clear');

        $manager->archiveCollector(new CacheDataCollector());
    }

    public function testSaveCollector()
    {
        $storage = $this->getMock('Itkg\Profiler\Storage\FileStorage', array(), array('data'));
        $manager = new ProfilerManager(new Profiler($storage));

        $storage->expects($this->once())->method('save');

        $manager->saveCollector(new CacheDataCollector());
    }

    public function testLoadCollector()
    {
        $storage = $this->getMock('Itkg\Profiler\Storage\FileStorage', array(), array('data'));
        $manager = new ProfilerManager(new Profiler($storage));

        $storage->expects($this->once())->method('load');

        $manager->loadCollector(new CacheDataCollector());
    }
}
