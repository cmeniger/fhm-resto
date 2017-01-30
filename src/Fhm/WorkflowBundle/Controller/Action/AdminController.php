<?php
namespace Fhm\WorkflowBundle\Controller\Action;

use Fhm\FhmBundle\Controller\RefAdminController as FhmController;
use Fhm\FhmBundle\Form\Handler\Admin\CreateHandler;
use Fhm\FhmBundle\Form\Handler\Admin\UpdateHandler;
use Fhm\WorkflowBundle\Document\WorkflowAction;
use Fhm\WorkflowBundle\Form\Type\Admin\Action\CreateType;
use Fhm\WorkflowBundle\Form\Type\Admin\Action\UpdateType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * @Route("/admin/workflowaction")
 * ---------------------------------------------
 * Class AdminController
 * @package Fhm\WorkflowBundle\Controller\Action
 */
class AdminController extends FhmController
{
    /**
     * ApiController constructor.
     */
    public function __construct()
    {
        self::$repository = "FhmWorkflowBundle:WorkflowAction";
        self::$source = "fhm";
        self::$domain = "FhmWorkflowBundle";
        self::$translation = "workflow.action";
        self::$class = WorkflowAction::class;
        self::$route = 'workflow_action';
        self::$form = new \stdClass();
        self::$form->createType = CreateType::class;
        self::$form->createHandler = CreateHandler::class;
        self::$form->updateType = UpdateType::class;
        self::$form->updateHandler = UpdateHandler::class;
    }

    /**
     * @Route
     * (
     *      path="/",
     *      name="fhm_admin_workflow_action"
     * )
     * @Template("::FhmWorkflow/Admin/Action/index.html.twig")
     */
    public function indexAction()
    {
        return parent::indexAction();
    }

    /**
     * @Route
     * (
     *      path="/create",
     *      name="fhm_admin_workflow_action_create"
     * )
     * @Template("::FhmWorkflow/Admin/Action/create.html.twig")
     */
    public function createAction(Request $request)
    {
        return parent::createAction($request);
    }

    /**
     * @Route
     * (
     *      path="/duplicate/{id}",
     *      name="fhm_admin_workflow_action_duplicate",
     *      requirements={"id"="[a-z0-9]*"}
     * )
     * @Template("::FhmWorkflow/Admin/Action/create.html.twig")
     */
    public function duplicateAction(Request $request, $id)
    {
        return parent::duplicateAction($request, $id);
    }

    /**
     * @Route
     * (
     *      path="/update/{id}",
     *      name="fhm_admin_workflow_action_update",
     *      requirements={"id"="[a-z0-9]*"}
     * )
     * @Template("::FhmWorkflow/Admin/Action/update.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        return parent::updateAction($request, $id);
    }

    /**
     * @Route
     * (
     *      path="/detail/{id}",
     *      name="fhm_admin_workflow_action_detail",
     *      requirements={"id"="[a-z0-9]*"}
     * )
     * @Template("::FhmWorkflow/Admin/Action/detail.html.twig")
     */
    public function detailAction($id)
    {
        return parent::detailAction($id);
    }

    /**
     * @Route
     * (
     *      path="/delete/{id}",
     *      name="fhm_admin_workflow_action_delete",
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
     *      name="fhm_admin_workflow_action_undelete",
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
     *      name="fhm_admin_workflow_action_activate",
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
     *      name="fhm_admin_workflow_action_deactivate",
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
     *      name="fhm_admin_workflow_action_import"
     * )
     * @Template("::FhmWorkflow/Admin/Action/import.html.twig")
     */
    public function importAction(Request $request)
    {
        return parent::importAction($request);
    }

    /**
     * @Route
     * (
     *      path="/export",
     *      name="fhm_admin_workflow_action_export"
     * )
     * @Template("::FhmWorkflow/Admin/Action/export.html.twig")
     */
    public function exportAction(Request $request)
    {
        return parent::exportAction($request);
    }
}