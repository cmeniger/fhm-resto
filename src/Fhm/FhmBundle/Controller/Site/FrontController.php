<?php
namespace Fhm\FhmBundle\Controller\Site;

use Fhm\FhmBundle\Controller\RefFrontController as FhmController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * @Route("/site")
 * ----------------------------------
 * Class FrontController
 * @package Fhm\SiteBundle\Controller\Site
 */
class FrontController extends FhmController
{
    /**
     * FrontController constructor.
     */
    public function __construct()
    {
        self::$repository = "FhmFhmBundle:Site";
        self::$source = "fhm";
        self::$domain = "FhmFhmSite";
        self::$translation = "site";
        self::$route = "site";
    }

    /**
     * @Route
     * (
     *      path="/detail/{id}",
     *      name="fhm_site_detail",
     *      requirements={"id"=".+"}
     * )
     * @Template("::FhmFhm/Site/Front/detail.html.twig")
     */
    public function detailAction($id)
    {
        parent::detailAction($id);

        return $this->redirect($this->getUrl('project_home'));
    }

    /**
     * @Route
     * (
     *      path="/{id}",
     *      name="fhm_site_lite",
     *      requirements={"id"=".+"}
     * )
     * @Template("::FhmFhm/Site/Front/detail.html.twig")
     */
    public function liteAction($id)
    {
        return $this->detailAction($id);
    }
}