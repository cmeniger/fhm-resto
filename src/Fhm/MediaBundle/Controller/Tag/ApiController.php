<?php
namespace Fhm\MediaBundle\Controller\Tag;

use Fhm\FhmBundle\Controller\RefApiController as FhmController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * @Route("/api/mediatag")
 * ----------------------------------------
 * Class ApiController
 * @package Fhm\MediaBundle\Controller\Tag
 */
class ApiController extends FhmController
{
    /**
     * AdminController constructor.
     */
    public function __construct()
    {
        self::$repository = "FhmMediaBundle:MediaTag";
        self::$source = "fhm";
        self::$domain = "FhmMediaBundle";
        self::$translation = "media.tag";
        self::$route = "media_tag";
    }

    /**
     * @Route
     * (
     *      path="/",
     *      name="fhm_api_media_tag"
     * )
     * @Template("::FhmMedia/Api/Tag/index.html.twig")
     */
    public function indexAction()
    {
        return parent::indexAction();
    }

    /**
     * @Route
     * (
     *      path="/autocomplete/",
     *      name="fhm_api_media_tag_autocomplete"
     * )
     * @Template("::FhmMedia/Api/Tag/autocomplete.html.twig")
     */
    public function autocompleteAction(Request $request)
    {
        return parent::autocompleteAction($request);
    }
}