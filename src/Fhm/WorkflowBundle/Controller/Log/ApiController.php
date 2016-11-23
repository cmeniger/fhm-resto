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
 * @Route("/api/workflowlog", service="fhm_workflow_controller_log_api")
 */
class ApiController extends FhmController
{
    /**
     * ApiController constructor.
     *
     * @param \Fhm\FhmBundle\Services\Tools $tools
     */
    public function __construct(\Fhm\FhmBundle\Services\Tools $tools)
    {
        $this->setFhmTools($tools);
        parent::__construct('Fhm', 'Workflow', 'workflow_log', 'WorkflowLog');
        $this->translation = array('FhmWorkflowBundle', 'workflow.log');
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
        $document = $this->fhm_tools->dmRepository('FhmWorkflowBundle:Workflow')->find($id);
        $instance = $this->fhm_tools->instanceData($document);
        $iterator = $document->getAllLogs()->getIterator();
        $iterator->uasort(function ($a, $b)
        {
            return ($a->getDateCreate() > $b->getDateCreate()) ? -1 : 1;
        });

        return array(
            'documents' => iterator_to_array($iterator),
            'document'  => $document,
            'instance'  => $instance
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
        $document = $this->fhm_tools->dmRepository('FhmWorkflowBundle:WorkflowTask')->find($id);
        $instance = $this->fhm_tools->instanceData($document);
        $iterator = $document->getLogs()->getIterator();
        $iterator->uasort(function ($a, $b)
        {
            return ($a->getDateCreate() > $b->getDateCreate()) ? -1 : 1;
        });

        return array(
            'documents' => iterator_to_array($iterator),
            'task'      => $document,
            'instance'  => $instance
        );
    }
}