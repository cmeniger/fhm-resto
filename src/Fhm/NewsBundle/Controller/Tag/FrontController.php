<?php
namespace Fhm\NewsBundle\Controller\Tag;

use Fhm\FhmBundle\Controller\RefFrontController as FhmController;
use Fhm\NewsBundle\Document\NewsTag;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * @Route("/newstag")
 * ---------------------------------------
 * Class FrontController
 * @package Fhm\NewsBundle\Controller\Tag
 */
class FrontController extends FhmController
{
    /**
     * FrontController constructor.
     */
    public function __construct()
    {
        self::$repository = "FhmNewsBundle:NewsTag";
        self::$source = "fhm";
        self::$domain = "FhmNewsBundle";
        self::$translation = "News.tag";
        self::$class = NewsTag::class;
        self::$route = "news_tag";
    }

    /**
     * @Route
     * (
     *      path="/",
     *      name="fhm_news_tag"
     * )
     * @Template("::FhmNews/Front/Tag/index.html.twig")
     */
    public function indexAction()
    {
        return parent::indexAction();
    }

    /**
     * @Route
     * (
     *      path="/detail/{id}",
     *      name="fhm_news_tag_detail",
     *      requirements={"id"=".+"}
     * )
     * @Template("::FhmNews/Front/Tag/detail.html.twig")
     */
    public function detailAction($id)
    {
        return parent::detailAction($id);
    }

    /**
     * @Route
     * (
     *      path="/{id}",
     *      name="fhm_news_tag_lite",
     *      requirements={"id"=".+"}
     * )
     * @Template("::FhmNews/Front/Tag/detail.html.twig")
     */
    public function liteAction($id)
    {
        return $this->detailAction($id);
    }
}