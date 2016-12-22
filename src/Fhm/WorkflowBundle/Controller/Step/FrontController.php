<?php
namespace Fhm\WorkflowBundle\Controller\Step;

use Fhm\FhmBundle\Controller\RefFrontController as FhmController;
use Fhm\WorkflowBundle\Document\WorkflowStep;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * @Route("/workflowstep")
 * ------------------------------------------
 * Class FrontController
 * @package Fhm\WorkflowBundle\Controller\Step
 */
class FrontController extends FhmController
{
    /**
     * FrontController constructor.
     */
    public function __construct()
    {
        self::$repository = "FhmWorkflowBundle:WorkflowStep";
        self::$source = "fhm";
        self::$domain = "FhmWorkflowBundle";
        self::$translation = "workflow.step";
        self::$class = WorkflowStep::class;
        self::$route = 'workflow_step';
    }

    /**
     * @Route
     * (
     *      path="/",
     *      name="fhm_workflow_step"
     * )
     * @Template("::FhmWorkflow/Front/Step/index.html.twig")
     */
    public function indexAction()
    {
        return array(
            'breadcrumbs' => array(
                array(
                    'link' => $this->getUrl('project_home'),
                    'text' => $this->trans('project.home.breadcrumb', array(), 'ProjectDefaultBundle'),
                ),
                array(
                    'link' => $this->getUrl('fhm_workflow'),
                    'text' => $this->trans('workflow.front.index.breadcrumb'),
                ),
                array(
                    'link'    => $this->getUrl(self::$source . '_' . self::$route),
                    'text'    => $this->trans(self::$translation.'.front.index.breadcrumb'),
                )
            )
        );
    }

    /**
     * @Route
     * (
     *      path="/detail/{id}",
     *      name="fhm_workflow_step_detail",
     *      requirements={"id"=".+"}
     * )
     * @Template("::FhmWorkflow/Front/Step/detail.html.twig")
     */
    public function detailAction($id)
    {
        return parent::detailAction($id);
    }

    /**
     * @Route
     * (
     *      path="/{id}",
     *      name="fhm_workflow_step_lite",
     *      requirements={"id"=".+"}
     * )
     * @Template("::FhmWorkflow/Front/Step/detail.html.twig")
     */
    public function liteAction($id)
    {
        return $this->detailAction($id);
    }
}