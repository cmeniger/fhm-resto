<?php
namespace Fhm\WorkflowBundle\Controller\Comment;

use Fhm\FhmBundle\Controller\RefFrontController as FhmController;
use Fhm\WorkflowBundle\Document\WorkflowComment;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * @Route("/workflowcomment", service="fhm_workflow_controller_comment_front")
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
        parent::__construct('Fhm', 'Workflow', 'workflow_comment', 'WorkflowComment');
        $this->form->type->create = 'Fhm\\WorkflowBundle\\Form\\Type\\Front\\Comment\\CreateType';
        $this->form->type->update = 'Fhm\\WorkflowBundle\\Form\\Type\\Front\\Comment\\UpdateType';
        $this->translation        = array('FhmWorkflowBundle', 'workflow.comment');
    }

    /**
     * @Route
     * (
     *      path="/",
     *      name="fhm_workflow_comment"
     * )
     * @Template("::FhmWorkflow/Front/Comment/index.html.twig")
     */
    public function indexAction()
    {
        // For activate this route, delete next line
        throw $this->createNotFoundException($this->fhm_tools->trans('fhm.error.route', array(), 'FhmFhmBundle'));

        return parent::indexAction();
    }

    /**
     * @Route
     * (
     *      path="/create",
     *      name="fhm_workflow_comment_create"
     * )
     * @Template("::FhmWorkflow/Front/Comment/create.html.twig")
     */
    public function createAction(Request $request)
    {
        // For activate this route, delete next line
        throw $this->createNotFoundException($this->fhm_tools->trans('fhm.error.route', array(), 'FhmFhmBundle'));

        return parent::createAction($request);
    }

    /**
    * @Route
    * (
    *      path="/duplicate/{id}",
    *      name="fhm_workflow_comment_duplicate",
    *      requirements={"id"="[a-z0-9]*"}
    * )
    * @Template("::FhmWorkflow/Front/Comment/create.html.twig")
    */
    public function duplicateAction(Request $request, $id)
    {
        // For activate this route, delete next line
        throw $this->createNotFoundException($this->fhm_tools->trans('fhm.error.route', array(), 'FhmFhmBundle'));

        return parent::duplicateAction($request, $id);
    }

    /**
    * @Route
    * (
    *      path="/update/{id}",
    *      name="fhm_workflow_comment_update",
    *      requirements={"id"="[a-z0-9]*"}
    * )
    * @Template("::FhmWorkflow/Front/Comment/update.html.twig")
    */
    public function updateAction(Request $request, $id)
    {
        // For activate this route, delete next line
        throw $this->createNotFoundException($this->fhm_tools->trans('fhm.error.route', array(), 'FhmFhmBundle'));

        return parent::updateAction($request, $id);
    }

    /**
    * @Route
    * (
    *      path="/detail/{id}",
    *      name="fhm_workflow_comment_detail",
    *      requirements={"id"=".+"}
    * )
    * @Template("::FhmWorkflow/Front/Comment/detail.html.twig")
    */
    public function detailAction($id)
    {
        // For activate this route, delete next line
        throw $this->createNotFoundException($this->fhm_tools->trans('fhm.error.route', array(), 'FhmFhmBundle'));

        return parent::detailAction($id);
    }

    /**
    * @Route
    * (
    *      path="/delete/{id}",
    *      name="fhm_workflow_comment_delete",
    *      requirements={"id"="[a-z0-9]*"}
    * )
    */
    public function deleteAction($id)
    {
        // For activate this route, delete next line
        throw $this->createNotFoundException($this->fhm_tools->trans('fhm.error.route', array(), 'FhmFhmBundle'));

        return parent::deleteAction($id);
    }

    /**
    * @Route
    * (
    *      path="/{id}",
    *      name="fhm_workflow_comment_lite",
    *      requirements={"id"=".+"}
    * )
    * @Template("::FhmWorkflow/Front/Comment/detail.html.twig")
    */
    public function liteAction($id)
    {
        return $this->detailAction($id);
    }
}