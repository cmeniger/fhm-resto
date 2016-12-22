<?php
namespace Fhm\WorkflowBundle\Controller\Comment;

use Fhm\FhmBundle\Controller\RefFrontController as FhmController;
use Fhm\WorkflowBundle\Document\WorkflowComment;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * @Route("/workflowcomment")
 * ----------------------------------------------
 * Class FrontController
 * @package Fhm\WorkflowBundle\Controller\Comment
 */
class FrontController extends FhmController
{
    /**
     * FrontController constructor.
     */
    public function __construct()
    {
        self::$repository = "FhmWorkflowBundle:WorkflowComment";
        self::$source = "fhm";
        self::$domain = "FhmWorkflowBundle";
        self::$translation = "workflow.comment";
        self::$class = WorkflowComment::class;
        self::$route = 'workflow_comment';
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