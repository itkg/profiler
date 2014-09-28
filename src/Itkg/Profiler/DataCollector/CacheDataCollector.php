<?php

namespace Itkg\Profiler\DataCollector;

use Itkg\Core\Cache\Event\CacheEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * @author Pascal DENIS <pascal.denis@businessdecision.com>
 */
class CacheDataCollector extends DataCollector implements EventSubscriberInterface
{
    /**
     * @var array
     */
    protected static $collectedData = array();

    /**
     * Collect data
     */
    public function collect()
    {
        $all = $this->getDataForKey('all');
        $data = array();
        foreach (self::$collectedData as $key => $values) {
            $values = array(
                'name' => $key,
                'load' => (isset($values['load'])) ? $values['load'] : 0,
                'set' => (isset($values['set'])) ? $values['set'] : 0,
                'size' => (isset($values['size'])) ? $values['size'] : '?'
            );

            if (isset($all[$key])) {
                $all[$key]['load'] += $values['load'];
                $all[$key]['set'] += $values['set'];
                $all[$key]['size'] = $values['size'];
            } else {
                $all[$key] = $values;
            }
            $data[] = $values;
        }
        $this->addData($all, 'all');
        $this->addData($data);
    }

    /**
     * Returns the unique name of the collector
     *
     * @return string
     */
    public function getName()
    {
        return 'cache';
    }

    /**
     * Store loaded information
     *
     * @param CacheEvent $event
     */
    public function onCacheLoad(CacheEvent $event)
    {
        $key = $event->getCachabledata()->getHashKey();
        if (isset(self::$collectedData[$key]['load'])) {
            self::$collectedData[$key]['load']++;
        } else {
            self::$collectedData[$key]['load'] = 1;
        }

        self::$collectedData[$key]['size'] = strlen($event->getCachabledata()->getDataForCache());
    }

    /**
     * Store cache set information
     *
     * @param $event
     */
    public function onCacheSet(CacheEvent $event)
    {
        $key = $event->getCachabledata()->getHashKey();
        if (isset(self::$collectedData[$key]['set'])) {
            self::$collectedData[$key]['set']++;
        } else {
            self::$collectedData[$key]['set'] = 1;
        }

        self::$collectedData[$key]['size'] = strlen($event->getCachabledata()->getDataForCache());
    }

    /**
     * Returns the events to which this class has subscribed.
     *
     * Return format:
     *     array(
     *         array('event' => 'the-event-name', 'method' => 'onEventName', 'class' => 'some-class', 'format' => 'json'),
     *         array(...),
     *     )
     *
     * The class may be omitted if the class wants to subscribe to events of all classes.
     * Same goes for the format key.
     *
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return array(
            'cache.load' => 'onCacheLoad',
            'cache.set' => 'onCacheSet',
            'cache.remove' => 'onCacheRemove'
        );
    }
}
