<?php
namespace Fhm\WorkflowBundle\Controller\Action;

use Fhm\FhmBundle\Controller\RefApiController as FhmController;
use Fhm\WorkflowBundle\Document\WorkflowAction;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * @Route("/api/workflowaction")
 * ----------------------------------------------
 * Class ApiController
 * @package Fhm\WorkflowBundle\Controller\Action
 */
class ApiController extends FhmController
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
        self::$route = 'Workflow_action';
    }

    /**
     * @Route
     * (
     *      path="/",
     *      name="fhm_api_workflow_action"
     * )
     * @Template("::FhmWorkflow/Api/Action/index.html.twig")
     */
    public function indexAction()
    {
        return parent::indexAction();
    }

    /**
     * @Route
     * (
     *      path="/autocomplete",
     *      name="fhm_api_workflow_action_autocomplete"
     * )
     * @Template("::FhmWorkflow/Api/Action/autocomplete.html.twig")
     */
    public function autocompleteAction(Request $request)
    {
        return parent::autocompleteAction($request);
    }
}