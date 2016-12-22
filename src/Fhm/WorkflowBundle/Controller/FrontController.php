<?php
namespace Fhm\WorkflowBundle\Controller;

use Fhm\FhmBundle\Controller\RefFrontController as FhmController;
use Fhm\WorkflowBundle\Document\Workflow;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * @Route("/workflow")
 * --------------------------------------
 * Class FrontController
 * @package Fhm\WorkflowBundle\Controller
 */
class FrontController extends FhmController
{
    /**
     * FrontController constructor.
     */
    public function __construct()
    {
        self::$repository = "FhmWorkflowBundle:Workflow";
        self::$source = "fhm";
        self::$domain = "FhmWorkflowBundle";
        self::$translation = "workflow";
        self::$route = 'workflow';
    }

    /**
     * @Route
     * (
     *      path="/",
     *      name="fhm_workflow"
     * )
     * @Template("::FhmWorkflow/Front/index.html.twig")
     */
    public function indexAction()
    {
        return parent::indexAction();
    }

    /**
     * @Route
     * (
     *      path="/detail/{id}",
     *      name="fhm_workflow_detail",
     *      requirements={"id"=".+"}
     * )
     * @Template("::FhmWorkflow/Front/detail.html.twig")
     */
    public function detailAction($id)
    {
        return parent::detailAction($id);
    }

    /**
     * @Route
     * (
     *      path="/{id}",
     *      name="fhm_workflow_lite",
     *      requirements={"id"=".+"}
     * )
     * @Template("::FhmWorkflow/Front/detail.html.twig")
     */
    public function liteAction($id)
    {
        return $this->detailAction($id);
    }
}