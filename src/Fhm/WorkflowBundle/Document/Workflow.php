<?php
namespace Fhm\WorkflowBundle\Document;

use Fhm\FhmBundle\Document\Fhm as FhmFhm;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Workflow
 * @MongoDB\Document(repositoryClass="Fhm\WorkflowBundle\Repository\WorkflowRepository")
 */
class Workflow extends FhmFhm
{
    /**
     * @MongoDB\Field(type="int")
     * @Assert\Range(min = 0, max = 3)
     */
    protected $status;

    /**
     * @MongoDB\ReferenceOne(targetDocument="Fhm\WorkflowBundle\Document\WorkflowStep", nullable=true)
     */
    protected $step;

    /**
     * @MongoDB\ReferenceOne(targetDocument="Fhm\WorkflowBundle\Document\WorkflowTask", nullable=true)
     */
    protected $task;

    /**
     * @MongoDB\ReferenceMany(targetDocument="Fhm\WorkflowBundle\Document\WorkflowTask", nullable=true, cascade={"persist"})
     */
    protected $tasks;

    /**
     * @MongoDB\Field(type="int")
     */
    protected $sort_task;

    /**
     * @MongoDB\Field(type="int")
     */
    protected $sort_comment;

    /**
     * @MongoDB\Field(type="int")
     */
    protected $sort_log;

    /**
     * @MongoDB\Field(type="int")
     */
    protected $sort_step;

    /**
     * @MongoDB\Field(type="int")
     */
    protected $sort_media;

    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->tasks        = new ArrayCollection();
        $this->sort_task    = 0;
        $this->sort_comment = 0;
        $this->sort_log     = 0;
        $this->sort_step    = 0;
        $this->sort_media   = 0;
    }

    /**
     * Get status
     *
     * @return string $status
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set status
     *
     * @param string $status
     *
     * @return self
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Set step
     *
     * @param \Fhm\WorkflowBundle\Document\WorkflowStep $step
     *
     * @return self
     */
    public function setStep($step)
    {
        $this->step = ($step instanceof \Fhm\WorkflowBundle\Document\WorkflowStep) ? $step : null;

        return $this;
    }

    /**
     * Get step
     *
     * @return mixed
     */
    public function getStep()
    {
        return $this->step;
    }

    /**
     * Set task
     *
     * @param \Fhm\WorkflowBundle\Document\WorkflowTask $task
     *
     * @return self
     */
    public function setTask($task)
    {
        $this->task = ($task instanceof \Fhm\WorkflowBundle\Document\WorkflowTask) ? $task : null;

        return $this;
    }

    /**
     * Get task
     *
     * @return mixed
     */
    public function getTask()
    {
        return $this->task;
    }

    /**
     * Get tasks
     *
     * @return mixed
     */
    public function getTasks()
    {
        return $this->tasks;
    }

    /**
     * Set tasks
     *
     * @param ArrayCollection $tasks
     *
     * @return $this
     */
    public function setTasks(ArrayCollection $tasks)
    {
        $this->resetTasks();
        $this->tasks = $tasks;

        return $this;
    }

    /**
     * Add task
     *
     * @param \Fhm\WorkflowBundle\Document\WorkflowTask $task
     *
     * @return $this
     */
    public function addTask(\Fhm\WorkflowBundle\Document\WorkflowTask $task)
    {
        if(!$this->tasks->contains($task))
        {
            $this->tasks->add($task);
        }

        return $this;
    }

    /**
     * Remove task
     *
     * @param \Fhm\WorkflowBundle\Document\WorkflowTask $task
     *
     * @return $this
     */
    public function removeTask(\Fhm\WorkflowBundle\Document\WorkflowTask $task)
    {
        if($this->tasks->contains($task))
        {
            $this->tasks->removeElement($task);
        }

        return $this;
    }

    /**
     * Reset tasks
     *
     * @return $this
     */
    public function resetTasks()
    {
        $this->tasks = new ArrayCollection();

        return $this;
    }

    /**
     * Count tasks
     *
     * @return $this
     */
    public function countTasks()
    {
        $count = 0;
        $tasks = new ArrayCollection();
        foreach($this->tasks as $task)
        {
            $tasks->add($task);
        }
        foreach($this->tasks as $task)
        {
            $count += $task->countTasks($tasks);
        }

        return $count;
    }

    /**
     * Count comments
     *
     * @return $this
     */
    public function countComments()
    {
        $count = 0;
        $tasks = new ArrayCollection();
        foreach($this->tasks as $task)
        {
            $tasks->add($task);
        }
        foreach($this->tasks as $task)
        {
            $count += $task->countComments($tasks);
        }

        return $count;
    }

    /**
     * Count logs
     *
     * @return $this
     */
    public function countLogs()
    {
        $count = 0;
        $tasks = new ArrayCollection();
        foreach($this->tasks as $task)
        {
            $tasks->add($task);
        }
        foreach($this->tasks as $task)
        {
            $count += $task->countLogs($tasks);
        }

        return $count;
    }

    /**
     * Count medias
     *
     * @return $this
     */
    public function countMedias()
    {
        $count = 0;
        $tasks = new ArrayCollection();
        foreach($this->tasks as $task)
        {
            $tasks->add($task);
        }
        foreach($this->tasks as $task)
        {
            $count += $task->countMedias($tasks);
        }

        return $count;
    }

    /**
     * Get sort task
     *
     * @return $this
     */
    public function getSortTask()
    {
        return $this->sort_task;
    }

    /**
     * Get sort comment
     *
     * @return $this
     */
    public function getSortComment()
    {
        return $this->sort_comment;
    }

    /**
     * Get sort tasks
     *
     * @return $this
     */
    public function getSortLog()
    {
        return $this->sort_log;
    }

    /**
     * Get sort tasks
     *
     * @return $this
     */
    public function getSortMedia()
    {
        return $this->sort_media;
    }

    /**
     * Get all logs
     *
     * @return $this
     */
    public function getAllLogs()
    {
        $logs = new ArrayCollection();
        foreach($this->tasks as $task)
        {
            $task->getAllLogs($logs);
        }

        return $logs;
    }

    /**
     * Get all medias
     *
     * @param $user
     *
     * @return ArrayCollection
     */
    public function getAllMedias($user)
    {
        $medias = new ArrayCollection();
        if($user instanceof \Fhm\UserBundle\Document\User)
        {
            foreach($this->tasks as $task)
            {
                $task->getAllMedias($medias, $user);
            }
        }

        return $medias;
    }

    /**
     * Get all comment
     *
     * @param $user
     *
     * @return ArrayCollection
     */
    public function getAllComments($user)
    {
        $comments = new ArrayCollection();
        if($user instanceof \Fhm\UserBundle\Document\User)
        {
            foreach($this->tasks as $task)
            {
                $task->getAllComments($comments, $user);
            }
        }

        return $comments;
    }

    /**
     * Refresh
     *
     * @return $this
     */
    public function refresh()
    {
        $current = null;
        foreach($this->tasks as $task)
        {
            $current = $task->getTaskCurrent();
            if($current)
            {
                break;
            }
        }
        $this->setStep($current ? $current->getStep() : null);
        $this->setStatus($current ? $current->getStatus() : 2);
        $this->setTask($current);

        return $this;
    }

    /**
     * Sort update
     *
     * @return $this
     */
    public function sortUpdate()
    {
        $this->sort_task    = $this->countTasks();
        $this->sort_comment = $this->countComments();
        $this->sort_log     = $this->countLogs();
        $this->sort_media   = $this->countMedias();
        $this->sort_step    = $this->step ? $this->step->getOrder() : 0;
        $this->refresh();

        return parent::sortUpdate();
    }

    /**
     * @MongoDB\PreRemove()
     */
    public function preRemove()
    {
        $this->resetTasks();

        return parent::preRemove();
    }
}