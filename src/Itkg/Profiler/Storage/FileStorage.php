<?php

namespace Itkg\Profiler\Storage;


use Itkg\Profiler\DataCollector\DataCollectorInterface;

class FileStorage implements StorageInterface
{
    /**
     * @var string
     */
    private $path;
    /**
     * @var string
     */
    private $filename = 'current';
    /**
     * @var string
     */
    private $statsFilename = 'statistics';

    /**
     * @param string $path
     */
    public function __construct($path)
    {
        $this->path = BASE_DIR . $path;
        if (!is_dir($this->path)) {
            mkdir($this->path, 0777, true);
        }
    }

    /**
     * @param DataCollectorInterface $collector
     * @param string $current
     */
    public function load(DataCollectorInterface $collector, $current = 'current')
    {
        $this->filename = $current;
        $path = sprintf('%s/%s/%s', $this->path, $collector->getName(), $this->filename);
        $data = array();
        if (file_exists($path)) {
            $data = (array) json_decode(file_get_contents($path), true);
        }
        $collector->setData($data);
        $this->loadStats($collector);
    }

    /**
     * @param DataCollectorInterface $collector
     */
    public function save(DataCollectorInterface $collector)
    {
        $path = sprintf('%s/%s/%s', $this->path, $collector->getName(), $this->filename);
        $directory = sprintf('%s/%s', $this->path, $collector->getName());
        if (!is_dir($directory)) {
            mkdir($directory, 0777, true);
        }
        file_put_contents($path, json_encode($collector->getData()));
    }

    /**
     * @param DataCollectorInterface $collector
     */
    public function archive(DataCollectorInterface $collector)
    {
        $path = sprintf('%s/%s/%s', $this->path, $collector->getName(), date('d_m_Y_h_i'));
        file_put_contents($path, json_encode($collector->getData()));
    }

    /**
     * @param DataCollectorInterface $collector
     */
    public function clear(DataCollectorInterface $collector)
    {
        $path = sprintf('%s/%s/%s', $this->path, $collector->getName(), $this->filename);

        if (file_exists($path)) {
            unlink($path);
        }
        $collector->setData(array());
    }

    /**
     * @param $path
     * @return $this
     */
    public function setPath($path)
    {
        $this->path = BASE_DIR . $path;

        return $this;
    }

    /**
     * Save collector statistics
     * @param DataCollectorInterface $collector
     * @return void
     */
    public function saveStats(DataCollectorInterface $collector)
    {
        $path = sprintf('%s/%s/%s', $this->path, $collector->getName(), $this->statsFilename);

        file_put_contents($path, json_encode($collector->getStats()));
    }

    /**
     * Get collector archives
     *
     * @param DataCollectorInterface $collector
     * @return array
     */
    public function getArchives(DataCollectorInterface $collector)
    {
        $path = sprintf('%s/%s', $this->path, $collector->getName());

        $archives = array();

        foreach (new \DirectoryIterator($path) as $i => $fileInfo) {
            if ($fileInfo->isDot() || $fileInfo->getFilename() == 'statistics') {
                continue;
            }
            $key = ($fileInfo->getFilename() == $this->filename) ? 'current' : $i;
            $archives[$key] = $fileInfo->getFilename();
        }

        return $archives;
    }

    /**
     * @param DataCollectorInterface $collector
     */
    private function loadStats(DataCollectorInterface $collector)
    {
        $path = sprintf('%s/%s/%s', $this->path, $collector->getName(), $this->statsFilename);
        $stats = array();
        if (file_exists($path)) {
            $stats = json_decode(file_get_contents($path), true);
        }
        $collector->setStats($stats);
    }
}
