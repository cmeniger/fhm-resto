<?php
namespace Fhm\WorkflowBundle\Controller\Task;

use Fhm\FhmBundle\Controller\RefFrontController as FhmController;
use Fhm\WorkflowBundle\Document\WorkflowTask;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * @Route("/workflowtask")
 * --------------------------------------------
 * Class FrontController
 * @package Fhm\WorkflowBundle\Controller\Task
 */
class FrontController extends FhmController
{
    /**
     * FrontController constructor.
     */
    public function __construct()
    {
        self::$repository = "FhmWorkflowBundle:WorkflowTask";
        self::$source = "fhm";
        self::$domain = "FhmWorkflowBundle";
        self::$translation = "workflow.task";
        self::$class = WorkflowTask::class;
        self::$route = 'workflow_task';
    }

    /**
     * @Route
     * (
     *      path="/{id}",
     *      name="fhm_workflow_task_detail",
     *      requirements={"id"=".+"}
     * )
     * @Template("::FhmWorkflow/Front/Task/detail.html.twig")
     */
    public function detailAction($id)
    {
        return $this->detailAction($id);
    }

    /**
     * @Route
     * (
     *      path="/{id}",
     *      name="fhm_workflow_task_lite",
     *      requirements={"id"=".+"}
     * )
     * @Template("::FhmWorkflow/Front/Task/detail.html.twig")
     */
    public function liteAction($id)
    {
        return $this->detailAction($id);
    }
}