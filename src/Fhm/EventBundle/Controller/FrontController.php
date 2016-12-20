<?php
namespace Fhm\EventBundle\Controller;

use Fhm\FhmBundle\Controller\RefFrontController as FhmController;
use Fhm\EventBundle\Document\Event;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * @Route("/event")
 * -----------------------------------
 * Class FrontController
 * @package Fhm\EventBundle\Controller
 */
class FrontController extends FhmController
{
    /**
     * FrontController constructor.
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
        $translation = "event",
        $document = Event::class,
        $route = 'event'
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
     *      name="fhm_event"
     * )
     * @Template("::FhmEvent/Front/index.html.twig")
     */
    public function indexAction()
    {
        return parent::indexAction();
    }

    /**
    * @Route
    * (
    *      path="/detail/{id}",
    *      name="fhm_event_detail",
    *      requirements={"id"=".+"}
    * )
    * @Template("::FhmEvent/Front/detail.html.twig")
    */
    public function detailAction($id)
    {
        return parent::detailAction($id);
    }

    /**
    * @Route
    * (
    *      path="/{id}",
    *      name="fhm_event_lite",
    *      requirements={"id"=".+"}
    * )
    * @Template("::FhmEvent/Front/detail.html.twig")
    */
    public function liteAction($id)
    {
        return $this->detailAction($id);
    }
}