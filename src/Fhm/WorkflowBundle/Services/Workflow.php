<?php
namespace Fhm\WorkflowBundle\Services;

use Fhm\FhmBundle\Document\Fhm;
use Fhm\FhmBundle\Services\Tools;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Session\Session;
use Fhm\WorkflowBundle\Document\WorkflowTask;
/**
 * Class Workflow
 *
 * @package Fhm\WorkflowBundle\Services
 */
class Workflow
{
    private $tools;

    /**
     * Workflow constructor.
     * @param Tools $tools
     */
    public function __construct(Tools $tools)
    {
        $this->tools = $tools;
    }

    /**
     * @param \Fhm\WorkflowBundle\Document\Workflow $document
     * @param string $name
     * @return \Fhm\WorkflowBundle\Document\Workflow
     */
    public function duplicate(\Fhm\WorkflowBundle\Document\Workflow $document, $name = '')
    {
        $workflow = new \Fhm\WorkflowBundle\Document\Workflow();
        $workflow->setUserCreate($this->tools->getUser());
        $workflow->setName($name ? $name : $this->tools->getUnique(null, $document->getName()));
        $workflow->setDescription($document->getDescription());
        $workflow->setAlias($this->tools->getAlias(null, $workflow->getName(), 'FhmWorkflowBundle:Workflow'));
        $workflow->setActive(true);
        $workflow->setSeoTitle($document->getSeoTitle());
        $workflow->setSeoDescription($document->getSeoDescription());
        $workflow->setSeoKeywords($document->getSeoKeywords());
        $this->duplicateTasks($document, $workflow);
        $this->tools->dmPersist($workflow);

        return $workflow;
    }

    /**
     * @param \Fhm\WorkflowBundle\Document\Workflow $document
     * @param \Fhm\WorkflowBundle\Document\Workflow $workflow
     * @return $this
     */
    public function duplicateTasks(
        \Fhm\WorkflowBundle\Document\Workflow $document,
        \Fhm\WorkflowBundle\Document\Workflow &$workflow
    )
    {
        foreach ($document->getTasks() as $task) {
            $new = new WorkflowTask();
            $new->setUserCreate($this->tools->getUser());
            $new->setName($task->getName());
            $new->setDescription($task->getDescription());
            $new->setAlias($this->tools->getAlias(null, $task->getName(), 'FhmWorkflowBundle:WorkflowTask'));
            $new->setStep($task->getStep());
            $new->setAction($task->getAction());
            $new->setActive(true);
            $this->duplicateSons($task, $new);
            $this->tools->dmPersist($new);
            $workflow->addTask($new);
            $this->tools->dmPersist($workflow);
        }

        return $this;
    }

    /**
     * @param WorkflowTask $document
     * @param WorkflowTask $task
     * @return $this
     */
    public function duplicateSons(WorkflowTask $document, WorkflowTask &$task)
    {
        foreach ($document->getSons() as $son) {
            $new = new WorkflowTask();
            $new->setUserCreate($this->tools->getUser());
            $new->setName($son->getName());
            $new->setDescription($son->getDescription());
            $new->setAlias($this->tools->getAlias(null, $son->getName(), 'FhmWorkflowBundle:WorkflowTask'));
            $new->setStep($son->getStep());
            $new->setAction($son->getAction());
            $new->setActive(true);
            $new->addParent($task);
            $this->duplicateSons($son, $new);
            $this->tools->dmPersist($new);
        }

        return $this;
    }
}