<?php
namespace Fhm\WorkflowBundle\Entity;
use Fhm\FhmBundle\Entity\Fhm;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table()
 */
class WorkflowLog extends Fhm
{
    /**
     * @ORM\ManyToOne(targetEntity="Fhm\WorkflowBundle\Entity\WorkflowTask", inversedBy="logs")
     */
    protected $task;

    /**
     * @ORM\Column(type="integer")
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