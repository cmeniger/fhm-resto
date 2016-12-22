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
     * AdminController constructor.
     */
    public function __construct()
    {
        self::$repository = "FhmNewsBundle:NewsGroup";
        self::$source = "fhm";
        self::$domain = "FhmNewsBundle";
        self::$translation = "News.group";
        self::$class = NewsGroup::class;
        self::$route = "news_group";
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