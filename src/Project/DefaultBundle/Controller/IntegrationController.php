<?php

namespace Project\DefaultBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Session\Session;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * @Route("/integration")
 */
class IntegrationController extends Controller
{
    private $tools;

    /**
     *
     * @param \Fhm\FhmBundle\Services\Tools $tools
     */
    public function __construct(\Fhm\FhmBundle\Services\Tools $tools)
    {
        $this->tools = $tools;
    }

    /**
     * @Route
     * (
     *      path="/{name}",
     *      name="project_integration"
     * )
     */
    public function integrationAction($name)
    {
        $template = ($this->get('templating')->exists('::ProjectDefault/Integration/' . $name . '.html.twig')) ?
            '::ProjectDefault/Integration/' . $name . '.html.twig' :
            '::ProjectDefault/Integration/default.html.twig';

        return $this->render($template, array(
        ));
    }
}