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
        $this->path = BASE_DIR.$path;
        if (!is_dir($this->path)) {
            mkdir($this->path, 0777, true);
        }
    }

    /**
     * @param DataCollectorInterface $collector
     */
    public function load(DataCollectorInterface $collector)
    {
        $path = sprintf('%s/%s/%s', $this->path, $collector->getName(), $this->filename);
        $data = array();
        if (file_exists($path)) {
            $data = json_decode(file_get_contents($path), true);
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
        $this->load($collector);
        $path = sprintf('%s/%s/%s', $this->path, $collector->getName(), date('d_m_Y_h_i'));
        file_put_contents($path, json_encode($collector->getData()));

        $this->saveStats($collector);
        $this->clear($collector);
    }

    /**
     * @param DataCollectorInterface $collector
     */
    public function clear(DataCollectorInterface $collector)
    {
        $collector->setData(array());
        $this->save($collector);
    }

    /**
     * @param $path
     * @return $this
     */
    public function setPath($path)
    {
        $this->path = $path;

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
