<?php
namespace Fhm\WorkflowBundle\Document;

use Fhm\FhmBundle\Document\Fhm as FhmFhm;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * WorkflowLog
 * @MongoDB\Document(repositoryClass="Fhm\WorkflowBundle\Document\Repository\WorkflowLogRepository")
 */
class WorkflowLog extends FhmFhm
{
    /**
     * @MongoDB\ReferenceOne(targetDocument="Fhm\WorkflowBundle\Document\WorkflowTask", nullable=true)
     */
    protected $task;

    /**
     * @MongoDB\Field(type="int")
     * @Assert\Range(min = 0)
     */
    protected $type;

    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->type = 0;
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
     * Get type
     *
     * @return int $type
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set type
     *
     * @param int $type
     *
     * @return self
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }
}