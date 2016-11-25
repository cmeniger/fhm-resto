<?php
namespace Fhm\EventBundle\Controller\Group;

use Fhm\FhmBundle\Controller\RefFrontController as FhmController;
use Fhm\EventBundle\Document\Event;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * @Route("/eventgroup", service="fhm_event_controller_group_front")
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
        parent::__construct('Fhm', 'Event', 'event_group', 'EventGroup');
        $this->form->type->create = 'Fhm\\EventBundle\\Form\\Type\\Front\\Group\\CreateType';
        $this->form->type->update = 'Fhm\\EventBundle\\Form\\Type\\Front\\Group\\UpdateType';
        $this->translation = array('FhmEventBundle', 'event.group');
    }

    /**
     * @Route
     * (
     *      path="/",
     *      name="fhm_event_group"
     * )
     * @Template("::FhmEvent/Front/Group/index.html.twig")
     */
    public function indexAction()
    {
        $response = parent::indexAction();
        foreach ($response['documents'] as $key => $document) {
            $response['documents'][$key]->allevent = $this->fhm_tools->dmRepository(
                "FhmEventBundle:Event"
            )->getEventByGroupAll($document);
        }

        return $response;
    }

    /**
     * @Route
     * (
     *      path="/create",
     *      name="fhm_event_group_create"
     * )
     * @Template("::FhmEvent/Front/Group/create.html.twig")
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
     *      name="fhm_event_group_duplicate",
     *      requirements={"id"="[a-z0-9]*"}
     * )
     * @Template("::FhmEvent/Front/Group/create.html.twig")
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
     *      name="fhm_event_group_update",
     *      requirements={"id"="[a-z0-9]*"}
     * )
     * @Template("::FhmEvent/Front/Group/update.html.twig")
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
     *      name="fhm_event_group_detail",
     *      requirements={"id"=".+"}
     * )
     * @Template("::FhmEvent/Front/Group/detail.html.twig")
     */
    public function detailAction($id)
    {
        $response = parent::detailAction($id);
        $document = $response['document'];
        $instance = $response['instance'];
        $classType = $this->form->type->search;
        $form = $this->createForm(new $classType($instance), null);
        $form->setData($this->get('request_stack')->get($form->getName()));
        $dataSearch = $form->getData();
        $dataPagination = $this->get('request_stack')->get('FhmPagination');
        $this->translation = array('FhmEventBundle', 'event');
        // Ajax pagination request
        if (isset($dataPagination['pagination'])) {
            $documents = $this->fhm_tools->dmRepository("FhmEventBundle:Event")->getEventByGroupIndex(
                $document,
                $dataSearch['search'],
                $dataPagination['pagination'],
                $this->fhm_tools->getParameter(array('pagination', 'front', 'page'), 'fhm_fhm')
            );

            return array_merge(
                $response,
                array(
                    'documents' => $documents,
                    'pagination' => $this->fhm_tools->getPagination(
                        $dataPagination['pagination'],
                        count($documents),
                        $this->fhm_tools->dmRepository("FhmEventBundle:Event")->getEventByGroupCount(
                            $document,
                            $dataSearch['search']
                        ),
                        'pagination',
                        $this->fhm_tools->formRename($form->getName(), $dataSearch),
                        $this->fhm_tools->getUrl('fhm_event_group_lite', array('id' => $id))
                    ),
                )
            );
        } // Router request
        else {
            $documents = $this->fhm_tools->dmRepository("FhmEventBundle:Event")->getEventByGroupIndex(
                $document,
                $dataSearch['search'],
                1,
                $this->fhm_tools->getParameter(array('pagination', 'front', 'page'), 'fhm_fhm')
            );

            return array_merge(
                $response,
                array(
                    'documents' => $documents,
                    'pagination' => $this->fhm_tools->getPagination(
                        1,
                        count($documents),
                        $this->fhm_tools->dmRepository("FhmEventBundle:Event")->getEventByGroupCount(
                            $document,
                            $dataSearch['search']
                        ),
                        'pagination',
                        $this->fhm_tools->formRename($form->getName(), $dataSearch),
                        $this->fhm_tools->getUrl('fhm_event_group_lite', array('id' => $id))
                    ),
                    'form' => $form->createView(),
                )
            );
        }
    }

    /**
     * @Route
     * (
     *      path="/delete/{id}",
     *      name="fhm_event_group_delete",
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
     *      name="fhm_event_group_lite",
     *      requirements={"id"=".+"}
     * )
     * @Template("::FhmEvent/Front/Group/detail.html.twig")
     */
    public function liteAction($id)
    {
        return $this->detailAction($id);
    }
}