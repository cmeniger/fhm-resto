<?php
namespace Fhm\NewsBundle\Controller\Tag;

use Fhm\FhmBundle\Controller\RefFrontController as FhmController;
use Fhm\NewsBundle\Document\NewsTag;
use Fhm\NewsBundle\Form\Type\Front\Tag\CreateType;
use Fhm\NewsBundle\Form\Type\Front\Tag\UpdateType;
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
     * @param string $repository
     * @param string $source
     * @param string $domain
     * @param string $translation
     * @param $document
     * @param string $route
     */
    public function __construct(
        $repository = "FhmNewsBundle:NewsTag",
        $source = "fhm",
        $domain = "FhmNewsBundle",
        $translation = "news.tag",
        $document = NewsTag::class,
        $route = 'news_tag'
    ) {
        self::$repository = $repository;
        self::$source = $source;
        self::$domain = $domain;
        self::$translation = $translation;
        self::$document = new $document();
        self::$class = get_class(self::$document);
        self::$route = $route;
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