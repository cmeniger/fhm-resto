<?php
namespace Fhm\WorkflowBundle\Controller\Step;

use Fhm\FhmBundle\Controller\RefFrontController as FhmController;
use Fhm\WorkflowBundle\Document\WorkflowStep;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * @Route("/workflowstep")
 */
class FrontController extends FhmController
{
    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct('Fhm', 'Workflow', 'workflow_step', 'WorkflowStep');
        $this->form->type->create = 'Fhm\\WorkflowBundle\\Form\\Type\\Front\\Step\\CreateType';
        $this->form->type->update = 'Fhm\\WorkflowBundle\\Form\\Type\\Front\\Step\\UpdateType';
        $this->translation        = array('FhmWorkflowBundle', 'workflow.step');
    }

    /**
     * @Route
     * (
     *      path="/",
     *      name="fhm_workflow_step"
     * )
     * @Template("::FhmWorkflow/Front/Step/index.html.twig")
     */
    public function indexAction()
    {
        return array(
            'instance'    => $this->instanceData(),
            'breadcrumbs' => array(
                array(
                    'link' => $this->get('router')->generate('project_home'),
                    'text' => $this->get('translator')->trans('project.home.breadcrumb', array(), 'ProjectDefaultBundle'),
                ),
                array(
                    'link' => $this->get('router')->generate('fhm_workflow'),
                    'text' => $this->get('translator')->trans('workflow.front.index.breadcrumb', array(), $this->translation[0]),
                ),
                array(
                    'link'    => $this->get('router')->generate($this->source . '_' . $this->route),
                    'text'    => $this->get('translator')->trans($this->translation[1] . '.front.index.breadcrumb', array(), $this->translation[0]),
                    'current' => true
                )
            )
        );
    }

    /**
     * @Route
     * (
     *      path="/create",
     *      name="fhm_workflow_step_create"
     * )
     * @Template("::FhmWorkflow/Front/Step/create.html.twig")
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
     *      name="fhm_workflow_step_duplicate",
     *      requirements={"id"="[a-z0-9]*"}
     * )
     * @Template("::FhmWorkflow/Front/Step/create.html.twig")
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
     *      name="fhm_workflow_step_update",
     *      requirements={"id"="[a-z0-9]*"}
     * )
     * @Template("::FhmWorkflow/Front/Step/update.html.twig")
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
     *      name="fhm_workflow_step_detail",
     *      requirements={"id"=".+"}
     * )
     * @Template("::FhmWorkflow/Front/Step/detail.html.twig")
     */
    public function detailAction($id)
    {
        return parent::detailAction($id);
    }

    /**
     * @Route
     * (
     *      path="/delete/{id}",
     *      name="fhm_workflow_step_delete",
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
     *      name="fhm_workflow_step_lite",
     *      requirements={"id"=".+"}
     * )
     * @Template("::FhmWorkflow/Front/Step/detail.html.twig")
     */
    public function liteAction($id)
    {
        return $this->detailAction($id);
    }
}