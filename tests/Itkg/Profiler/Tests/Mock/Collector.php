<?php
/**
 * Created by PhpStorm.
 * User: pdenis
 * Date: 28/09/14
 * Time: 13:57
 */

namespace Itkg\Profiler\Tests\Mock;


use Itkg\Profiler\DataCollector\DataCollector;

class Collector extends DataCollector
{

    /**
     * Get data collector name
     * @return string
     */
    public function getName()
    {
        return 'stub';
    }

    /**
     * Collect some data
     */
    public function collect()
    {}
}