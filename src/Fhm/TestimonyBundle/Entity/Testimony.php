<?php
namespace Fhm\TestimonyBundle\Entity;

use Fhm\FhmBundle\Entity\Fhm;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table()
 */
class Testimony extends Fhm
{
    /**
     * @ORM\OneToOne(targetEntity="Fhm\MediaBundle\Entity\Media", orphanRemoval=true)
     */
    protected $image;

    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Set image
     *
     * @param \Fhm\MediaBundle\Entity\Media $image
     *
     * @return self
     */
    public function setImage(\Fhm\MediaBundle\Entity\Media $image)
    {
        $this->image = $image;

        return $this;
    }

    /**
     * Get image
     *
     * @return \Fhm\MediaBundle\Entity\Media $image
     */
    public function getImage()
    {
        return $this->image;
    }
}