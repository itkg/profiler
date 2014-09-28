<?php

namespace Itkg\Profiler\Storage;

use Itkg\Profiler\DataCollector\DataCollectorInterface;

interface StorageInterface
{
    /**
     * @param DataCollectorInterface $collector
     * @return void
     */
    public function load(DataCollectorInterface $collector);

    /**
     * @param DataCollectorInterface $collector
     * @return void
     */
    public function save(DataCollectorInterface $collector);

    /**
     * @param DataCollectorInterface $collector
     * @return void
     */
    public function archive(DataCollectorInterface $collector);

    /**
     * @param DataCollectorInterface $collector
     * @return void
     */
    public function clear(DataCollectorInterface $collector);

    /**
     * Save collector statistics
     * @param DataCollectorInterface $collector
     * @return void
     */
    public function saveStats(DataCollectorInterface $collector);
}
