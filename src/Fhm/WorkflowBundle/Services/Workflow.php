<?php
namespace Fhm\WorkflowBundle\Services;
/**
 * Class Workflow
 *
 * @package Fhm\WorkflowBundle\Services
 */
class Workflow
{
    private $fhm_tools;

    /**
     * Workflow constructor.
     *
     * @param \Fhm\FhmBundle\Services\Tools $tools
     */
    public function __construct(\Fhm\FhmBundle\Services\Tools $tools)
    {
        $this->fhm_tools = $tools;
    }

    /**
     * @param \Fhm\WorkflowBundle\Document\Workflow $document
     * @param string                                $name
     *
     * @return \Fhm\WorkflowBundle\Document\Workflow
     */
    public function duplicate(\Fhm\WorkflowBundle\Document\Workflow $document, $name = '')
    {
        $workflow = new \Fhm\WorkflowBundle\Document\Workflow();
        $workflow->setUserCreate($this->fhm_tools->getUser());
        $workflow->setName($name ? $name : $this->fhm_tools->getUnique(null, $document->getName()));
        $workflow->setDescription($document->getDescription());
        $workflow->setAlias($this->fhm_tools->getAlias(null, $workflow->getName(), 'FhmWorkflowBundle:Workflow'));
        $workflow->setActive(true);
        $workflow->setSeoTitle($document->getSeoTitle());
        $workflow->setSeoDescription($document->getSeoDescription());
        $workflow->setSeoKeywords($document->getSeoKeywords());
        $this->_duplicateTasks($document, $workflow);
        $this->fhm_tools->dmPersist($workflow);

        return $workflow;
    }

    /**
     * @param \Fhm\WorkflowBundle\Document\Workflow $document
     * @param \Fhm\WorkflowBundle\Document\Workflow $workflow
     *
     * @return $this
     */
    public function _duplicateTasks(\Fhm\WorkflowBundle\Document\Workflow $document, \Fhm\WorkflowBundle\Document\Workflow &$workflow)
    {
        foreach($document->getTasks() as $task)
        {
            $new = new \Fhm\WorkflowBundle\Document\WorkflowTask();
            $new->setUserCreate($this->fhm_tools->getUser());
            $new->setName($task->getName());
            $new->setDescription($task->getDescription());
            $new->setAlias($this->fhm_tools->getAlias(null, $task->getName(), 'FhmWorkflowBundle:WorkflowTask'));
            $new->setStep($task->getStep());
            $new->setAction($task->getAction());
            $new->setActive(true);
            $this->_duplicateSons($task, $new);
            $this->fhm_tools->dmPersist($new);
            $workflow->addTask($new);
            $this->fhm_tools->dmPersist($workflow);
        }

        return $this;
    }

    /**
     * @param \Fhm\WorkflowBundle\Document\WorkflowTask $document
     * @param \Fhm\WorkflowBundle\Document\WorkflowTask $task
     *
     * @return $this
     */
    public function _duplicateSons(\Fhm\WorkflowBundle\Document\WorkflowTask $document, \Fhm\WorkflowBundle\Document\WorkflowTask &$task)
    {
        foreach($document->getSons() as $son)
        {
            $new = new \Fhm\WorkflowBundle\Document\WorkflowTask();
            $new->setUserCreate($this->fhm_tools->getUser());
            $new->setName($son->getName());
            $new->setDescription($son->getDescription());
            $new->setAlias($this->fhm_tools->getAlias(null, $son->getName(), 'FhmWorkflowBundle:WorkflowTask'));
            $new->setStep($son->getStep());
            $new->setAction($son->getAction());
            $new->setActive(true);
            $new->addParent($task);
            $this->_duplicateSons($son, $new);
            $this->fhm_tools->dmPersist($new);
        }

        return $this;
    }
}