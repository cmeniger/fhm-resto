<?php
namespace Fhm\EventBundle\Controller;

use Fhm\FhmBundle\Controller\RefFrontController as FhmController;
use Fhm\EventBundle\Document\Event;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * @Route("/event", service="fhm_event_controller_front")
 */
class FrontController extends FhmController
{
    /**
     * FrontController constructor.
     *
     * @param \Fhm\FhmBundle\Services\Tools $tools
     */
    public function __construct(\Fhm\FhmBundle\Services\Tools $tools)
    {
        $this->setFhmTools($tools);
        parent::__construct('Fhm', 'Event', 'event');
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
     *      path="/create",
     *      name="fhm_event_create"
     * )
     * @Template("::FhmEvent/Front/create.html.twig")
     */
    public function createAction(Request $request)
    {
        // For activate this route, delete next line
        throw $this->createNotFoundException($this->fhm_tools->trans('fhm.error.route', array(), 'FhmFhmBundle'));
    }

    /**
    * @Route
    * (
    *      path="/duplicate/{id}",
    *      name="fhm_event_duplicate",
    *      requirements={"id"="[a-z0-9]*"}
    * )
    * @Template("::FhmEvent/Front/create.html.twig")
    */
    public function duplicateAction(Request $request, $id)
    {
        // For activate this route, delete next line
        throw $this->createNotFoundException($this->fhm_tools->trans('fhm.error.route', array(), 'FhmFhmBundle'));
    }

    /**
    * @Route
    * (
    *      path="/update/{id}",
    *      name="fhm_event_update",
    *      requirements={"id"="[a-z0-9]*"}
    * )
    * @Template("::FhmEvent/Front/update.html.twig")
    */
    public function updateAction(Request $request, $id)
    {
        // For activate this route, delete next line
        throw $this->createNotFoundException($this->fhm_tools->trans('fhm.error.route', array(), 'FhmFhmBundle'));
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
    *      path="/delete/{id}",
    *      name="fhm_event_delete",
    *      requirements={"id"="[a-z0-9]*"}
    * )
    */
    public function deleteAction($id)
    {
        // For activate this route, delete next line
        throw $this->createNotFoundException($this->fhm_tools->trans('fhm.error.route', array(), 'FhmFhmBundle'));
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