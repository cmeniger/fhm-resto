<?php
namespace Fhm\WorkflowBundle\Entity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Fhm\FhmBundle\Entity\Fhm;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table()
 */
class Workflow extends Fhm
{
    /**
     * @ORM\Column(type="integer")
     * @Assert\Range(min = 0, max = 3)
     */
    protected $status;

    /**
     * @ORM\OneToOne(targetEntity="Fhm\WorkflowBundle\Entity\WorkflowStep", nullable=true)
     */
    protected $step;

    /**
     * @ORM\OneToOne(targetEntity="Fhm\WorkflowBundle\Entity\WorkflowTask", nullable=true)
     */
    protected $task;

    /**
     * @ORM\OneToMany(targetEntity="Fhm\WorkflowBundle\Entity\WorkflowTask", nullable=true, cascade={"persist"})
     */
    protected $tasks;

    /**
     * @ORM\Column(type="integer")
     */
    protected $sort_task;

    /**
     * @ORM\Column(type="integer")
     */
    protected $sort_comment;

    /**
     * @ORM\Column(type="integer")
     */
    protected $sort_log;

    /**
     * @ORM\Column(type="integer")
     */
    protected $sort_step;

    /**
     * @ORM\Column(type="integer")
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
     * @param \Fhm\WorkflowBundle\Entity\WorkflowStep $step
     *
     * @return self
     */
    public function setStep($step)
    {
        $this->step = ($step instanceof \Fhm\WorkflowBundle\Entity\WorkflowStep) ? $step : null;

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
     * @param \Fhm\WorkflowBundle\Entity\WorkflowTask $task
     *
     * @return self
     */
    public function setTask($task)
    {
        $this->task = ($task instanceof \Fhm\WorkflowBundle\Entity\WorkflowTask) ? $task : null;

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
     * @param \Fhm\WorkflowBundle\Entity\WorkflowTask $task
     *
     * @return $this
     */
    public function addTask(\Fhm\WorkflowBundle\Entity\WorkflowTask $task)
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
     * @param \Fhm\WorkflowBundle\Entity\WorkflowTask $task
     *
     * @return $this
     */
    public function removeTask(\Fhm\WorkflowBundle\Entity\WorkflowTask $task)
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
        if($user instanceof \Fhm\UserBundle\Entity\User)
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
        if($user instanceof \Fhm\UserBundle\Entity\User)
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
     * @ORM\PreRemove()
     */
    public function preRemove()
    {
        $this->resetTasks();

        return parent::preRemove();
    }
}