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