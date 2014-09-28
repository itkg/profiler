<?php

use Itkg\Profiler\Profiler;
use Itkg\Profiler\Storage\FileStorage;
use Itkg\Profiler\Template\Finder;
use Itkg\Profiler\Tests\Mock\Profiler as ProfilerMock;

class FinderTest extends \PHPUnit_Framework_TestCase
{
    public function testGetBasePath()
    {
        $finder = new Finder();

        $profiler = new Profiler(new FileStorage(__DIR__));
        $reflector = new \ReflectionObject($profiler);

        $directory = dirname($reflector->getFileName());

        // Specific profiler template
        $this->assertEquals($directory.'/Resources/views/base.html.php', $finder->getBasePath($profiler));

        // Default template
        $this->assertEquals($directory.'/Template/../Resources/views/base.html.php', $finder->getBasePath(new ProfilerMock(new FileStorage(__DIR__))));
    }

    public function testGetCollectorPath()
    {
        $finder = new Finder();
        $collector = new \Itkg\Profiler\DataCollector\CacheDataCollector();
        $reflector = new \ReflectionObject($collector);

        $directory = dirname($reflector->getFileName());
        $this->assertEquals($directory.'/../Resources/views/collector/cache.html.php', $finder->getCollectorPath($collector));
    }

    /**
     * @expectedException \Itkg\Profiler\Exception\NotFoundException
     */
    public function testNotFoundCollectorPath()
    {
        $finder = new Finder();
        $collector = new \Itkg\Profiler\Tests\Mock\Collector();
        $finder->getCollectorPath($collector);
    }

}
