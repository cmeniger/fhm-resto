<?php
namespace Fhm\WorkflowBundle\Services;

use Fhm\FhmBundle\Controller\FhmController;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Session\Session;

/**
 * Class Workflow
 *
 * @package Fhm\WorkflowBundle\Services
 */
class Workflow extends FhmController
{
    /**
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        parent::__construct();
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
        $workflow->setUserCreate($this->getUser());
        $workflow->setName($name ? $name : $this->getUnique(null, $document->getName()));
        $workflow->setDescription($document->getDescription());
        $workflow->setAlias($this->getAlias(null, $workflow->getName(), 'FhmWorkflowBundle:Workflow'));
        $workflow->setActive(true);
        $workflow->setSeoTitle($document->getSeoTitle());
        $workflow->setSeoDescription($document->getSeoDescription());
        $workflow->setSeoKeywords($document->getSeoKeywords());
        $this->_duplicateTasks($document, $workflow);
        $this->dmPersist($workflow);

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
            $new->setUserCreate($this->getUser());
            $new->setName($task->getName());
            $new->setDescription($task->getDescription());
            $new->setAlias($this->getAlias(null, $task->getName(), 'FhmWorkflowBundle:WorkflowTask'));
            $new->setStep($task->getStep());
            $new->setAction($task->getAction());
            $new->setActive(true);
            $this->_duplicateSons($task, $new);
            $this->dmPersist($new);
            $workflow->addTask($new);
            $this->dmPersist($workflow);
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
            $new->setUserCreate($this->getUser());
            $new->setName($son->getName());
            $new->setDescription($son->getDescription());
            $new->setAlias($this->getAlias(null, $son->getName(), 'FhmWorkflowBundle:WorkflowTask'));
            $new->setStep($son->getStep());
            $new->setAction($son->getAction());
            $new->setActive(true);
            $new->addParent($task);
            $this->_duplicateSons($son, $new);
            $this->dmPersist($new);
        }

        return $this;
    }
}