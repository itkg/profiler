<?php

namespace Itkg\Profiler;

use Symfony\Component\HttpFoundation\Request;

/**
 * Interface ProfilerInterface
 * @package Itkg\Profiler
 */
interface ProfilerInterface
{
    /**
     * End profiling
     *
     * @param Request $request
     * @return void
     */
    public function profile(Request $request);

    /**
     * Start profiling
     *
     * @return mixed
     */
    public function start();
}
