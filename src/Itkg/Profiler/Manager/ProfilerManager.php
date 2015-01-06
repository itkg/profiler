<?php

namespace Itkg\Profiler\Manager;

use Itkg\Profiler\DataCollector\DataCollectorInterface;
use Itkg\Profiler\Profiler;

class ProfilerManager implements ProfilerManagerInterface
{
    /**
     * @var \Itkg\Profiler\Profiler
     */
    private $profiler;

    /**
     * @param Profiler $profiler
     */
    public function __construct(Profiler $profiler)
    {
        $this->profiler = $profiler;
    }

    /**
     * @param DataCollectorInterface $collector
     */
    public function clearCollector(DataCollectorInterface $collector)
    {
        $this->profiler->getStorage()->clear($collector);
    }

    /**
     * @param DataCollectorInterface $collector
     */
    public function archiveCollector(DataCollectorInterface $collector)
    {
        $storage = $this->profiler->getStorage();
        $storage->saveStats($collector);
        $storage->archive($collector);
        $storage->clear($collector);
    }

    /**
     * @param DataCollectorInterface $collector
     * @return array
     */
    public function getCollectorArchives(DataCollectorInterface $collector)
    {
        $archives = $this->profiler->getStorage()->getArchives($collector);

        return $archives;
    }

    /**
     * @param DataCollectorInterface $collector
     * @param string $current
     */
    public function loadCollector(DataCollectorInterface $collector, $current = 'current')
    {
        $this->profiler->getStorage()->load($collector, $current);
    }

    /**
     * @param DataCollectorInterface $collector
     */
    public function saveCollector(DataCollectorInterface $collector)
    {
        $this->profiler->getStorage()->save($collector);
    }
}
