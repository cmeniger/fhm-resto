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
     */
    public function __construct()
    {
        self::$repository = "FhmEventBundle:Event";
        self::$source = "fhm";
        self::$domain = "FhmEventBundle";
        self::$translation = "event";
        self::$class = Event::class;
        self::$route = "event";
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