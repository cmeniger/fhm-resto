<?php
namespace Fhm\EventBundle\Controller\Group;

use Fhm\EventBundle\Form\Type\Admin\Group\CreateType;
use Fhm\EventBundle\Form\Type\Admin\Group\UpdateType;
use Fhm\FhmBundle\Controller\RefAdminController as FhmController;
use Fhm\FhmBundle\Form\Handler\Admin\CreateHandler;
use Fhm\FhmBundle\Form\Handler\Admin\UpdateHandler;
use Fhm\FhmBundle\Form\Type\Admin\SearchType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * @Route("/admin/eventgroup")
 * ----------------------------------------
 * Class AdminController
 * @package Fhm\EventBundle\Controller\Group
 */
class AdminController extends FhmController
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
        self::$route = "event_group";
        self::$form = new \stdClass();
        self::$form->createType    = CreateType::class;
        self::$form->createHandler = CreateHandler::class;
        self::$form->updateType    = UpdateType::class;
        self::$form->updateHandler = UpdateHandler::class;
    }

    /**
     * @Route
     * (
     *      path="/",
     *      name="fhm_admin_event_group"
     * )
     * @Template("::FhmEvent/Admin/Group/index.html.twig")
     */
    public function indexAction()
    {
        return parent::indexAction();
    }

    /**
     * @Route
     * (
     *      path="/create",
     *      name="fhm_admin_event_group_create"
     * )
     * @Template("::FhmEvent/Admin/Group/create.html.twig")
     */
    public function createAction(Request $request)
    {
        return parent::createAction($request);
    }

    /**
     * @Route
     * (
     *      path="/duplicate/{id}",
     *      name="fhm_admin_event_group_duplicate",
     *      requirements={"id"="[a-z0-9]*"}
     * )
     * @Template("::FhmEvent/Admin/Group/create.html.twig")
     */
    public function duplicateAction(Request $request, $id)
    {
        return parent::duplicateAction($request, $id);
    }

    /**
     * @Route
     * (
     *      path="/update/{id}",
     *      name="fhm_admin_event_group_update",
     *      requirements={"id"="[a-z0-9]*"}
     * )
     * @Template("::FhmEvent/Admin/Group/update.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        return parent::updateAction($request, $id);
    }

    /**
     * @Route
     * (
     *      path="/detail/{id}",
     *      name="fhm_admin_event_group_detail",
     *      requirements={"id"="[a-z0-9]*"}
     * )
     * @Template("::FhmEvent/Admin/Group/detail.html.twig")
     */
    public function detailAction($id)
    {
        $object = $this->get('fhm_tools')->dmRepository(self::$repository)->find($id);

        return array_merge(
            array(
                'event1' => $this->get('fhm_tools')->dmRepository('FhmEventBundle:Event')->getAllEnable(),
                'event2' => $this->getList($object->getEvent()),
            ),
            parent::detailAction($id)
        );
    }

    /**
     * @Route
     * (
     *      path="/preview/{id}",
     *      name="fhm_admin_event_group_preview",
     *      requirements={"id"="[a-z0-9]*"}
     * )
     * @Template("::FhmEvent/Front/Group/detail.html.twig")
     */
    public function previewAction($id)
    {
        $response = parent::detailAction($id);
        $object = $response['object'];
        $form = $this->createForm(SearchType::class);
        $form->setData($this->get('request_stack')->getCurrentRequest()->get($form->getName()));
        $dataSearch = $form->getData();
        $objects = $this->get('fhm_tools')->dmRepository("FhmEventBundle:Event")->getEventByGroupIndex(
            $object,
            $dataSearch['search']
        );

        return array_merge(
            $response,
            array(
                'objects' => $objects,
                'form' => $form->createView(),
            )
        );
    }

    /**
     * @Route
     * (
     *      path="/delete/{id}",
     *      name="fhm_admin_event_group_delete",
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
     *      name="fhm_admin_event_group_undelete",
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
     *      name="fhm_admin_event_group_activate",
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
     *      name="fhm_admin_event_group_deactivate",
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
     *      name="fhm_admin_event_group_import"
     * )
     * @Template("::FhmEvent/Admin/Group/import.html.twig")
     */
    public function importAction(Request $request)
    {
        return parent::importAction($request);
    }

    /**
     * @Route
     * (
     *      path="/export",
     *      name="fhm_admin_event_group_export"
     * )
     * @Template("::FhmEvent/Admin/Group/export.html.twig")
     */
    public function exportAction(Request $request)
    {
        return parent::exportAction($request);
    }

    /**
     * @Route
     * (
     *      path="/event",
     *      name="fhm_admin_event_group_event",
     *      requirements={"id"="[a-z0-9]*"}
     * )
     */
    public function eventAction(Request $request)
    {
        $event = json_decode($request->get('list'));
        $object = $this->get('fhm_tools')->dmRepository(self::$repository)->find($request->get('id'));
        foreach ($object->getEvents() as $event) {
            $object->removeEvent($event);
        }
        foreach ($event as $key => $data) {
            $event = $this->get('fhm_tools')->dmRepository('FhmEventBundle:Event')->find($data->id);
            $object->addEvent($event);
        }
        $this->get('fhm_tools')->dmPersist($object);

        return new Response();
    }
}