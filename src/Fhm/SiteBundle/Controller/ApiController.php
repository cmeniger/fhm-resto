<?php
namespace Fhm\SiteBundle\Controller;

use Fhm\FhmBundle\Controller\RefApiController as FhmController;
use Fhm\SiteBundle\Document\Site;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * @Route("/api/site")
 * -------------------------------------
 * Class ApiController
 * @package Fhm\SiteBundle\Controller
 */
class ApiController extends FhmController
{
    /**
     * ApiController constructor.
     */
    public function __construct()
    {
        self::$repository = "FhmSiteBundle:Site";
        self::$source = "fhm";
        self::$domain = "FhmSiteBundle";
        self::$translation = "site";
        self::$document = new Site();
        self::$class = get_class(self::$document);
        self::$route = 'site';
    }

    /**
     * @Route
     * (
     *      path="/",
     *      name="fhm_api_site"
     * )
     * @Template("::FhmSite/Api/index.html.twig")
     */
    public function indexAction()
    {
        return parent::indexAction();
    }

    /**
     * @Route
     * (
     *      path="/autocomplete/",
     *      name="fhm_api_site_autocomplete"
     * )
     * @Template("::FhmSite/Api/autocomplete.html.twig")
     */
    public function autocompleteAction(Request $request)
    {
        return parent::autocompleteAction($request);
    }
}