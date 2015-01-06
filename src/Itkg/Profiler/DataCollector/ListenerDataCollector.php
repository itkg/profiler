<?php

namespace Itkg\Profiler\DataCollector;


use Itkg\Profiler\EventDispatcher\TraceableEventDispatcherInterface;

class ListenerDataCollector extends DataCollector
{
    /**
     * @var TraceableEventDispatcherInterface
     */
    protected $dispatcher;

    /**
     * @param TraceableEventDispatcherInterface $dispatcher
     */
    public function __construct(TraceableEventDispatcherInterface $dispatcher)
    {
        $this->dispatcher = $dispatcher;
    }

    /**
     * Get data collector name
     * @return string
     */
    public function getName()
    {
        return 'listener';
    }

    /**
     * Collect some data (stop)
     */
    public function collect()
    {
        $data = array();
        foreach ($this->dispatcher->getCalledListeners() as $eventName => $listeners) {

            $row = array(
                'eventName' => $eventName,
                'listeners' => array()
            );
            foreach ($listeners as $listenerInfo) {
                $row['listeners'][] = array('class' => get_class($listenerInfo[0]), 'method' => $listenerInfo[1]);
            }

            $data[] = $row;
        }
        $this->addData($data);
    }
}
