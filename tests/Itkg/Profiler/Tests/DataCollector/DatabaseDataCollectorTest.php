<?php

namespace Itkg\Profiler\Tests\DataCollector;

use Itkg\Core\Event\DatabaseEvent;
use Itkg\Profiler\DataCollector\DatabaseDataCollector;
use Symfony\Component\HttpFoundation\Request;

class DatabaseDataCollectorTest extends \PHPUnit_Framework_TestCase
{

    public function testCollect()
    {
        $collector = new DatabaseDataCollector();

        $event = new DatabaseEvent('SELECT * FROM MY_TABLE', 0.12, array('bind' => 'binds'));

        $request = Request::create('/');
        $collector->setRequest($request);
        $result = array(
            '/' => array(
                array(
                    'query'         => $event->getQuery(),
                    'executionTime' => $event->getExecutionTime(),
                    'bind'          => 'binds'
                ),
                array(
                    'query'         => $event->getQuery(),
                    'executionTime' => $event->getExecutionTime(),
                    'bind'          => 'binds'
                )
            )
        );
        $collector->onPostQueryExecute($event);
        $collector->onPostQueryExecute($event);
        $collector->collect();

        $this->assertEquals($result, $collector->getData());
    }
}
