<?php
namespace Fhm\WorkflowBundle\Controller;

use Fhm\FhmBundle\Controller\RefApiController as FhmController;
use Fhm\WorkflowBundle\Document\Workflow;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * @Route("/api/workflow")
 * --------------------------------------
 * Class ApiController
 * @package Fhm\WorkflowBundle\Controller
 */
class ApiController extends FhmController
{
    /**
     * ApiController constructor.
     */
    public function __construct()
    {
        self::$repository = "FhmWorkflowBundle:Workflow";
        self::$source = "fhm";
        self::$domain = "FhmWorkflowBundle";
        self::$translation = "workflow";
        self::$document = new Workflow();
        self::$class = get_class(self::$document);
        self::$route = 'workflow';
    }

    /**
     * @Route
     * (
     *      path="/",
     *      name="fhm_api_workflow"
     * )
     * @Template("::FhmWorkflow/Api/index.html.twig")
     */
    public function indexAction()
    {
        return parent::indexAction();
    }

    /**
     * @Route
     * (
     *      path="/autocomplete",
     *      name="fhm_api_workflow_autocomplete"
     * )
     * @Template("::FhmWorkflow/Api/autocomplete.html.twig")
     */
    public function autocompleteAction(Request $request)
    {
        return parent::autocompleteAction($request);
    }

    /**
     * @Route
     * (
     *      path="/modal/{id}",
     *      name="fhm_api_workflow_modal",
     *      requirements={"id"="[a-z0-9]*"}
     * )
     * @Template("::FhmWorkflow/Template/modal.workflow.html.twig")
     */
    public function modalAction($id)
    {
        $document = $this->get('fhm_tools')->dmRepository(self::$repository)->find($id);
        return array(
            'document' => $document,
        );
    }

    /**
     * @Route
     * (
     *      path="/status",
     *      name="fhm_api_workflow_status"
     * )
     * @Template("::FhmWorkflow/Template/workflow.status.html.twig")
     */
    public function statusAction()
    {
        return array(
            'datas'    => array(
                '0' => array(
                    'trans'     => 'workflow.status.wait',
                    'code'      => 'wait',
                    'workflows' => $this->get('fhm_tools')->dmRepository(self::$repository)->getByStatus(0, true)
                ),
                '1' => array(
                    'trans'     => 'workflow.status.run',
                    'code'      => 'run',
                    'workflows' => $this->get('fhm_tools')->dmRepository(self::$repository)->getByStatus(1, true)
                ),
                '2' => array(
                    'trans'     => 'workflow.status.finish',
                    'code'      => 'finish',
                    'workflows' => $this->get('fhm_tools')->dmRepository(self::$repository)->getByStatus(2, true)
                ),
                '3' => array(
                    'trans'     => 'workflow.status.cancel',
                    'code'      => 'cancel',
                    'workflows' => $this->get('fhm_tools')->dmRepository(self::$repository)->getByStatus(3, true)
                )
            )
        );
    }
}