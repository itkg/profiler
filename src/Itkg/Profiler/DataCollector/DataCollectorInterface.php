<?php

namespace Itkg\Profiler\DataCollector;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

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
     * Collect some data
     */
    public function collect();

    /**
     * Save data (you may want to store data in file or DB)
     */
    public function save();

    /**
     * Get data
     *
     * @return array
     */
    public function getData();

    /**
     * Clear stored data
     *
     * @return void
     */
    public function clear();
} 