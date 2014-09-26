<?php

namespace Itkg\Profiler;

use Itkg\Profiler\DataCollector\DataCollector;
use Itkg\Profiler\DataCollector\DataCollectorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class Profiler implements ProfilerInterface
{
    /**
     * @var array
     */
    private $collectors = array();


    /**
     * @param DataCollectorInterface $collector
     * @return $this
     */
    public function addCollector(DataCollectorInterface $collector)
    {
        $this->collectors[$collector->getName()] = $collector;

        return $this;
    }

    /**
     * @return array
     */
    public function getCollectors()
    {
        return $this->collectors;
    }

    /**
     * @param array $collectors
     * @return $this
     */
    public function setCollectors(array $collectors = array())
    {
        foreach ($collectors as $collector) {
            $this->addCollector($collector);
        }

        return $this;
    }

    /**
     * Start profiler by executing data collectors (collect & save)
     *
     * @param Request $request
     * @return void
     */
    public function profile(Request $request)
    {
        foreach ($this->collectors as $collector) {
            if ($collector instanceof DataCollector) {
                $collector->setRequest($request);
            }
            // Start collect data
            $collector->collect();

            $collector->save();
        }
    }
}
