<?php
namespace Fhm\EventBundle\Controller;

use Fhm\EventBundle\Form\Type\Admin\CreateType;
use Fhm\EventBundle\Form\Type\Admin\UpdateType;
use Fhm\FhmBundle\Controller\RefAdminController as FhmController;
use Fhm\EventBundle\Document\Event;
use Fhm\FhmBundle\Form\Handler\Admin\CreateHandler;
use Fhm\FhmBundle\Form\Handler\Admin\UpdateHandler;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * @Route("/admin/event")
 * -----------------------------------
 * Class AdminController
 * @package Fhm\EventBundle\Controller
 */
class AdminController extends FhmController
{
    /**
     * AdminController constructor.
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
     *      name="fhm_admin_event"
     * )
     * @Template("::FhmEvent/Admin/index.html.twig")
     */
    public function indexAction()
    {
        return parent::indexAction();
    }

    /**
     * @Route
     * (
     *      path="/create",
     *      name="fhm_admin_event_create"
     * )
     * @Template("::FhmEvent/Admin/create.html.twig")
     */
    public function createAction(Request $request)
    {
        self::$form = new \stdClass();
        self::$form->type = CreateType::class;
        self::$form->handler = CreateHandler::class;
        return parent::createAction($request);
    }

    /**
     * @Route
     * (
     *      path="/duplicate/{id}",
     *      name="fhm_admin_event_duplicate",
     *      requirements={"id"="[a-z0-9]*"}
     * )
     * @Template("::FhmEvent/Admin/create.html.twig")
     */
    public function duplicateAction(Request $request, $id)
    {
        self::$form = new \stdClass();
        self::$form->type = CreateType::class;
        self::$form->handler = CreateHandler::class;
        return parent::duplicateAction($request, $id);
    }

    /**
     * @Route
     * (
     *      path="/update/{id}",
     *      name="fhm_admin_event_update",
     *      requirements={"id"="[a-z0-9]*"}
     * )
     * @Template("::FhmEvent/Admin/update.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        self::$form = new \stdClass();
        self::$form->type = UpdateType::class;
        self::$form->handler = UpdateHandler::class;
        return parent::updateAction($request, $id);
    }

    /**
     * @Route
     * (
     *      path="/detail/{id}",
     *      name="fhm_admin_event_detail",
     *      requirements={"id"="[a-z0-9]*"}
     * )
     * @Template("::FhmEvent/Admin/detail.html.twig")
     */
    public function detailAction($id)
    {
        $document = $this->get('fhm_tools')->dmRepository(self::$repository)->find($id);
        return array_merge(
            array(
                'eventgroups1' => $this->get('fhm_tools')->dmRepository('FhmEventBundle:EventGroup')->getListEnable(
                ),
                'eventgroups2' => $this->get('fhm_tools')->getList($document->getEventgroups()),
            ),
            parent::detailAction($id)
        );
    }

    /**
     * @Route
     * (
     *      path="/preview/{id}",
     *      name="fhm_admin_event_preview",
     *      requirements={"id"="[a-z0-9]*"}
     * )
     * @Template("::FhmEvent/Front/detail.html.twig")
     */
    public function previewAction($id)
    {
        return parent::detailAction($id);
    }

    /**
     * @Route
     * (
     *      path="/delete/{id}",
     *      name="fhm_admin_event_delete",
     *      requirements={"id"="[a-z0-9]*"}
     * )
     */
    public function deleteAction($id)
    {
        return parent::deleteAction($id);
    }

    /**
     * @Route
     * (
     *      path="/undelete/{id}",
     *      name="fhm_admin_event_undelete",
     *      requirements={"id"="[a-z0-9]*"}
     * )
     */
    public function undeleteAction($id)
    {
        return parent::undeleteAction($id);
    }

    /**
     * @Route
     * (
     *      path="/activate/{id}",
     *      name="fhm_admin_event_activate",
     *      requirements={"id"="[a-z0-9]*"}
     * )
     */
    public function activateAction($id)
    {
        return parent::activateAction($id);
    }

    /**
     * @Route
     * (
     *      path="/deactivate/{id}",
     *      name="fhm_admin_event_deactivate",
     *      requirements={"id"="[a-z0-9]*"}
     * )
     */
    public function deactivateAction($id)
    {
        return parent::deactivateAction($id);
    }

    /**
     * @Route
     * (
     *      path="/import",
     *      name="fhm_admin_event_import"
     * )
     * @Template("::FhmEvent/Admin/import.html.twig")
     */
    public function importAction(Request $request)
    {
        return parent::importAction($request);
    }

    /**
     * @Route
     * (
     *      path="/export",
     *      name="fhm_admin_event_export"
     * )
     * @Template("::FhmEvent/Admin/export.html.twig")
     */
    public function exportAction(Request $request)
    {
        return parent::exportAction($request);
    }

    /**
     * @Route
     * (
     *      path="/eventgroup",
     *      name="fhm_admin_event_eventgroup",
     *      requirements={"id"="[a-z0-9]*"}
     * )
     */
    public function eventgroupAction(Request $request)
    {
        $eventgroups = json_decode($request->get('list'));
        $document = $this->get('fhm_tools')->dmRepository(self::$repository)->find($request->get('id'));
        foreach ($document->getEventgroups() as $eventgroup) {
            $document->removeEventgroup($eventgroup);
        }
        foreach ($eventgroups as $key => $data) {
            $eventgroup = $this->get('fhm_tools')->dmRepository('FhmEventBundle:EventGroup')->find($data->id);
            $document->addEventgroup($eventgroup);
        }
        $this->get('fhm_tools')->dmPersist($document);

        return new Response();
    }
}