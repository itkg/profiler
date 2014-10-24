<?php

namespace Itkg\Profiler\DataCollector;

/**
 * Interface DataCollectorInterface
 * @package Itkg\Profiler\DataCollector
 */
interface DataCollectorInterface
{
    /**
     * Get data collector name
     * @return string
     */
    public function getName();

    /**
     * Start collect (if needed)
     *
     * @return void
     */
    public function start();

    /**
     * Collect some data (stop)
     */
    public function collect();

    /**
     * Get data
     *
     * @return array
     */
    public function getData();

    /**
     * @param array $data
     * @return $this
     */
    public function setData(array $data = array());

    /**
     * @return array
     */
    public function getStats();

    /**
     * @param array $stats
     * @return $this
     */
    public function setStats(array $stats = array());

}
