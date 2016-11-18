<?php
namespace Project\DefaultBundle\Controller;

use Fhm\FhmBundle\Controller\FhmController;
use Symfony\Component\HttpFoundation\Session\Session;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * @Route("/")
 */
class FrontController extends FhmController
{
    /**
     * @Route
     * (
     *      path="/",
     *      name="project_home"
     * )
     * @Template("::ProjectDefault/Front/home.html.twig")
     */
    public function homeAction()
    {
        return $this->get($this->getParameter("grouping", "fhm_fhm"))->loadGrouping();
    }

    /**
     * @Route
     * (
     *      path="/template/{name}",
     *      name="project_template"
     * )
     */
    public function templateAction($name)
    {
        // Response HTTP Cache
        $response = $this->get('fhm_cache')->getResponseCache();
        $template = ($this->container->get('templating')->exists('::ProjectDefault/Template/' . $name . '.html.twig')) ? '::ProjectDefault/Template/' . $name . '.html.twig' : '::ProjectDefault/Template/default.html.twig';
        $date     = new \DateTime();
        $response->setLastModified($date);

        return $this->render($template, array('instance' => $this->instanceData()), $response);
    }
}