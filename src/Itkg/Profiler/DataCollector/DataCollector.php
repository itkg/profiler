<?php

namespace Itkg\Profiler\DataCollector;

use Itkg\Core\ServiceContainer;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @author Pascal DENIS <pascal.denis@businessdecision.com>
 */
abstract class DataCollector implements DataCollectorInterface
{
    /**
     * @var string
     */
    protected $path;

    /**
     * @var array
     */
    protected $data = array();

    /**
     * @var Request
     */
    protected $request;

    /**
     * @param string $profilerPath
     */
    public function __construct($profilerPath)
    {
        $this->path = sprintf('%s/%s/%s/current', BASE_DIR, $profilerPath, $this->getName());
        $directory = sprintf('%s/%s/%s', BASE_DIR, $profilerPath, $this->getName());
        if (!is_dir($directory)) {
            mkdir($directory, 0777, true);
        }
        $this->data = $this->getStoredData();
    }

    /**
     * Save data
     */
    public function save()
    {
        ksort($this->data);
        file_put_contents(
            $this->path,
            json_encode($this->data)
        );
    }

    /**
     * clear stored data
     */
    public function clear()
    {
        if (file_exists($this->path)) {
            unlink($this->path);
            $this->data = array();
        }
    }

    /**
     * Archive current data
     *
     * @return void
     */
    public function archive()
    {
        /**
         * @fixme : change archive name strategy
         */
        $path = str_replace('current',  date('Y_m_d_H_i'), $this->path);
        file_put_contents($path, $this->data);
        /**
         * @fixme : change createStats place
         */
        $key = date('d/m/Y à h:i');
        $stats = array();
        foreach ($this->data as $k => $data) {
            $stats[$k][$key] = array(
                'date' => $key,
                'count' => sizeof($data),
            );
        }

        $this->saveStats($stats);
        $this->clear();
    }

    /**
     * @param string $key
     * @return array
     */
    public function getStats($key = null)
    {
        $path = str_replace('current', 'statistics', $this->path);
        $stats = array();
        if (file_exists($path)) {
            $stats = json_decode(file_get_contents($path), true);
        }

        if (null !== $key) {
            if (isset($stats[$key])) {
               return $stats[$key];
            }
            return array();
        }

        return $stats;
    }
    /**
     * @return array
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @param Request $request
     * @return $this
     */
    public function setRequest(Request $request)
    {
        $this->request = $request;

        return $this;
    }

    /**
     * @return array
     */
    protected function getStoredData()
    {
        if (file_exists($this->path)) {
            return json_decode(
                file_get_contents($this->path),
                true
            );
        }

        return array();
    }

    /**
     * @param $key
     * @return array
     */
    protected function getStoredDataForKey($key)
    {
        $data = $this->getStoredData();
        if (isset($data[$key])) {
            return $data[$key];
        }

        return array();
    }

    /**
     * @param array $values
     * @param string $key
     * @return $this
     */
    protected function setData(array $values = array(), $key = null)
    {
        if (null == $key) {
            $key = $this->request->getPathInfo();
        }

        if (!empty($values)) { // We don't set empty data
            $this->data[$key] = $values;
        }

        return $this;
    }

    /**
     * @param array $stats
     */
    private function saveStats(array $stats = array())
    {
        $path = str_replace('current', 'statistics', $this->path);
        $currentStats = $this->getStats();
        file_put_contents($path, json_encode(array_merge_recursive ($currentStats, $stats)));

    }
    /**
     * @return string
     */
    public function __toString()
    {
        return ucfirst($this->getName());
    }
}
