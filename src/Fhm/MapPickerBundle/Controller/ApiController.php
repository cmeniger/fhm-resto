<?php
namespace Fhm\MapPickerBundle\Controller;

use Fhm\FhmBundle\Controller\RefApiController as FhmController;
use Fhm\MapPickerBundle\Document\Map;
use Fhm\MapPickerBundle\Document\MapPicker;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * @Route("/api/mappicker")
 * Class ApiController
 * @package Fhm\MapPickerBundle\Controller
 */
class ApiController extends FhmController
{
    /**
     * ApiController constructor.
     */
    public function __construct()
    {
        self::$repository = "FhmMapPickerBundle:MapPicker";
        self::$source = "fhm";
        self::$domain = "FhmMapPickerBundle";
        self::$translation = "mappicker";
        self::$class = MapPicker::class;
        self::$route = "mappicker";
    }

    /**
     * @Route
     * (
     *      path="/",
     *      name="fhm_api_map"
     * )
     * @Template("::FhmMapPicker/Api/index.html.twig")
     */
    public function indexAction()
    {
        return parent::indexAction();
    }

    /**
     * @Route
     * (
     *      path="/autocomplete/",
     *      name="fhm_api_map_autocomplete"
     * )
     * @Template("::FhmMapPicker/Api/autocomplete.html.twig")
     */
    public function autocompleteAction(Request $request)
    {
        return parent::autocompleteAction($request);
    }
}