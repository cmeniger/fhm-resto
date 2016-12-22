<?php
namespace Fhm\ArticleBundle\Controller;

use Fhm\FhmBundle\Controller\RefApiController as FhmController;
use Fhm\ArticleBundle\Document\Article;
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
     * @param string $repository
     * @param string $source
     * @param string $domain
     * @param string $translation
     * @param string $document
     * @param string $route
     */
    public function __construct(
        $repository = "FhmArticleBundle:Article",
        $source = "fhm",
        $domain = "FhmArticleBundle",
        $translation = "article",
        $document = Article::class,
        $route = 'article'
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