<?php
namespace Fhm\WorkflowBundle\Controller\Comment;

use Fhm\FhmBundle\Controller\RefApiController as FhmController;
use Fhm\WorkflowBundle\Document\WorkflowComment;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * @Route("/api/workflowcomment")
 * ---------------------------------------------
 * Class ApiController
 * @package Fhm\WorkflowBundle\Controller\Comment
 */
class ApiController extends FhmController
{
    /**
     * ApiController constructor.
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
     *      path="/",
     *      name="fhm_api_workflow_comment"
     * )
     * @Template("::FhmWorkflow/Api/Comment/index.html.twig")
     */
    public function indexAction()
    {
        return parent::indexAction();
    }

    /**
     * @Route
     * (
     *      path="/autocomplete",
     *      name="fhm_api_workflow_comment_autocomplete"
     * )
     * @Template("::FhmWorkflow/Api/Comment/autocomplete.html.twig")
     */
    public function autocompleteAction(Request $request)
    {
        return parent::autocompleteAction($request);
    }

    /**
     * @Route
     * (
     *      path="/workflow/{id}",
     *      name="fhm_api_workflow_comment_workflow",
     *      requirements={"id"="[a-z0-9]*"}
     * )
     * @Template("::FhmWorkflow/Template/comment.workflow.html.twig")
     */
    public function workflowAction($id)
    {
        $document = $this->get('fhm_tools')->dmRepository('FhmWorkflowBundle:Workflow')->find($id);
        $iterator = $document->getAllComments($this->getUser())->getIterator();
        $iterator->uasort(function ($a, $b) {
            return ($a->getDateCreate() > $b->getDateCreate()) ? -1 : 1;
        });

        return array(
            'documents' => iterator_to_array($iterator),
            'document'  => $document,
        );
    }

    /**
     * @Route
     * (
     *      path="/task/{id}",
     *      name="fhm_api_workflow_comment_task",
     *      requirements={"id"="[a-z0-9]*"}
     * )
     * @Template("::FhmWorkflow/Template/comment.task.html.twig")
     */
    public function taskAction($id)
    {
        $document = $this->get('fhm_tools')->dmRepository('FhmWorkflowBundle:WorkflowTask')->find($id);
        $iterator = $document->getComments()->getIterator();
        $iterator->uasort(function ($a, $b) {
            return ($a->getDateCreate() > $b->getDateCreate()) ? -1 : 1;
        });

        return array(
            'documents' => iterator_to_array($iterator),
            'task'      => $document,
        );
    }
}