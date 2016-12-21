<?php
namespace Fhm\NewsBundle\Controller\Tag;

use Fhm\FhmBundle\Controller\RefApiController as FhmController;
use Fhm\NewsBundle\Document\NewsTag;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * @Route("/api/newstag")
 * --------------------------------------
 * Class ApiController
 * @package Fhm\NewsBundle\Controller\Tag
 */
class ApiController extends FhmController
{
    /**
     * ApiController constructor.
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
     *      name="fhm_api_news_tag"
     * )
     * @Template("::FhmNews/Api/Tag/index.html.twig")
     */
    public function indexAction()
    {
        return parent::indexAction();
    }

    /**
     * @Route
     * (
     *      path="/autocomplete",
     *      name="fhm_api_news_tag_autocomplete"
     * )
     * @Template("::FhmNews/Api/Tag/autocomplete.html.twig")
     */
    public function autocompleteAction(Request $request)
    {
        return parent::autocompleteAction($request);
    }
}