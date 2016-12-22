<?php
namespace Fhm\EventBundle\Controller\Group;

use Fhm\EventBundle\Document\EventGroup;
use Fhm\FhmBundle\Controller\RefApiController as FhmController;
use Fhm\EventBundle\Document\Event;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * @Route("/api/event/group")
 * -----------------------------------------
 * Class ApiController
 * @package Fhm\EventBundle\Controller\Group
 */
class ApiController extends FhmController
{
    /**
     * FrontController constructor.
     */
    public function __construct()
    {
        self::$repository = "FhmEventBundle:EventGroup";
        self::$source = "fhm";
        self::$domain = "FhmEventBundle";
        self::$translation = "event.group";
        self::$class = EventGroup::class;
        self::$route = "event_group";
    }

    /**
     * @Route
     * (
     *      path="/",
     *      name="fhm_api_event_group"
     * )
     * @Template("::FhmEvent/Api/Group/index.html.twig")
     */
    public function indexAction()
    {
        return parent::indexAction();
    }

    /**
     * @Route
     * (
     *      path="/autocomplete/",
     *      name="fhm_api_event_group_autocomplete"
     * )
     * @Template("::FhmEvent/Api/Group/autocomplete.html.twig")
     */
    public function autocompleteAction(Request $request)
    {
        return parent::autocompleteAction($request);
    }
}