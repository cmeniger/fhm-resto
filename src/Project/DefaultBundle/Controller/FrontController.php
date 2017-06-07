<?php

namespace Project\DefaultBundle\Controller;

use Fhm\FhmBundle\Controller\RefFrontController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * @Route("/")
 */
class FrontController extends RefFrontController
{
    /**
     * FrontController constructor.
     */
    public function __construct()
    {
        self::$source      = "project";
        self::$domain      = "ProjectDefaultBundle";
        self::$translation = "project";
        self::$route       = 'project';
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
        $site = $this->get('fhm_tools')->dmRepository('FhmFhmBundle:Site')->getDefault();
        if($site)
        {
            return array(
                'site' => $site,
                'menu' => $site->getMenu(),
            );
        }

        return $this->redirectToRoute('fhm_admin_site_create');
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
        $response = new Response();
        $template = ($this->get('templating')->exists(
            '::ProjectDefault/Template/' . $name . '.html.twig'
        )) ? '::ProjectDefault/Template/' . $name . '.html.twig' : '::ProjectDefault/Template/default.html.twig';

        return $this->render(
            $template,
            array(),
            $response
        );
    }
}