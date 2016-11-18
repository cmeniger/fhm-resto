<?php
namespace Fhm\WorkflowBundle\Controller\Step;

use Fhm\FhmBundle\Controller\RefAdminController as FhmController;
use Fhm\WorkflowBundle\Document\WorkflowStep;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * @Route("/admin/workflowstep")
 */
class AdminController extends FhmController
{
    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct('Fhm', 'Workflow', 'workflow_step', 'WorkflowStep');
        $this->form->type->create = 'Fhm\\WorkflowBundle\\Form\\Type\\Admin\\Step\\CreateType';
        $this->form->type->update = 'Fhm\\WorkflowBundle\\Form\\Type\\Admin\\Step\\UpdateType';
        $this->translation        = array('FhmWorkflowBundle', 'workflow.step');
    }

    /**
     * @Route
     * (
     *      path="/",
     *      name="fhm_admin_workflow_step"
     * )
     * @Template("::FhmWorkflow/Admin/Step/index.html.twig")
     */
    public function indexAction()
    {
        return parent::indexAction();
    }

    /**
     * @Route
     * (
     *      path="/create",
     *      name="fhm_admin_workflow_step_create"
     * )
     * @Template("::FhmWorkflow/Admin/Step/create.html.twig")
     */
    public function createAction(Request $request)
    {
        return parent::createAction($request);
    }

    /**
     * @Route
     * (
     *      path="/duplicate/{id}",
     *      name="fhm_admin_workflow_step_duplicate",
     *      requirements={"id"="[a-z0-9]*"}
     * )
     * @Template("::FhmWorkflow/Admin/Step/create.html.twig")
     */
    public function duplicateAction(Request $request, $id)
    {
        return parent::duplicateAction($request, $id);
    }

    /**
     * @Route
     * (
     *      path="/update/{id}",
     *      name="fhm_admin_workflow_step_update",
     *      requirements={"id"="[a-z0-9]*"}
     * )
     * @Template("::FhmWorkflow/Admin/Step/update.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        return parent::updateAction($request, $id);
    }

    /**
     * @Route
     * (
     *      path="/detail/{id}",
     *      name="fhm_admin_workflow_step_detail",
     *      requirements={"id"="[a-z0-9]*"}
     * )
     * @Template("::FhmWorkflow/Admin/Step/detail.html.twig")
     */
    public function detailAction($id)
    {
        return parent::detailAction($id);
    }

    /**
     * @Route
     * (
     *      path="/delete/{id}",
     *      name="fhm_admin_workflow_step_delete",
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
     *      name="fhm_admin_workflow_step_undelete",
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
     *      name="fhm_admin_workflow_step_activate",
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
     *      name="fhm_admin_workflow_step_deactivate",
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
     *      name="fhm_admin_workflow_step_import"
     * )
     * @Template("::FhmWorkflow/Admin/Step/import.html.twig")
     */
    public function importAction(Request $request)
    {
        return parent::importAction($request);
    }

    /**
     * @Route
     * (
     *      path="/export",
     *      name="fhm_admin_workflow_step_export"
     * )
     * @Template("::FhmWorkflow/Admin/Step/export.html.twig")
     */
    public function exportAction(Request $request)
    {
        return parent::exportAction($request);
    }

    /**
     * @Route
     * (
     *      path="/grouping",
     *      name="fhm_admin_workflow_step_grouping"
     * )
     */
    public function groupingAction(Request $request)
    {
        return parent::groupingAction($request);
    }
}