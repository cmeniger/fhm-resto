<?php
namespace Fhm\TestimonyBundle\Document;

use Fhm\FhmBundle\Document\Fhm as FhmFhm;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Testimony
 * @MongoDB\Document(repositoryClass="Fhm\TestimonyBundle\Repository\TestimonyRepository")
 */
class Testimony extends FhmFhm
{
    /**
     * @MongoDB\ReferenceOne(targetDocument="Fhm\MediaBundle\Document\Media", nullable=true)
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
     * @param \Fhm\MediaBundle\Document\Media $image
     *
     * @return self
     */
    public function setImage(\Fhm\MediaBundle\Document\Media $image)
    {
        $this->image = $image;

        return $this;
    }

    /**
     * Get image
     *
     * @return \Fhm\MediaBundle\Document\Media $image
     */
    public function getImage()
    {
        return $this->image;
    }
}