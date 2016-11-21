<?php
namespace Fhm\EventBundle\Controller\Group;

use Fhm\FhmBundle\Controller\RefAdminController as FhmController;
use Fhm\EventBundle\Document\EventGroup;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * @Route("/admin/eventgroup")
 */
class AdminController extends FhmController
{
    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct('Fhm', 'Event', 'event_group', 'EventGroup');
        $this->form->type->create = 'Fhm\\EventBundle\\Form\\Type\\Admin\\Group\\CreateType';
        $this->form->type->update = 'Fhm\\EventBundle\\Form\\Type\\Admin\\Group\\UpdateType';
        $this->translation        = array('FhmEventBundle', 'event.group');
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
        $document = $this->dmRepository()->find($id);
        $instance = $this->instanceData($document);

        return array_merge(
            array(
                'event1' => $this->dmRepository('FhmEventBundle:Event')->getAllEnable($instance->grouping->current),
                'event2' => $this->getList($document->getEvent())
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
        $response  = parent::detailAction($id);
        $document  = $response['document'];
        $instance  = $response['instance'];
        $classType = $this->form->type->search;
        $form      = $this->createForm(new $classType($instance), null);
        $form->setData($this->get('request')->get($form->getName()));
        $dataSearch        = $form->getData();
        $dataPagination    = $this->get('request')->get('FhmPagination');
        $this->translation = array('FhmEventBundle', 'event');
        // Ajax pagination request
        if(isset($dataPagination['pagination']))
        {
            $documents = $this->dmRepository("FhmEventBundle:Event")->getEventByGroupIndex($document, $dataSearch['search'], $dataPagination['pagination'], $this->getParameters(array('pagination', 'front', 'page'), 'fhm_fhm'));

            return array_merge(
                $response,
                array(
                    'documents'  => $documents,
                    'pagination' => $this->getPagination($dataPagination['pagination'], count($documents), $this->dmRepository("FhmEventBundle:Event")->getEventByGroupCount($document, $dataSearch['search']), 'pagination', $this->formRename($form->getName(), $dataSearch), $this->generateUrl('fhm_event_group_lite', array('id' => $id))),
                ));
        }
        // Router request
        else
        {
            $documents = $this->dmRepository("FhmEventBundle:Event")->getEventByGroupIndex($document, $dataSearch['search'], 1, $this->getParameters(array('pagination', 'front', 'page'), 'fhm_fhm'));

            return array_merge(
                $response,
                array(
                    'documents'  => $documents,
                    'pagination' => $this->getPagination(1, count($documents), $this->dmRepository("FhmEventBundle:Event")->getEventByGroupCount($document, $dataSearch['search']), 'pagination', $this->formRename($form->getName(), $dataSearch), $this->generateUrl('fhm_event_group_lite', array('id' => $id))),
                    'form'       => $form->createView(),
                ));
        }
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
     *      path="/grouping",
     *      name="fhm_admin_event_group_grouping"
     * )
     */
    public function groupingAction(Request $request)
    {
        return parent::groupingAction($request);
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
        $event     = json_decode($request->get('list'));
        $document = $this->dmRepository()->find($request->get('id'));
        foreach($document->getEvents() as $event)
        {
            $document->removeEvent($event);
        }
        foreach($event as $key => $data)
        {
            $event = $this->dmRepository('FhmEventBundle:Event')->find($data->id);
            $document->addEvent($event);
        }
        $this->dmPersist($document);

        return new Response();
    }
}