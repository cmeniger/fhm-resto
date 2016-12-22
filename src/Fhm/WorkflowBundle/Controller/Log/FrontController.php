<?php
namespace Fhm\WorkflowBundle\Controller\Log;

use Fhm\FhmBundle\Controller\RefFrontController as FhmController;
use Fhm\WorkflowBundle\Document\WorkflowLog;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * @Route("/workflowlog")
 * ------------------------------------------
 * Class FrontController
 * @package Fhm\WorkflowBundle\Controller\Log
 */
class FrontController extends FhmController
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
     *      path="/{id}",
     *      name="fhm_workflow_log_lite",
     *      requirements={"id"=".+"}
     * )
     * @Template("::FhmWorkflow/Front/Log/detail.html.twig")
     */
    public function liteAction($id)
    {
        return $this->detailAction($id);
    }
}