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
     * @param Request $request
     * @return void
     */
    public function profile(Request $request);
}
