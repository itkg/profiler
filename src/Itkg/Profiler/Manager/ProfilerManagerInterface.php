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
     */
    public function loadCollector(DataCollectorInterface $collector);

    /**
     * @param DataCollectorInterface $collector
     */
    public function saveCollector(DataCollectorInterface $collector);
}
