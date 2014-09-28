<?php

namespace Itkg\Profiler\Tests\Listener;

use Itkg\Profiler\Listener\ProfilerListener;
use Itkg\Profiler\Storage\FileStorage;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ControllerResolver;
use Symfony\Component\HttpKernel\Event\FinishRequestEvent;
use Symfony\Component\HttpKernel\HttpKernel;
use Symfony\Component\HttpKernel\HttpKernelInterface;

class ProfilerListenerTest extends \PHPUnit_Framework_TestCase
{
    public function testOnFinishRequest()
    {
        $stub = $this->getMock('Itkg\Profiler\Profiler', array(), array(new FileStorage('data')));
        $listener = new ProfilerListener($stub);

        $stub->expects($this->once())->method('profile');
        $kernel = new HttpKernel(new EventDispatcher(), new ControllerResolver());
        $listener->onFinish(new FinishRequestEvent($kernel, Request::createFromGlobals(), HttpKernelInterface::MASTER_REQUEST));

        $stub->expects($this->never())->method('profile');
        $listener->onFinish(new FinishRequestEvent($kernel, Request::createFromGlobals(), HttpKernelInterface::SUB_REQUEST));

        $this->assertEquals($listener, $listener->setProfiler($stub));
    }
} 