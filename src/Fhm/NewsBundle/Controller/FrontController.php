<?php
namespace Fhm\NewsBundle\Controller;

use Fhm\FhmBundle\Controller\RefFrontController as FhmController;
use Fhm\NewsBundle\Document\News;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * @Route("/news")
 * ---------------------------------
 * Class FrontController
 * @package Fhm\NewsBundle\Controller
 */
class FrontController extends FhmController
{
    /**
     * FrontController constructor.
     */
    public function __construct()
    {
        self::$repository = "FhmNewsBundle:News";
        self::$source = "fhm";
        self::$domain = "FhmNewsBundle";
        self::$translation = "News";
        self::$class = News::class;
        self::$route = "news";
    }

    /**
     * @Route
     * (
     *      path="/",
     *      name="fhm_news"
     * )
     * @Template("::FhmNews/Front/index.html.twig")
     */
    public function indexAction()
    {
        return parent::indexAction();
    }

    /**
     * @Route
     * (
     *      path="/detail/{id}",
     *      name="fhm_news_detail",
     *      requirements={"id"=".+"}
     * )
     * @Template("::FhmNews/Front/detail.html.twig")
     */
    public function detailAction($id)
    {
        return parent::detailAction($id);
    }

    /**
     * @Route
     * (
     *      path="/{id}",
     *      name="fhm_news_lite",
     *      requirements={"id"=".+"}
     * )
     * @Template("::FhmNews/Front/detail.html.twig")
     */
    public function liteAction($id)
    {
        return $this->detailAction($id);
    }
}