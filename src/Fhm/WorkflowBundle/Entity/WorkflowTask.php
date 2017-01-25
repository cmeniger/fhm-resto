<?php
namespace Fhm\WorkflowBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Fhm\FhmBundle\Entity\Fhm;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table()
 */
class WorkflowTask extends Fhm
{
    /**
     * @ORM\Column(type="integer")
     * @Assert\Range(min = 0, max = 3)
     */
    protected $status;

    /**
     * @ORM\ManyToOne(targetEntity="WorkflowStep", inversedBy="tasks")
     */
    protected $step;

    /**
     * @ORM\ManyToOne(targetEntity="WorkflowAction", inversedBy="tasks")
     */
    protected $action;

    /**
     * @ORM\ManyToMany(targetEntity="WorkflowTask",  cascade={"persist"})
     * @ORM\JoinTable(name="wkfl_task_parent")
     */
    protected $parents;

    /**
     * @ORM\ManyToMany(targetEntity="WorkflowTask", cascade={"persist"})
     * @ORM\JoinTable(name="wkfl_task_son")
     */
    protected $sons;

    /**
     * @ORM\OneToMany(targetEntity="WorkflowLog", cascade={"persist"}, mappedBy="task")
     */
    protected $logs;

    /**
     * @ORM\OneToMany(targetEntity="WorkflowComment", cascade={"persist"}, mappedBy="task")
     */
    protected $comments;

    /**
     * @ORM\ManyToMany(targetEntity="Fhm\MediaBundle\Entity\Media",  cascade={"persist"})
     */
    protected $medias;

    /**
     * @ORM\Column(type="integer")
     */
    protected $sort_parent;

    /**
     * @ORM\Column(type="integer")
     */
    protected $sort_son;

    /**
     * @ORM\Column(type="integer")
     */
    protected $sort_log;

    /**
     * @ORM\Column(type="integer")
     */
    protected $sort_comment;

    /**
     * @ORM\Column(type="integer")
     */
    protected $sort_step;

    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->status       = 0;
        $this->parents      = new ArrayCollection();
        $this->sons         = new ArrayCollection();
        $this->logs         = new ArrayCollection();
        $this->comments     = new ArrayCollection();
        $this->medias       = new ArrayCollection();
        $this->sort_parent  = 0;
        $this->sort_son     = 0;
        $this->sort_log     = 0;
        $this->sort_comment = 0;
        $this->sort_step    = 0;
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
     * Set action
     *
     * @param \Fhm\WorkflowBundle\Entity\WorkflowAction $action
     *
     * @return self
     */
    public function setAction($action)
    {
        $this->action = ($action instanceof \Fhm\WorkflowBundle\Entity\WorkflowAction) ? $action : null;

        return $this;
    }

    /**
     * Get action
     *
     * @return \Fhm\MediaBundle\Entity\Media $action
     */
    public function getAction()
    {
        return $this->action;
    }

    /**
     * Get parents
     *
     * @return mixed
     */
    public function getParents()
    {
        return $this->parents;
    }

    /**
     * Set parents
     *
     * @param ArrayCollection $parents
     *
     * @return $this
     */
    public function setParents(ArrayCollection $parents)
    {
        $this->resetParents();
        foreach($parents as $parent)
        {
            $parent->addSon($this);
        }
        $this->parents = $parents;

        return $this;
    }

    /**
     * Add parent
     *
     * @param \Fhm\WorkflowBundle\Entity\WorkflowTask $parent
     *
     * @return $this
     */
    public function addParent(\Fhm\WorkflowBundle\Entity\WorkflowTask $parent)
    {
        if(!$this->parents->contains($parent))
        {
            $this->parents->add($parent);
            $parent->addSon($this);
        }

        return $this;
    }

    /**
     * Remove parent
     *
     * @param \Fhm\WorkflowBundle\Entity\WorkflowTask $parent
     *
     * @return $this
     */
    public function removeParent(\Fhm\WorkflowBundle\Entity\WorkflowTask $parent)
    {
        if($this->parents->contains($parent))
        {
            $this->parents->removeElement($parent);
            $parent->removeSon($this);
        }

        return $this;
    }

    /**
     * Reset parents
     *
     * @return $this
     */
    public function resetParents()
    {
        foreach($this->parents as $parent)
        {
            $parent->removeSon($this);
        }
        $this->parents = new ArrayCollection();

        return $this;
    }

    /**
     * Get sons
     *
     * @return mixed
     */
    public function getSons()
    {
        return $this->sons;
    }

    /**
     * Set sons
     *
     * @param ArrayCollection $sons
     *
     * @return $this
     */
    public function setSons(ArrayCollection $sons)
    {
        $this->resetSons();
        foreach($sons as $son)
        {
            $son->addParent($this);
        }
        $this->sons = $sons;

        return $this;
    }

    /**
     * Add son
     *
     * @param \Fhm\WorkflowBundle\Entity\WorkflowTask $son
     *
     * @return $this
     */
    public function addSon(\Fhm\WorkflowBundle\Entity\WorkflowTask $son)
    {
        if(!$this->sons->contains($son))
        {
            $this->sons->add($son);
            $son->addParent($this);
        }

        return $this;
    }

    /**
     * Remove son
     *
     * @param \Fhm\WorkflowBundle\Entity\WorkflowTask $son
     *
     * @return $this
     */
    public function removeSon(\Fhm\WorkflowBundle\Entity\WorkflowTask $son)
    {
        if($this->sons->contains($son))
        {
            $this->sons->removeElement($son);
            $son->removeParent($this);
        }

        return $this;
    }

    /**
     * Reset sons
     *
     * @return $this
     */
    public function resetSons()
    {
        foreach($this->sons as $son)
        {
            $son->removeParent($this);
        }
        $this->sons = new ArrayCollection();

        return $this;
    }

    /**
     * Get logs
     *
     * @return mixed
     */
    public function getLogs()
    {
        return $this->logs;
    }

    /**
     * Set logs
     *
     * @param ArrayCollection $logs
     *
     * @return $this
     */
    public function setLogs(ArrayCollection $logs)
    {
        $this->resetLogs();
        foreach($logs as $log)
        {
            $log->setTask($this);
        }
        $this->logs = $logs;

        return $this;
    }

    /**
     * Add log
     *
     * @param \Fhm\WorkflowBundle\Entity\WorkflowLog $log
     *
     * @return $this
     */
    public function addLog(\Fhm\WorkflowBundle\Entity\WorkflowLog $log)
    {
        if(!$this->logs->contains($log))
        {
            $this->logs->add($log);
            $log->setTask($this);
        }

        return $this;
    }

    /**
     * Remove log
     *
     * @param \Fhm\WorkflowBundle\Entity\WorkflowLog $log
     *
     * @return $this
     */
    public function removeLog(\Fhm\WorkflowBundle\Entity\WorkflowLog $log)
    {
        if($this->logs->contains($log))
        {
            $this->logs->removeElement($log);
            $log->setTask(null);
        }

        return $this;
    }

    /**
     * Reset logs
     *
     * @return $this
     */
    public function resetLogs()
    {
        foreach($this->logs as $log)
        {
            $log->setTask(null);
        }
        $this->logs = new ArrayCollection();

        return $this;
    }

    /**
     * Get comments
     *
     * @return mixed
     */
    public function getComments()
    {
        return $this->comments;
    }

    /**
     * Set comments
     *
     * @param ArrayCollection $comments
     *
     * @return $this
     */
    public function setComments(ArrayCollection $comments)
    {
        $this->resetComments();
        foreach($comments as $comment)
        {
            $comment->setTask($this);
        }
        $this->comments = $comments;

        return $this;
    }

    /**
     * Add comment
     *
     * @param \Fhm\WorkflowBundle\Entity\WorkflowComment $comment
     *
     * @return $this
     */
    public function addComment(\Fhm\WorkflowBundle\Entity\WorkflowComment $comment)
    {
        if(!$this->comments->contains($comment))
        {
            $this->comments->add($comment);
            $comment->setTask($this);
        }

        return $this;
    }

    /**
     * Remove comment
     *
     * @param \Fhm\WorkflowBundle\Entity\WorkflowComment $comment
     *
     * @return $this
     */
    public function removeComment(\Fhm\WorkflowBundle\Entity\WorkflowComment $comment)
    {
        if($this->comments->contains($comment))
        {
            $this->comments->removeElement($comment);
            $comment->setTask(null);
        }

        return $this;
    }

    /**
     * Reset comments
     *
     * @return $this
     */
    public function resetComments()
    {
        foreach($this->comments as $comment)
        {
            $comment->setTask(null);
        }
        $this->comments = new ArrayCollection();

        return $this;
    }

    /**
     * Get medias
     *
     * @return mixed
     */
    public function getMedias()
    {
        return $this->medias;
    }

    /**
     * Set medias
     *
     * @param ArrayCollection $medias
     *
     * @return $this
     */
    public function setMedias(ArrayCollection $medias)
    {
        $this->resetMedias();
        $this->medias = $medias;

        return $this;
    }

    /**
     * Add media
     *
     * @param \Fhm\MediaBundle\Entity\Media $media
     *
     * @return $this
     */
    public function addMedia(\Fhm\MediaBundle\Entity\Media $media)
    {
        if(!$this->medias->contains($media))
        {
            $this->medias->add($media);
        }

        return $this;
    }

    /**
     * Remove media
     *
     * @param \Fhm\MediaBundle\Entity\Media $media
     *
     * @return $this
     */
    public function removeMedia(\Fhm\MediaBundle\Entity\Media $media)
    {
        if($this->medias->contains($media))
        {
            $this->medias->removeElement($media);
        }

        return $this;
    }

    /**
     * Reset medias
     *
     * @return $this
     */
    public function resetMedias()
    {
        $this->medias = new ArrayCollection();

        return $this;
    }

    /**
     * Count tasks
     *
     * @return $this
     */
    public function countTasks(ArrayCollection &$tasks)
    {
        $count = 1;
        foreach($this->parents as $task)
        {
            if(!$tasks->contains($task))
            {
                $tasks->add($task);
                $count += $task->countTasks($tasks);
            }
        }
        foreach($this->sons as $task)
        {
            if(!$tasks->contains($task))
            {
                $tasks->add($task);
                $count += $task->countTasks($tasks);
            }
        }

        return $count;
    }

    /**
     * Count comments
     *
     * @return $this
     */
    public function countComments(ArrayCollection &$tasks)
    {
        $count = $this->comments->count();
        foreach($this->parents as $task)
        {
            if(!$tasks->contains($task))
            {
                $tasks->add($task);
                $count += $task->countComments($tasks);
            }
        }
        foreach($this->sons as $task)
        {
            if(!$tasks->contains($task))
            {
                $tasks->add($task);
                $count += $task->countComments($tasks);
            }
        }

        return $count;
    }

    /**
     * Count logs
     *
     * @return $this
     */
    public function countLogs(ArrayCollection &$tasks)
    {
        $count = $this->logs->count();
        foreach($this->parents as $task)
        {
            if(!$tasks->contains($task))
            {
                $tasks->add($task);
                $count += $task->countLogs($tasks);
            }
        }
        foreach($this->sons as $task)
        {
            if(!$tasks->contains($task))
            {
                $tasks->add($task);
                $count += $task->countLogs($tasks);
            }
        }

        return $count;
    }

    /**
     * Count medias
     *
     * @return $this
     */
    public function countMedias(ArrayCollection &$tasks)
    {
        $count = $this->medias->count();
        foreach($this->parents as $task)
        {
            if(!$tasks->contains($task))
            {
                $tasks->add($task);
                $count += $task->countMedias($tasks);
            }
        }
        foreach($this->sons as $task)
        {
            if(!$tasks->contains($task))
            {
                $tasks->add($task);
                $count += $task->countMedias($tasks);
            }
        }

        return $count;
    }

    /**
     * Check status
     *
     * @return $this
     */
    public function checkStatus()
    {
        foreach($this->parents as $parent)
        {
            if($parent->getStatus() != 2)
            {
                $this->setStatus(0);
                break;
            }
            else
            {
                $this->setStatus(1);
            }
        }

        return $this;
    }

    /**
     * Task current
     *
     * @return $this
     */
    public function getTaskCurrent()
    {
        if($this->status == 1 || $this->status == 3 || ($this->status == 0 && $this->parents->count() == 0))
        {
            return $this;
        }
        foreach($this->sons as $son)
        {
            $current = $son->getTaskCurrent();
            if($current)
            {
                return $current;
            }
        }

        return null;
    }

    /**
     * Get all logs
     *
     * @param $logs
     *
     * @return $this
     */
    public function getAllLogs(&$logs)
    {
        foreach($this->logs as $log)
        {
            if(!$logs->contains($log))
            {
                $logs->add($log);
            }
        }
        foreach($this->sons as $son)
        {
            $son->getAllLogs($logs);
        }

        return $this;
    }

    /**
     * Get all medias
     *
     * @param $medias
     * @param $user
     *
     * @return $this
     */
    public function getAllMedias(&$medias, $user)
    {
        if($this->action && $this->action->hasUserDownload($user))
        {
            foreach($this->medias as $media)
            {
                if(!$medias->contains($media))
                {
                    $medias->add($media);
                }
            }
        }
        foreach($this->sons as $son)
        {
            $son->getAllMedias($medias, $user);
        }

        return $this;
    }

    /**
     * Get all comments
     *
     * @param $comments
     * @param $user
     *
     * @return $this
     */
    public function getAllComments(&$comments, $user)
    {
        if($this->action && $this->action->hasUserDownload($user))
        {
            foreach($this->comments as $comment)
            {
                if(!$comments->contains($comment))
                {
                    $comments->add($comment);
                }
            }
        }
        foreach($this->sons as $son)
        {
            $son->getAllComments($comments, $user);
        }

        return $this;
    }

    /**
     * Sort update
     *
     * @return $this
     */
    public function sortUpdate()
    {
        $this->sort_parent  = $this->parents->count();
        $this->sort_son     = $this->sons->count();
        $this->sort_log     = $this->logs->count();
        $this->sort_comment = $this->comments->count();
        $this->sort_step    = $this->step ? $this->step->getOrder() : 0;

        return parent::sortUpdate();
    }

    /**
     * @ORM\PrePersist()
     */
    public function prePersist()
    {
        $this->setStatus(0);

        return parent::prePersist();
    }

    /**
     * @ORM\PreRemove()
     */
    public function preRemove()
    {
        $this->resetParents();
        $this->resetSons();
        $this->resetLogs();
        $this->resetComments();
        $this->resetMedias();

        return parent::preRemove();
    }
}