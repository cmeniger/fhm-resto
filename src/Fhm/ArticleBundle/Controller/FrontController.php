<?php
namespace Fhm\ArticleBundle\Controller;

use Fhm\FhmBundle\Controller\RefFrontController as FhmController;
use Fhm\ArticleBundle\Document\Article;
use Fhm\FhmBundle\Services\Tools;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * @Route("/article")
 * -------------------------------------------
 * Class FrontController
 * @package Fhm\ArticleBundle\Controller
 */
class FrontController extends FhmController
{
    /**
     * FrontController constructor.
     * @param string $repository
     * @param string $source
     * @param string $domaine
     * @param string $translation
     * @param string $route
     */
    public function __construct(
        $repository = "FhmArticleBundle:Article",
        $source = "fhm",
        $domaine = "FhmArticleBundle",
        $translation = "article",
        $route = 'article'
    ) {
        self::$repository = $repository;
        self::$source = $source;
        self::$domain = $domaine;
        self::$translation = $translation;
        self::$document = new Article();
        self::$class = get_class(self::$document);
        self::$route = $route;
    }

    /**
     * @Route
     * (
     *      path="/",
     *      name="fhm_article"
     * )
     * @Template("::FhmArticle/Front/index.html.twig")
     */
    public function indexAction()
    {
        return parent::indexAction();
    }

    /**
     * @Route
     * (
     *      path="/detail/{id}",
     *      name="fhm_article_detail",
     *      requirements={"id"=".+"}
     * )
     * @Template("::FhmArticle/Front/detail.html.twig")
     */
    public function detailAction($id)
    {
        return parent::detailAction($id);
    }

    /**
     * @Route
     * (
     *      path="/{id}",
     *      name="fhm_article_lite",
     *      requirements={"id"=".+"}
     * )
     * @Template("::FhmArticle/Front/detail.html.twig")
     */
    public function liteAction($id)
    {
        return $this->detailAction($id);
    }
}