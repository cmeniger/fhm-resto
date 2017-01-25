<?php
namespace Fhm\WorkflowBundle\Entity;
use Fhm\FhmBundle\Entity\Fhm;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table()
 */
class WorkflowComment extends Fhm
{
    /**
     * @ORM\ManyToOne(targetEntity="Fhm\WorkflowBundle\Entity\WorkflowTask", inversedBy="comments")
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
}