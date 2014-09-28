<?php

namespace Itkg\Profiler;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

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
