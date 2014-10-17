<?php

namespace Itkg\Profiler\Listener;

use Itkg\Profiler\ProfilerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\FinishRequestEvent;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * Symfony HttpKernel bridge
 *
 * Class ProfilerListener
 * @package Itkg\Profiler\Listener
 */
class ProfilerListener implements EventSubscriberInterface
{
    /**
     * @var ProfilerInterface
     */
    protected $profiler;

    /**
     * @param ProfilerInterface $profiler
     */
    public function __construct(ProfilerInterface $profiler)
    {
        $this->profiler = $profiler;
    }

    /**
     * Returns an array of event names this subscriber wants to listen to.
     *
     * The array keys are event names and the value can be:
     *
     *  * The method name to call (priority defaults to 0)
     *  * An array composed of the method name to call and the priority
     *  * An array of arrays composed of the method names to call and respective
     *    priorities, or 0 if unset
     *
     * For instance:
     *
     *  * array('eventName' => 'methodName')
     *  * array('eventName' => array('methodName', $priority))
     *  * array('eventName' => array(array('methodName1', $priority), array('methodName2'))
     *
     * @return array The event names to listen to
     *
     * @api
     */
    public static function getSubscribedEvents()
    {
        return array(
            KernelEvents::FINISH_REQUEST => 'onFinish'
        );
    }

    /**
     * @param FinishRequestEvent $event
     */
    public function onFinish(FinishRequestEvent $event)
    {
        if ($event->getRequestType() == HttpKernelInterface::MASTER_REQUEST) {
            $this->profiler->profile($event->getRequest());
        }
    }

    /**
     * @param ProfilerInterface $profiler
     * @return $this
     */
    public function setProfiler(ProfilerInterface $profiler)
    {
        $this->profiler = $profiler;

        return $this;
    }
}
