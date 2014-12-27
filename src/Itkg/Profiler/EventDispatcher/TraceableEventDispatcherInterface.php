<?php

namespace Itkg\Profiler\EventDispatcher;

interface TraceableEventDispatcherInterface
{
    /**
     * @return array
     */
    public function getCalledListeners();
} 