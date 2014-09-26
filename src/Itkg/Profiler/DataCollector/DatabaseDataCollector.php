<?php

namespace Itkg\Profiler\DataCollector;

use Itkg\Core\Event\DatabaseEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * @author Pascal DENIS <pascal.denis@businessdecision.com>
 */
class DatabaseDataCollector extends DataCollector implements EventSubscriberInterface
{
    /**
     * @var array
     */
    protected static $collectedData = array();

    /**
     * Get data collector name
     * @return string
     */
    public function getName()
    {
        return 'database';
    }

    /**
     * Collect some data
     */
    public function collect()
    {
        $this->setData(self::$collectedData);
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
            'db.post_execute' => 'onPostQueryExecute'
        );
    }

    /**
     * @param DatabaseEvent $event
     */
    public function onPostQueryExecute(DatabaseEvent $event)
    {
        $data                  = $event->getData();
        self::$collectedData[] = array(
            'query'         => $event->getQuery(),
            'executionTime' => $event->getExecutionTime(),
            'bind'          => $data['bind']
        );
    }
}
