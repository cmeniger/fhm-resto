<?php
namespace Fhm\ArticleBundle\Controller;

use Fhm\ArticleBundle\Form\Type\Admin\CreateType;
use Fhm\ArticleBundle\Form\Type\Admin\UpdateType;
use Fhm\FhmBundle\Controller\RefApiController as FhmController;
use Fhm\ArticleBundle\Document\Article;
use Fhm\FhmBundle\Form\Handler\Admin\CreateHandler;
use Fhm\FhmBundle\Form\Handler\Admin\UpdateHandler;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * @Route("/api/article")
 * --------------------------------------
 * Class ApiController
 * @package Fhm\ArticleBundle\Controller
 */
class ApiController extends FhmController
{
    /**
     * ApiController constructor.
     */
    public function __construct()
    {
        self::$repository  = "FhmArticleBundle:Article";
        self::$domain      = "FhmArticleBundle";
        self::$translation = "article";
        self::$route       = "article";
        self::$source      = "fhm";

        self::$class = Article::class;
    }

    /**
     * @Route
     * (
     *      path="/",
     *      name="fhm_api_article"
     * )
     * @Template("::FhmArticle/Api/index.html.twig")
     */
    public function indexAction()
    {
        return parent::indexAction();
    }

    /**
     * @Route
     * (
     *      path="/autocomplete/",
     *      name="fhm_api_article_autocomplete"
     * )
     * @Template("::FhmArticle/Api/autocomplete.html.twig")
     */
    public function autocompleteAction(Request $request)
    {
        return parent::autocompleteAction($request);
    }

    /**
     * @Route
     * (
     *      path="/historic/",
     *      name="fhm_api_article_historic"
     * )
     * @Template("::FhmArticle/Api/historic.html.twig")
     */
    public function historicAction(Request $request)
    {
        return parent::historicAction($request);
    }

    /**
     * @Route
     * (
     *      path="/historic/copy/{id}",
     *      name="fhm_api_article_historic_copy",
     *      requirements={"id"="[a-z0-9]*"}
     * )
     */
    public function historicCopyAction(Request $request, $id)
    {
        return parent::historicCopyAction($request, $id);
    }

    /**
     * @Route
     * (
     *      path="/historic/delete/{id}",
     *      name="fhm_api_article_historic_delete",
     *      requirements={"id"="[a-z0-9]*"}
     * )
     */
    public function historicDeleteAction(Request $request, $id)
    {
        return parent::historicDeleteAction($request, $id);
    }

    /**
     * @Route
     * (
     *      path="/historic/restore/{id}",
     *      name="fhm_api_article_historic_restore",
     *      requirements={"id"="[a-z0-9]*"}
     * )
     */
    public function historicRestaureAction(Request $request, $id)
    {
        return parent::historicRestaureAction($request, $id);
    }
}