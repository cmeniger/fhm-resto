<?php
namespace Fhm\WorkflowBundle\Document;

use Fhm\FhmBundle\Document\Fhm as FhmFhm;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * WorkflowComment
 * @MongoDB\Document(repositoryClass="Fhm\WorkflowBundle\Document\Repository\WorkflowCommentRepository")
 */
class WorkflowComment extends FhmFhm
{
    /**
     * @MongoDB\ReferenceOne(targetDocument="Fhm\WorkflowBundle\Document\WorkflowTask", nullable=true)
     */
    protected $task;

    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
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
}