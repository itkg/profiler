<?php

namespace Itkg\Profiler\Tests\DataCollector;

use Itkg\Core\Cache\CacheableData;
use Itkg\Core\Cache\Event\CacheEvent;
use Itkg\Profiler\DataCollector\CacheDataCollector;
use Symfony\Component\HttpFoundation\Request;

class CacheDataCollectorTest extends \PHPUnit_Framework_TestCase
{
    public function testCollect()
    {
        $data = array(
            'all' => array(
                'key_1' => array(
                    'name' => 'key_1',
                    'size' => 10,
                    'load' => 1,
                    'set'  => 1
                )
            )
        );
        $collector = new CacheDataCollector();
        $collector->setData($data);
        $collector->setRequest(Request::create('/'));
        $event = new CacheEvent(new CacheableData('key_1', 86400, 'Cache content'));
        $collector->onCacheLoad($event);

        $collector->onCacheLoad($event);

        $event = new CacheEvent(new CacheableData('key_3', 86400, 'Cache content 3'));
        $collector->onCacheLoad($event);

        $event = new CacheEvent(new CacheableData('key_2', 86400, 'Cache content 2'));

        $collector->onCacheSet($event);
        $collector->onCacheSet($event);

        $collector->collect();
        $result = array(
            'all' => array(
                'key_1' => array(
                    'size' => strlen('Cache content'),
                    'load' => 3,
                    'set'  => 1,
                    'name' => 'key_1'
                ),
                'key_3' => array(
                    'size' => strlen('Cache content 3'),
                    'load' => 1,
                    'set'  => 0,
                    'name' => 'key_3'
                ),
                'key_2' => array(
                    'size' => strlen('Cache content 2'),
                    'load' => 0,
                    'set'  => 2,
                    'name' => 'key_2'
                ),

            ),
            '/' => array(
                0 => array(
                    'name' => 'key_1',
                    'size' => strlen('Cache content'),
                    'load' => 2,
                    'set'  => 0
                ),
                1 => array(
                    'name' => 'key_3',
                    'size' => strlen('Cache content 3'),
                    'load' => 1,
                    'set'  => 0
                ),
                2 => array(
                    'name' => 'key_2',
                    'size' => strlen('Cache content 2'),
                    'load' => 0,
                    'set'  => 2
                ),
            )
        );

        $this->assertEquals($result, $collector->getData());

    }

    public function test__toString()
    {
        $collector = new CacheDataCollector();

        $this->assertEquals('Cache', (string) $collector);
    }

    public function testRequestNotSet()
    {
        $collector = new CacheDataCollector();
        $this->assertInstanceOf('Symfony\Component\HttpFoundation\Request', $collector->getRequest());
    }

    public function testStats()
    {
        $collector = new CacheDataCollector();
        $this->assertEquals(array(), $collector->getStatsForKey(null));
        $key = $collector->getRequest()->getPathInfo();
        $collector->setData(array($key => array('test')));
        
        $this->assertArrayHasKey($key, $collector->getStats());
        $this->assertNotEmpty($collector->getStatsForKey($key));
    }
} 