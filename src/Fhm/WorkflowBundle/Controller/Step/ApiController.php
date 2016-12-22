<?php
namespace Fhm\WorkflowBundle\Controller\Step;

use Fhm\FhmBundle\Controller\RefApiController as FhmController;
use Fhm\WorkflowBundle\Document\WorkflowStep;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * @Route("/api/workflowstep")
 * -------------------------------------------
 * Class ApiController
 * @package Fhm\WorkflowBundle\Controller\Step
 */
class ApiController extends FhmController
{
    /**
     * ApiController constructor.
     */
    public function __construct()
    {
        self::$repository = "FhmWorkflowBundle:WorkflowStep";
        self::$source = "fhm";
        self::$domain = "FhmWorkflowBundle";
        self::$translation = "workflow.step";
        self::$class = WorkflowStep::class;
        self::$route = 'workflow_step';
    }

    /**
     * @Route
     * (
     *      path="/",
     *      name="fhm_api_workflow_step"
     * )
     * @Template("::FhmWorkflow/Api/Step/index.html.twig")
     */
    public function indexAction()
    {
        return parent::indexAction();
    }

    /**
     * @Route
     * (
     *      path="/autocomplete",
     *      name="fhm_api_workflow_step_autocomplete"
     * )
     * @Template("::FhmWorkflow/Api/Step/autocomplete.html.twig")
     */
    public function autocompleteAction(Request $request)
    {
        return parent::autocompleteAction($request);
    }

    /**
     * @Route
     * (
     *      path="/board",
     *      name="fhm_api_workflow_step_board"
     * )
     * @Template("::FhmWorkflow/Template/step.html.twig")
     */
    public function boardAction()
    {
        return array(
            'documents' => $this->get('fhm_tools')->dmRepository(self::$repository)->getAllEnable(),
        );
    }

    /**
     * @Route
     * (
     *      path="/workflow/{id}",
     *      name="fhm_api_workflow_step_workflow",
     *      requirements={"id"="[a-z0-9]*"}
     * )
     * @Template("::FhmWorkflow/Template/step.workflow.html.twig")
     */
    public function workflowAction($id)
    {
        return array(
            'document'  => $this->get('fhm_tools')->dmRepository(self::$repository)->find($id),
            'workflows' => $this->get('fhm_tools')->dmRepository('FhmWorkflowBundle:Workflow')->getByStep($id, true),
        );
    }
}