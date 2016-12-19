<?php
namespace Fhm\WorkflowBundle\Twig;

use Fhm\FhmBundle\Services\Tools;

/**
 * Class WorkflowExtension
 * @package Fhm\WorkflowBundle\Twig
 */
class WorkflowExtension extends \Twig_Extension
{
    protected $fhm_tools;
    protected $template;
    protected $tasks;

    /**
     * WorkflowExtension constructor.
     * @param Tools $tools
     */
    public function __construct(Tools $tools)
    {
        $this->fhm_tools = $tools;
        $this->template = new \Twig_Environment();
        $this->tasks = array();
    }

    /**
     * {@inheritdoc}
     */
    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('wfTasks', array($this, 'getTasks')),
            new \Twig_SimpleFilter('wfTask', array($this, 'getTask')),
            new \Twig_SimpleFilter('wfTaskAction', array($this, 'getTaskAction')),
            new \Twig_SimpleFilter('wfTaskAnchor', array($this, 'getTaskAnchor')),
            new \Twig_SimpleFilter('wfStepTask', array($this, 'getStepTask')),
            new \Twig_SimpleFilter('wfStepTaskAction', array($this, 'getStepTaskAction')),
        );
    }

    /**
     * @param $workflow
     *
     * @return null
     */
    public function getTasks($workflow)
    {
        return $this->_tasks($workflow);
    }

    /**
     * @param $task
     * @param $workflow
     *
     * @return null
     */
    public function getTask($task, $workflow, $instance)
    {
        return $this->template->render(
            '::FhmWorkflow/Template/task.html.twig',
            array(
                'document' => $task,
                'workflow' => $workflow,
                'instance' => $instance,
            )
        );
    }

    /**
     * @param $task
     * @param $workflow
     * @param $instance
     *
     * @return null
     */
    public function getTaskAction($task, $workflow, $instance)
    {
        if ($task == null || $task->getAction() == null) {
            return null;
        }

        return $this->template->render(
            '::FhmWorkflow/Template/task.action.html.twig',
            array(
                'document' => $task->getAction(),
                'workflow' => $workflow,
                'task' => $task,
                'instance' => $instance,
            )
        );
    }

    /**
     * @param $task
     *
     * @return string
     */
    public function getTaskAnchor($task)
    {
        $anchor = array(
            'data-id' => array($task->getId()),
            'data-parent' => array(),
            'data-son' => array(),
        );
        foreach ($task->getParents() as $parent) {
            $anchor['data-parent'][] = $parent->getId();
        }
        foreach ($task->getSons() as $son) {
            $anchor['data-son'][] = $son->getId();
        }

        return "data-id='".implode(',', $anchor['data-id'])."' data-parent='".implode(
            ',',
            $anchor['data-parent']
        )."' data-son='".implode(',', $anchor['data-son'])."'";
    }

    /**
     * @param $task
     * @param $workflow
     *
     * @return null
     */
    public function getStepTask($task, $workflow, $instance)
    {
        return $this->template->render(
            '::FhmWorkflow/Template/step.task.html.twig',
            array(
                'document' => $task,
                'workflow' => $workflow,
                'instance' => $instance,
            )
        );
    }

    /**
     * @param $task
     * @param $workflow
     * @param $instance
     *
     * @return null
     */
    public function getStepTaskAction($task, $workflow, $instance)
    {
        if ($task == null || $task->getAction() == null) {
            return null;
        }

        return $this->template->render(
            '::FhmWorkflow/Template/step.action.html.twig',
            array(
                'document' => $task->getAction(),
                'workflow' => $workflow,
                'task' => $task,
                'instance' => $instance,
            )
        );
    }

    /**
     * @param $workflow
     *
     * @return string
     */
    private function _tasks($workflow)
    {
        if ($workflow == null) {
            return array();
        }
        foreach ($workflow->getTasks() as $task) {
            $this->tasks[] = array('level' => 1, 'task' => $task);
        }
        foreach ($workflow->getTasks() as $task) {
            $this->_tasksSons($task, 2);
        }

        return $this->_tasksOrder();
    }

    /**
     * @param $parent
     * @param $level
     *
     * @return array
     */
    private function _tasksSons($parent, $level)
    {
        foreach ($parent->getSons() as $task) {
            if (!$this->_tasksIn($task)) {
                $this->tasks[] = array('level' => $level, 'task' => $task);
//                $this->_tasksSons($task, $level + 1);
            }
        }
        foreach ($parent->getSons() as $task) {
            $this->_tasksSons($task, $level + 1);
        }
    }

    /**
     * @param $task
     *
     * @return bool
     */
    private function _tasksIn($task)
    {
        foreach ($this->tasks as $data) {
            if ($data['task'] == $task) {
                return true;
            }
        }

        return false;
    }

    /**
     * @return array
     */
    private function _tasksOrder()
    {
        $list = array();
        $level = 1;
        $end = false;
        while (!$end) {
            $end = count($list) < count($this->tasks) ? false : true;
            foreach ($this->tasks as $task) {
                if ($task['level'] === $level) {
                    $list[] = $task;
                    $end = false;
                }
            }
            $level++;
        }

        return $list;
    }

    /**
     * Returns the name of the extension.
     *
     * @return string The extension name
     */
    public function getName()
    {
        return 'workflow_extension';
    }
}
