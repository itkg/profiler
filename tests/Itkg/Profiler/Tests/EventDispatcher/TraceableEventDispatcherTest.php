<?php

namespace Itkg\Profiler\Tests\EventDispatcher;

use Itkg\Core\Cache\CacheableData;
use Itkg\Core\Cache\Event\CacheEvent;
use Itkg\Profiler\DataCollector\CacheDataCollector;
use Itkg\Profiler\EventDispatcher\TraceableEventDispatcher;
use Symfony\Component\EventDispatcher\EventDispatcher;

class TraceableEventDispatcherTest extends \PHPUnit_Framework_TestCase
{
    public function testWrapper()
    {
        $dispatcherMock = $this->getMock('Symfony\Component\EventDispatcher\EventDispatcher');
        $dispatcherMock->expects($this->once())->method('addListener');
        $dispatcherMock->expects($this->once())->method('addSubscriber');
        $dispatcherMock->expects($this->once())->method('dispatch');
        $dispatcherMock->expects($this->once())->method('getListeners');
        $dispatcherMock->expects($this->once())->method('hasListeners');
        $dispatcherMock->expects($this->once())->method('removeListener');
        $dispatcherMock->expects($this->once())->method('removeSubscriber');

        $traceableDispatcher = new TraceableEventDispatcher($dispatcherMock);
        $traceableDispatcher->addListener('event.test', function() {});
        $collector = new CacheDataCollector();
        $traceableDispatcher->addSubscriber($collector);
        $traceableDispatcher->dispatch('cache.set', new CacheEvent(new CacheableData('test', 1234, null)));

        $traceableDispatcher->hasListeners('event.test');
        $traceableDispatcher->removeListener('event.test', function() {});
        $traceableDispatcher->removeSubscriber($collector);
    }

    public function testCalledListeners()
    {
        $dispatcher = new EventDispatcher();

        $traceableDispatcher = new TraceableEventDispatcher($dispatcher);
        $collector = new CacheDataCollector();
        $traceableDispatcher->addSubscriber($collector);
        $traceableDispatcher->dispatch('cache.set', new CacheEvent(new CacheableData('test', 1234, null)));

        $calledListeners = $traceableDispatcher->getCalledListeners();
        $this->assertEquals(array('cache.set' => array(0 => array($collector, 'onCacheSet'))), $calledListeners);
    }
}