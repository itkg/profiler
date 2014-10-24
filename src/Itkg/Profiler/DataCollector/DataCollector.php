<?php

namespace Itkg\Profiler\DataCollector;

use Symfony\Component\HttpFoundation\Request;

/**
 * @author Pascal DENIS <pascal.denis@businessdecision.com>
 */
abstract class DataCollector implements DataCollectorInterface
{
    /**
     * @var array
     */
    protected $data = array();
    /**
     * @var array
     */
    protected $stats = array();
    /**
     * @var Request
     */
    private $request;

    /**
     * @param string $key
     * @return array
     */
    public function getStatsForKey($key)
    {
        if (isset($this->stats[$key])) {
            return $this->stats[$key];
        }
        return array();
    }

    /**
     * @return array
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @param array $data
     * @return $this
     */
    public function setData(array $data = array())
    {
        $this->data = $data;

        return $this;
    }

    /**
     * @return array
     */
    public function getStats()
    {
        $this->createStats();

        return $this->stats;
    }

    /**
     * @param array $stats
     * @return $this
     */
    public function setStats(array $stats = array())
    {
        $this->stats = $stats;

        return $this;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return ucfirst($this->getName());
    }

    /**
     * Create stats from current set of data
     */
    protected function createStats()
    {
        $key = date('d/m/Y à h:i');
        foreach ($this->data as $k => $data) {
            $this->stats[$k][$key] = array(
                'date' => $key,
                'count' => sizeof($data),
            );
        }
    }

    /**
     * @param string $key
     * @return array
     */
    protected function getDataForKey($key)
    {
        if (isset($this->data[$key])) {
            return $this->data[$key];
        }

        return array();
    }

    /**
     * @param array $values
     * @param string $key
     */
    protected function addData(array $values = array(), $key = null)
    {
        if (null === $key) {
            $key = $this->getRequest()->getPathInfo();
        }

        if (!empty($values)) { // We don't set empty data
            $this->data[$key] = $values;
        }
    }

    /**
     * @return Request
     */
    public function getRequest()
    {
        if (null === $this->request) {
            $this->request = Request::createFromGlobals();
        }

        return $this->request;
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
     * No start action by default
     *
     * @return void
     */
    public function start()
    {

    }

}
