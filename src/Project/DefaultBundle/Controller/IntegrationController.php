<?php

namespace Project\DefaultBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * @Route("/integration")
 */
class IntegrationController extends Controller
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
        $template = ($this->get('templating')->exists('::ProjectDefault/Integration/' . $name . '.html.twig')) ?
            '::ProjectDefault/Integration/' . $name . '.html.twig' :
            '::ProjectDefault/Integration/default.html.twig';

        return $this->render($template);
    }
}