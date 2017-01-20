<?php
namespace Fhm\WorkflowBundle\Entity;
use Fhm\FhmBundle\Entity\Fhm;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table()
 */
class WorkflowStep extends Fhm
{
    /**
     * @ORM\Column(type="string", length=100)
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