<?php
namespace Fhm\NotificationBundle\Controller;

use Fhm\FhmBundle\Controller\RefFrontController as FhmController;
use Fhm\NotificationBundle\Document\Notification;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * @Route("/notification")
 */
class FrontController extends FhmController
{
    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct('Fhm', 'Notification', 'notification');
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
     *      path="/create",
     *      name="fhm_notification_create"
     * )
     * @Template("::FhmNotification/Front/create.html.twig")
     */
    public function createAction(Request $request)
    {
        // For activate this route, delete next line
        throw $this->createNotFoundException($this->get('translator')->trans('fhm.error.route', array(), 'FhmFhmBundle'));

        return parent::createAction($request);
    }

    /**
    * @Route
    * (
    *      path="/duplicate/{id}",
    *      name="fhm_notification_duplicate",
    *      requirements={"id"="[a-z0-9]*"}
    * )
    * @Template("::FhmNotification/Front/create.html.twig")
    */
    public function duplicateAction(Request $request, $id)
    {
        // For activate this route, delete next line
        throw $this->createNotFoundException($this->get('translator')->trans('fhm.error.route', array(), 'FhmFhmBundle'));

        return parent::duplicateAction($request, $id);
    }

    /**
    * @Route
    * (
    *      path="/update/{id}",
    *      name="fhm_notification_update",
    *      requirements={"id"="[a-z0-9]*"}
    * )
    * @Template("::FhmNotification/Front/update.html.twig")
    */
    public function updateAction(Request $request, $id)
    {
        // For activate this route, delete next line
        throw $this->createNotFoundException($this->get('translator')->trans('fhm.error.route', array(), 'FhmFhmBundle'));

        return parent::updateAction($request, $id);
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
    *      path="/delete/{id}",
    *      name="fhm_notification_delete",
    *      requirements={"id"="[a-z0-9]*"}
    * )
    */
    public function deleteAction($id)
    {
        // For activate this route, delete next line
        throw $this->createNotFoundException($this->get('translator')->trans('fhm.error.route', array(), 'FhmFhmBundle'));

        return parent::deleteAction($id);
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