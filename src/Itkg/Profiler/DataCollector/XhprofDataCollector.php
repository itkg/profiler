<?php

namespace Itkg\Profiler\DataCollector;

/**
 * @author Pascal DENIS <pascal.denis@businessdecision.com>
 */
class XhprofDataCollector extends DataCollector
{
    /**
     * Start xhprof profiler
     */
    public function start()
    {

        if (!extension_loaded('xhprof')) {
            throw new \RuntimeException('xhprof extension is not loaded');
        }

        xhprof_enable(XHPROF_FLAGS_CPU + XHPROF_FLAGS_MEMORY);
    }

    /**
     * collect data
     */
    public function collect()
    {
        if (!extension_loaded('xhprof')) {
            throw new \RuntimeException('xhprof extension is not loaded');
        }

        $this->addData(\xhprof_disable());

    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'xhprof';
    }

    /**
     * Create stats from current set of data
     */
    protected function createStats()
    {
        $key = date('d/m/Y à h:i');
        foreach ($this->data as $k => $data) {
            $memory = 0;
            $peakMemory = 0;
            $cpuTime = 0;
            $waitTime = 0;

            foreach ($data as $method => $values) {
                $memory     += $values['mu'];
                $peakMemory += $values['pmu'];
                $cpuTime    += $values['cpu'];
                $waitTime   += $values['wt'];
            }

            $this->stats[$k][$key] = array(
                'date' => $key,
                'memory' => $memory,
                'peak_memory' => $peakMemory,
                'cpu' => $cpuTime,
                'wait' => $waitTime
            );
        }
    }
}
