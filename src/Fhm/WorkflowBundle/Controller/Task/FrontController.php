<?php
namespace Fhm\WorkflowBundle\Controller\Task;

use Fhm\FhmBundle\Controller\RefFrontController as FhmController;
use Fhm\WorkflowBundle\Document\WorkflowTask;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * @Route("/workflowtask")
 */
class FrontController extends FhmController
{
    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct('Fhm', 'Workflow', 'workflow_task', 'WorkflowTask');
        $this->form->type->create = 'Fhm\\WorkflowBundle\\Form\\Type\\Front\\Task\\CreateType';
        $this->form->type->update = 'Fhm\\WorkflowBundle\\Form\\Type\\Front\\Task\\UpdateType';
        $this->translation        = array('FhmWorkflowBundle', 'workflow.task');
    }

    /**
     * @Route
     * (
     *      path="/",
     *      name="fhm_workflow_task"
     * )
     * @Template("::FhmWorkflow/Front/Task/index.html.twig")
     */
    public function indexAction()
    {
        // For activate this route, delete next line
        throw $this->createNotFoundException($this->get('translator')->trans('fhm.error.route', array(), 'FhmFhmBundle'));

        return parent::indexAction();
    }

    /**
     * @Route
     * (
     *      path="/create",
     *      name="fhm_workflow_task_create"
     * )
     * @Template("::FhmWorkflow/Front/Task/create.html.twig")
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
    *      name="fhm_workflow_task_duplicate",
    *      requirements={"id"="[a-z0-9]*"}
    * )
    * @Template("::FhmWorkflow/Front/Task/create.html.twig")
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
    *      name="fhm_workflow_task_update",
    *      requirements={"id"="[a-z0-9]*"}
    * )
    * @Template("::FhmWorkflow/Front/Task/update.html.twig")
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
    *      name="fhm_workflow_task_detail",
    *      requirements={"id"=".+"}
    * )
    * @Template("::FhmWorkflow/Front/Task/detail.html.twig")
    */
    public function detailAction($id)
    {
        // For activate this route, delete next line
        throw $this->createNotFoundException($this->get('translator')->trans('fhm.error.route', array(), 'FhmFhmBundle'));

        return parent::detailAction($id);
    }

    /**
    * @Route
    * (
    *      path="/delete/{id}",
    *      name="fhm_workflow_task_delete",
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
    *      name="fhm_workflow_task_lite",
    *      requirements={"id"=".+"}
    * )
    * @Template("::FhmWorkflow/Front/Task/detail.html.twig")
    */
    public function liteAction($id)
    {
        return $this->detailAction($id);
    }
}