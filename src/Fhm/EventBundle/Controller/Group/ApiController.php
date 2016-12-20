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
     * ApiController constructor.

     * @param string $repository
     * @param string $source
     * @param string $domain
     * @param string $translation
     * @param string $document
     * @param string $route
     */
    public function __construct(
        $repository = "FhmEventBundle:Event",
        $source = "fhm",
        $domain = "FhmEventBundle",
        $translation = "event.group",
        $document = EventGroup::class,
        $route = 'event_group'
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