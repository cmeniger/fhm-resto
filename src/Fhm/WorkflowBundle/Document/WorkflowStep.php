<?php
namespace Fhm\WorkflowBundle\Document;

use Fhm\FhmBundle\Document\Fhm as FhmFhm;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * WorkflowStep
 * @MongoDB\Document(repositoryClass="Fhm\WorkflowBundle\Document\Repository\WorkflowStepRepository")
 */
class WorkflowStep extends FhmFhm
{
    /**
     * @MongoDB\Field(type="string")
     */
    protected $color;

    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->color = null;
    }

    /**
     * Get color
     *
     * @return string $color
     */
    public function getColor()
    {
        return $this->color;
    }

    /**
     * Set color
     *
     * @param string $color
     *
     * @return self
     */
    public function setColor($color)
    {
        $this->color = $color;

        return $this;
    }
}