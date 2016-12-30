<?php
namespace Fhm\FhmBundle\Controller\Site;

use Fhm\FhmBundle\Controller\RefApiController as FhmController;
use Fhm\FhmBundle\Document\Site;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * @Route("/api/site")
 * -------------------------------------
 * Class ApiController
 * @package Fhm\FhmBundle\Controller\Site
 */
class ApiController extends FhmController
{
    /**
     * ApiController constructor.
     */
    public function __construct()
    {
        self::$repository = "FhmFhmBundle:Site";
        self::$source = "fhm";
        self::$domain = "FhmFhmSite";
        self::$translation = "site";
        self::$class = Site::class;
        self::$route = "site";
    }

    /**
     * @Route
     * (
     *      path="/",
     *      name="fhm_api_site"
     * )
     * @Template("::FhmFhm/Site/Api/index.html.twig")
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
     * @Template("::FhmFhm/Site/Api/autocomplete.html.twig")
     */
    public function autocompleteAction(Request $request)
    {
        return parent::autocompleteAction($request);
    }
}