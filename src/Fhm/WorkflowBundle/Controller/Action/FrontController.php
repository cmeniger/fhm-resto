<?php
namespace Fhm\WorkflowBundle\Controller\Action;

use Fhm\FhmBundle\Controller\RefFrontController as FhmController;
use Fhm\WorkflowBundle\Document\WorkflowAction;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * @Route("/workflowaction")
 * --------------------------------------------
 * Class FrontController
 * @package Fhm\WorkflowBundle\Controller\Action
 */
class FrontController extends FhmController
{
    /**
     * FrontController constructor.
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