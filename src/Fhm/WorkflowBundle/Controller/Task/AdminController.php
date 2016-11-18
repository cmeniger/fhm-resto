<?php
namespace Fhm\WorkflowBundle\Controller\Task;

use Fhm\FhmBundle\Controller\RefAdminController as FhmController;
use Fhm\WorkflowBundle\Document\WorkflowTask;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * @Route("/admin/workflowtask")
 */
class AdminController extends FhmController
{
    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct('Fhm', 'Workflow', 'workflow_task', 'WorkflowTask');
        $this->form->type->create = 'Fhm\\WorkflowBundle\\Form\\Type\\Admin\\Task\\CreateType';
        $this->form->type->update = 'Fhm\\WorkflowBundle\\Form\\Type\\Admin\\Task\\UpdateType';
        $this->translation        = array('FhmWorkflowBundle', 'workflow.task');
    }

    /**
     * @Route
     * (
     *      path="/",
     *      name="fhm_admin_workflow_task"
     * )
     * @Template("::FhmWorkflow/Admin/Task/index.html.twig")
     */
    public function indexAction()
    {
        return parent::indexAction();
    }

    /**
     * @Route
     * (
     *      path="/create",
     *      name="fhm_admin_workflow_task_create"
     * )
     * @Template("::FhmWorkflow/Admin/Task/create.html.twig")
     */
    public function createAction(Request $request)
    {
        return parent::createAction($request);
    }

    /**
     * @Route
     * (
     *      path="/duplicate/{id}",
     *      name="fhm_admin_workflow_task_duplicate",
     *      requirements={"id"="[a-z0-9]*"}
     * )
     * @Template("::FhmWorkflow/Admin/Task/create.html.twig")
     */
    public function duplicateAction(Request $request, $id)
    {
        return parent::duplicateAction($request, $id);
    }

    /**
     * @Route
     * (
     *      path="/update/{id}",
     *      name="fhm_admin_workflow_task_update",
     *      requirements={"id"="[a-z0-9]*"}
     * )
     * @Template("::FhmWorkflow/Admin/Task/update.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        return parent::updateAction($request, $id);
    }

    /**
     * @Route
     * (
     *      path="/detail/{id}",
     *      name="fhm_admin_workflow_task_detail",
     *      requirements={"id"="[a-z0-9]*"}
     * )
     * @Template("::FhmWorkflow/Admin/Task/detail.html.twig")
     */
    public function detailAction($id)
    {
        return parent::detailAction($id);
    }

    /**
     * @Route
     * (
     *      path="/delete/{id}",
     *      name="fhm_admin_workflow_task_delete",
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
     *      name="fhm_admin_workflow_task_undelete",
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
     *      name="fhm_admin_workflow_task_activate",
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
     *      name="fhm_admin_workflow_task_deactivate",
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
     *      name="fhm_admin_workflow_task_import"
     * )
     * @Template("::FhmWorkflow/Admin/Task/import.html.twig")
     */
    public function importAction(Request $request)
    {
        return parent::importAction($request);
    }

    /**
     * @Route
     * (
     *      path="/export",
     *      name="fhm_admin_workflow_task_export"
     * )
     * @Template("::FhmWorkflow/Admin/Task/export.html.twig")
     */
    public function exportAction(Request $request)
    {
        return parent::exportAction($request);
    }

    /**
     * @Route
     * (
     *      path="/grouping",
     *      name="fhm_admin_workflow_task_grouping"
     * )
     */
    public function groupingAction(Request $request)
    {
        return parent::groupingAction($request);
    }
}