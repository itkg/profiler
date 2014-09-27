<?php

namespace Itkg\Profiler\Controller;


use Itkg\Core\ServiceContainer;
use Itkg\Profiler\Template\Finder;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Controller
 *
 * Class ProfilerController
 * @package Itkg\Profiler\Controller
 */
class ProfilerController
{
    /**
     * @var ServiceContainer
     */
    protected $container;

    /**
     * @var Request $request
     */
    protected $request;

    /**
     * @return Response
     */
    public function indexAction()
    {
        $profiler = $this->container['profiler']['profiler'];

        return $this->render(
            $this->getTemplateFinder()->getBasePath($profiler),
            array(
                'profiler' => $profiler
            )
        );
    }

    /**
     * Render collector template
     *
     * @return Response
     */
    public function collectorsAction()
    {

        $key = $this->request->attributes->get('route_params')['params']['collector'];

        // Create collector Factory ?
        $collector = $this->container['profiler']['collector.' . $key];

        if ($this->request->query->get('action') == 'clear') {
            $collector->clear();
        } elseif($this->request->query->get('action') == 'archive') {
            $collector->archive();
        }

        return $this->render(
            $this->getTemplateFinder()->getCollectorPath($collector),
            array(
                'collector' => $collector
            )
        );
    }

    /**
     * @param ServiceContainer $container
     * @return $this
     */
    public function setContainer(ServiceContainer $container)
    {
        $this->container = $container;

        return $this;
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
     * Render a template and return content
     *
     * @param $template
     * @param array $data
     * @return string
     * @throws \RuntimeException
     */
    protected function render($template, array $data = array())
    {
        if (!file_exists($template)) {
            throw new \RuntimeException(sprintf('Template %s does not exist', $template));
        }
        //Extracts vars to current view scope
        extract($data);

        //Starts output buffering
        ob_start();

        include $template;
        $buffer = ob_get_contents();
        @ob_end_clean();

        //Returns output buffer
        return new Response($buffer);
    }

    /**
     * @return Finder
     */
    private function getTemplateFinder()
    {
        return $this->container['profiler']['template_finder'];
    }
}
