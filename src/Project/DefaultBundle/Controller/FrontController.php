<?php
namespace Project\DefaultBundle\Controller;

use Fhm\FhmBundle\Controller\RefFrontController;
use Fhm\FhmBundle\Services\Tools;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Session\Session;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * @Route("/", service="project_default_controller_front")
 */
class FrontController extends RefFrontController
{
    /**
     * FrontController constructor.
     * @param $fhm_tools
     */

    public function __construct(Tools $fhm_tools)
    {
        $this->setFhmTools($fhm_tools);
    }

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
        return $this->fhm_tools->getContainer()->get($this->getParameter("fhm_fhm")["grouping"])->loadGrouping();
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
        $template = ($this->get('templating')->exists('::ProjectDefault/Template/' . $name . '.html.twig')) ?
            '::ProjectDefault/Template/' . $name . '.html.twig' :
            '::ProjectDefault/Template/default.html.twig';
        $date     = new \DateTime();
        $response->setLastModified($date);

        return $this->render(
            $template,
            array(
                'instance' => $this->get('fhm_tools')->instanceData()
            ),
            $response
        );
    }
}