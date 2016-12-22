<?php
namespace Fhm\WorkflowBundle\Controller\Log;

use Fhm\FhmBundle\Controller\RefApiController as FhmController;
use Fhm\WorkflowBundle\Document\WorkflowLog;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * @Route("/api/workflowlog")
 * -------------------------------------------
 * Class ApiController
 * @package Fhm\WorkflowBundle\Controller\Log
 */
class ApiController extends FhmController
{
    /**
     * FrontController constructor.
     */
    public function __construct()
    {
        self::$repository = "FhmWorkflowBundle:WorkflowLog";
        self::$source = "fhm";
        self::$domain = "FhmWorkflowBundle";
        self::$translation = "workflow.log";
        self::$class = WorkflowLog::class;
        self::$route = 'workflow_log';
    }

    /**
     * @Route
     * (
     *      path="/",
     *      name="fhm_api_workflow_log"
     * )
     * @Template("::FhmWorkflow/Api/Log/index.html.twig")
     */
    public function indexAction()
    {
        return parent::indexAction();
    }

    /**
     * @Route
     * (
     *      path="/autocomplete",
     *      name="fhm_api_workflow_log_autocomplete"
     * )
     * @Template("::FhmWorkflow/Api/Log/autocomplete.html.twig")
     */
    public function autocompleteAction(Request $request)
    {
        return parent::autocompleteAction($request);
    }

    /**
     * @Route
     * (
     *      path="/workflow/{id}",
     *      name="fhm_api_workflow_log_workflow",
     *      requirements={"id"="[a-z0-9]*"}
     * )
     * @Template("::FhmWorkflow/Template/log.workflow.html.twig")
     */
    public function workflowAction($id)
    {
        $document = $this->get('fhm_tools')->dmRepository('FhmWorkflowBundle:Workflow')->find($id);
        $iterator = $document->getAllLogs()->getIterator();
        $iterator->uasort(
            function ($a, $b) {
                return ($a->getDateCreate() > $b->getDateCreate()) ? -1 : 1;
            }
        );

        return array(
            'documents' => iterator_to_array($iterator),
            'document' => $document,
        );
    }

    /**
     * @Route
     * (
     *      path="/task/{id}",
     *      name="fhm_api_workflow_log_task",
     *      requirements={"id"="[a-z0-9]*"}
     * )
     * @Template("::FhmWorkflow/Template/log.task.html.twig")
     */
    public function taskAction($id)
    {
        $document = $this->get('fhm_tools')->dmRepository('FhmWorkflowBundle:WorkflowTask')->find($id);
        $iterator = $document->getLogs()->getIterator();
        $iterator->uasort(
            function ($a, $b) {
                return ($a->getDateCreate() > $b->getDateCreate()) ? -1 : 1;
            }
        );

        return array(
            'documents' => iterator_to_array($iterator),
            'task' => $document,
        );
    }
}