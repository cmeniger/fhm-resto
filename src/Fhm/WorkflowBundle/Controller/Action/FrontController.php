<?php
namespace Fhm\WorkflowBundle\Controller\Action;

use Fhm\FhmBundle\Controller\RefFrontController as FhmController;
use Fhm\WorkflowBundle\Document\WorkflowAction;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * @Route("/workflowaction")
 */
class FrontController extends FhmController
{
    /**
     * @param \Fhm\FhmBundle\Services\Tools $tools
     */
    public function __construct(\Fhm\FhmBundle\Services\Tools $tools)
    {
        $this->setFhmTools($tools);
        parent::__construct('Fhm', 'Workflow', 'workflow_action', 'WorkflowAction');
        $this->form->type->create = 'Fhm\\WorkflowBundle\\Form\\Type\\Front\\Action\\CreateType';
        $this->form->type->update = 'Fhm\\WorkflowBundle\\Form\\Type\\Front\\Action\\UpdateType';
        $this->translation        = array('FhmWorkflowBundle', 'workflow.action');
    }

    /**
     * @Route
     * (
     *      path="/",
     *      name="fhm_workflow_action"
     * )
     * @Template("::FhmWorkflow/Front/Action/index.html.twig")
     */
    public function indexAction()
    {
        return parent::indexAction();
    }

    /**
     * @Route
     * (
     *      path="/create",
     *      name="fhm_workflow_action_create"
     * )
     * @Template("::FhmWorkflow/Front/Action/create.html.twig")
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
    *      name="fhm_workflow_action_duplicate",
    *      requirements={"id"="[a-z0-9]*"}
    * )
    * @Template("::FhmWorkflow/Front/Action/create.html.twig")
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
    *      name="fhm_workflow_action_update",
    *      requirements={"id"="[a-z0-9]*"}
    * )
    * @Template("::FhmWorkflow/Front/Action/update.html.twig")
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
    *      name="fhm_workflow_action_detail",
    *      requirements={"id"=".+"}
    * )
    * @Template("::FhmWorkflow/Front/Action/detail.html.twig")
    */
    public function detailAction($id)
    {
        return parent::detailAction($id);
    }

    /**
    * @Route
    * (
    *      path="/delete/{id}",
    *      name="fhm_workflow_action_delete",
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
    *      name="fhm_workflow_action_lite",
    *      requirements={"id"=".+"}
    * )
    * @Template("::FhmWorkflow/Front/Action/detail.html.twig")
    */
    public function liteAction($id)
    {
        return $this->detailAction($id);
    }
}