<?php

namespace Itkg\Profiler\Tests\Profiler\Storage;

use Itkg\Profiler\DataCollector\CacheDataCollector;
use Itkg\Profiler\Storage\FileStorage;

class FileStorageTest extends \PHPUnit_Framework_TestCase
{
    public function testSave()
    {

        $storage = new FileStorage('data');
        $this->assertTrue(is_dir(TEST_BASE_DIR.'/data'));
        $collector = new CacheDataCollector();
        $collector->setData(array('My tested data'));
        $storage->save($collector);

        $this->assertTrue(is_dir(TEST_BASE_DIR.'/data/cache'));
        $this->assertTrue(file_exists(TEST_BASE_DIR.'/data/cache/current'));
        $this->assertEquals(json_encode($collector->getData()), file_get_contents(TEST_BASE_DIR.'/data/cache/current'));
    }

    public function testSaveStats()
    {

        $storage = new FileStorage('data');
        $this->assertTrue(is_dir(TEST_BASE_DIR.'/data'));
        $collector = new CacheDataCollector();
        $collector->setData(array('key' => array('My tested data')));
        $storage->saveStats($collector);

        $this->assertTrue(file_exists(TEST_BASE_DIR.'/data/cache/statistics'));
        $this->assertEquals(json_encode($collector->getStats()), file_get_contents(TEST_BASE_DIR.'/data/cache/statistics'));
    }

    public function testClear()
    {
        $storage = new FileStorage(__DIR__);
        $storage->setPath('data');
        $collector = new CacheDataCollector();
        $collector->setData(array('My tested data'));
        $storage->clear($collector);
        $this->assertFalse(file_exists(TEST_BASE_DIR.'/data/cache/current'));
        $this->assertEmpty($collector->getData());
    }

    public function testArchive()
    {
        $storage = new FileStorage('data');
        $this->assertTrue(is_dir(TEST_BASE_DIR.'/data'));
        $collector = new CacheDataCollector();
        $collector->setData(array('My tested data'));
        $storage->save($collector);
        $this->assertTrue(file_exists(TEST_BASE_DIR.'/data/cache/current'));
        $storage->archive($collector);
        chdir(TEST_BASE_DIR.'/data/cache');
        $files = glob('*');
        $this->assertCount(3, $files);
        $this->assertEquals(json_encode($collector->getData()), file_get_contents(TEST_BASE_DIR.'/data/cache/'.$files[0]));
        $this->assertEquals(json_encode($collector->getData()), file_get_contents(TEST_BASE_DIR.'/data/cache/'.$files[1]));
    }

    public function testLoad()
    {
        $storage = new FileStorage('data');
        $this->assertTrue(is_dir(TEST_BASE_DIR.'/data'));
        $collector = new CacheDataCollector();
        $data = array('My tested data');
        $collector->setData($data);
        $storage->save($collector);
        $collector->setData(array());
        $storage->load($collector);
        $this->assertEquals($data, $collector->getData());

    }
} 