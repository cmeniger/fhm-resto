<?php
namespace Fhm\ArticleBundle\Controller;

use Fhm\ArticleBundle\Form\Type\Admin\CreateType;
use Fhm\ArticleBundle\Form\Type\Admin\UpdateType;
use Fhm\FhmBundle\Controller\RefFrontController as FhmController;
use Fhm\FhmBundle\Form\Handler\Admin\CreateHandler;
use Fhm\FhmBundle\Form\Handler\Admin\UpdateHandler;
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
     */
    public function __construct()
    {
        self::$repository  = "FhmArticleBundle:Article";
        self::$domain      = "FhmArticleBundle";
        self::$translation = "article";
        self::$route       = "article";
        self::$source      = "fhm";
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