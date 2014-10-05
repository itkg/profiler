<?php

namespace Itkg\Profiler\Storage;

use Itkg\Profiler\DataCollector\DataCollectorInterface;
use Symfony\Component\HttpFoundation\Session\Attribute\NamespacedAttributeBag;
use Symfony\Component\HttpFoundation\Session\Session;

class SessionStorage implements StorageInterface
{
    /**
     * @var string
     */
    private $key;
    /**
     * @var string
     */
    private $current = 'current';
    /**
     * @var string
     */
    private $statsName = 'statistics';

    /**
     * @var Session
     */
    private $session;

    /**
     * @param \Symfony\Component\HttpFoundation\Session\Session $session
     * @param string $key
     */
    public function __construct(Session $session, $key = 'profiler')
    {
        $this->session = $session;
        $bag = new NamespacedAttributeBag($key); // Profiler defines its own bag
        $bag->setName($key);
        $this->session->registerBag($bag);
        $this->key = $key;
    }

    /**
     * @param DataCollectorInterface $collector
     * @param string $current
     */
    public function load(DataCollectorInterface $collector, $current = 'current')
    {
        $this->current = $current;
        $data = $this->session->getBag($this->key)
            ->get(
                sprintf(
                    '%s/%s',
                    $collector->getName(),
                    $this->current
                ),
                array()
            );

        $collector->setData($data);
        $this->loadStats($collector);
    }

    /**
     * @param DataCollectorInterface $collector
     */
    public function save(DataCollectorInterface $collector)
    {
        $this->session->getBag($this->key)->set(
            sprintf(
                '%s/%s',
                $collector->getName(),
                $this->current
            ),
            $collector->getData()
        );
    }


    /**
     * @param DataCollectorInterface $collector
     */
    public function archive(DataCollectorInterface $collector)
    {
        $this->session->getBag($this->key)->set(
            sprintf(
                '%s/%s',
                $collector->getName(),
                date('d_m_Y_h_i')
            ),
            $collector->getData()
        );
    }

    /**
     * @param DataCollectorInterface $collector
     */
    public function clear(DataCollectorInterface $collector)
    {
        $this->session->getBag($this->key)->remove(
            sprintf(
                '%s/%s',
                $collector->getName(),
                $this->current
            )
        );
        $collector->setData(array());
    }

    /**
     * Save collector statistics
     * @param DataCollectorInterface $collector
     * @return void
     */
    public function saveStats(DataCollectorInterface $collector)
    {
        $this->session->getBag($this->key)->set(
            sprintf(
                '%s/%s',
                $collector->getName(),
                $this->statsName
            ),
            $collector->getStats()
        );
    }

    /**
     * Get collector archives
     *
     * @param DataCollectorInterface $collector
     * @return array
     */
    public function getArchives(DataCollectorInterface $collector)
    {
        $storedKeys = array_keys(
            $this->session->getBag($this->key)->get(
                sprintf(
                    '%s',
                    $collector->getName()
                ),
                array()
            )
        );

        $archives = array();

        foreach ($storedKeys as $i => $storeKey) {
            if ($storeKey != 'statistics') {

                $key = ($storeKey == $this->current) ? 'current' : $i;
                $archives[$key] = $storeKey;

            }
        }

        return $archives;
    }

    /**
     * @param DataCollectorInterface $collector
     */
    private function loadStats(DataCollectorInterface $collector)
    {
        $data = $this->session->getBag($this->key)->get(sprintf('%s/%s', $collector->getName(), $this->statsName), array());

        $collector->setStats($data);
    }
}
