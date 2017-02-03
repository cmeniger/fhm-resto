<?php
namespace Fhm\NotificationBundle\Controller;

use Fhm\FhmBundle\Controller\RefFrontController as FhmController;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * @Route("/notification")
 * -----------------------------------------
 * Class FrontController
 * @package Fhm\NotificationBundle\Controller
 */
class FrontController extends FhmController
{
    /**
     * FrontController constructor.
     */
    public function __construct()
    {
        self::$repository = "FhmNotificationBundle:Notification";
        self::$source = "fhm";
        self::$domain = "FhmNotificationBundle";
        self::$translation = "notification";
        self::$route = "notification";
    }

    /**
     * @Route
     * (
     *      path="/",
     *      name="fhm_notification"
     * )
     * @Template("::FhmNotification/Front/index.html.twig")
     */
    public function indexAction()
    {
        return parent::indexAction();
    }

    /**
     * @Route
     * (
     *      path="/detail/{id}",
     *      name="fhm_notification_detail",
     *      requirements={"id"=".+"}
     * )
     * @Template("::FhmNotification/Front/detail.html.twig")
     */
    public function detailAction($id)
    {
        return parent::detailAction($id);
    }

    /**
     * @Route
     * (
     *      path="/{id}",
     *      name="fhm_notification_lite",
     *      requirements={"id"=".+"}
     * )
     * @Template("::FhmNotification/Front/detail.html.twig")
     */
    public function liteAction($id)
    {
        return $this->detailAction($id);
    }
}