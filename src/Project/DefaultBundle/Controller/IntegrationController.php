<?php

namespace Project\DefaultBundle\Controller;

use Fhm\FhmBundle\Controller\FhmController;
use Symfony\Component\HttpFoundation\Session\Session;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * @Route("/integration")
 */
class IntegrationController extends FhmController
{
    /**
     * @Route
     * (
     *      path="/{name}",
     *      name="project_integration"
     * )
     */
    public function integrationAction($name)
    {
        $template = ($this->container->get('templating')->exists('::ProjectDefault/Integration/' . $name . '.html.twig')) ? '::ProjectDefault/Integration/' . $name . '.html.twig' : '::ProjectDefault/Integration/default.html.twig';

        return $this->render($template, array(
            'instance' => $this->instanceData()
        ));
    }
}