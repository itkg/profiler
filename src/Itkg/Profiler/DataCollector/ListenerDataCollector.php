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
            foreach ($listeners as $listener) {
                $row['listeners'][] = get_class($listener);
            }

            $data[] = $row;
        }
        $this->addData($data);
    }
}
