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
        $this->path = sprintf('%s/%s/%s', BASE_DIR, $profilerPath, $this->getName());

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
     * @return string
     */
    public function __toString()
    {
        return ucfirst($this->getName());
    }
}
