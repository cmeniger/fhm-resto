<?php
namespace Fhm\NewsBundle\Controller\Group;

use Fhm\FhmBundle\Controller\RefApiController as FhmController;
use Fhm\NewsBundle\Document\NewsGroup;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * @Route("/api/news/group")
 * -----------------------------------------
 * Class ApiController
 * @package Fhm\NewsBundle\Controller\Group
 */
class ApiController extends FhmController
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
        $repository = "FhmNewsBundle:NewsGroup",
        $source = "fhm",
        $domain = "FhmNewsBundle",
        $translation = "news.group",
        $document = NewsGroup::class,
        $route = 'news_group'
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
     *      name="fhm_api_news_group"
     * )
     * @Template("::FhmNews/Api/Group/index.html.twig")
     */
    public function indexAction()
    {
        return parent::indexAction();
    }

    /**
     * @Route
     * (
     *      path="/autocomplete/",
     *      name="fhm_api_news_group_autocomplete"
     * )
     * @Template("::FhmNews/Api/Group/autocomplete.html.twig")
     */
    public function autocompleteAction(Request $request)
    {
        return parent::autocompleteAction($request);
    }
}