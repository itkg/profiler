<?php

namespace Itkg\Profiler\Template;

use Itkg\Profiler\DataCollector\DataCollector;
use Itkg\Profiler\Exception\NotFoundException;
use Itkg\Profiler\Profiler;

class Finder
{
    const VIEWS_PATH = 'Resources/views';

    /**
     * Return base template path
     * Check if base.php.html is defined in the same namespace as profiler
     * If not return const base path
     *
     * @param Profiler $profiler
     * @return string
     */
    public function getBasePath(Profiler $profiler)
    {
        $reflector = new \ReflectionObject($profiler);

        $directory = dirname($reflector->getFileName());
        if (file_exists($baseFile = sprintf('%s/%s/base.html.php', $directory, self::VIEWS_PATH))) {
            return $baseFile;
        }

        return sprintf('%s/../%s/base.html.php', __DIR__, self::VIEWS_PATH);
    }

    /**
     * Return collector template path
     * Check if collector_name.php.html is defined in the same namespace as collector
     * @param DataCollector $collector
     * @throws \Itkg\Profiler\Exception\NotFoundException
     * @return string
     */
    public function getCollectorPath(DataCollector $collector)
    {
        $reflector = new \ReflectionObject($collector);

        $directory = dirname($reflector->getFileName());

        if (file_exists(
            $baseFile = sprintf('%s/../%s/collector/%s.html.php', $directory, self::VIEWS_PATH, $collector->getName())
        )
        ) {
            return $baseFile;
        }

        throw new NotFoundException(sprintf('No template defined for %s collector', $collector->getName()));
    }
}
