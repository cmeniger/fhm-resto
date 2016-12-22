<?php
namespace Fhm\SiteBundle\Controller;

use Fhm\FhmBundle\Controller\RefFrontController as FhmController;
use Fhm\SiteBundle\Document\Site;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * @Route("/site")
 * ----------------------------------
 * Class FrontController
 * @package Fhm\SiteBundle\Controller
 */
class FrontController extends FhmController
{
    /**
     * FrontController constructor.
     */
    public function __construct()
    {
        self::$repository = "FhmSiteBundle:Site";
        self::$source = "fhm";
        self::$domain = "FhmSiteBundle";
        self::$translation = "site";
        self::$class = Site::class;
        self::$route = "site";
    }

    /**
     * @Route
     * (
     *      path="/detail/{id}",
     *      name="fhm_site_detail",
     *      requirements={"id"=".+"}
     * )
     * @Template("::FhmSite/Front/detail.html.twig")
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
     * @Template("::FhmSite/Front/detail.html.twig")
     */
    public function liteAction($id)
    {
        return $this->detailAction($id);
    }
}