<?php

namespace Itkg\Profiler\Manager;


use Itkg\Profiler\DataCollector\DataCollectorInterface;

interface ProfilerManagerInterface
{
    /**
     * @param DataCollectorInterface $collector
     */
    public function clearCollector(DataCollectorInterface $collector);

    /**
     * @param DataCollectorInterface $collector
     */
    public function archiveCollector(DataCollectorInterface $collector);

    /**
     * @param DataCollectorInterface $collector
     * @return array
     */
    public function getCollectorArchives(DataCollectorInterface $collector);

    /**
     * @param DataCollectorInterface $collector
     * @param string $current
     */
    public function loadCollector(DataCollectorInterface $collector, $current = 'current');

    /**
     * @param DataCollectorInterface $collector
     */
    public function saveCollector(DataCollectorInterface $collector);
}
