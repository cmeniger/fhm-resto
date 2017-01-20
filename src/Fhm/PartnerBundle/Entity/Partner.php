<?php
namespace Fhm\PartnerBundle\Entity;
use Doctrine\Common\Collections\ArrayCollection;
use Fhm\FhmBundle\Entity\Fhm;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table()
 */
class Partner extends Fhm
{
    /**
     * @ORM\Column(type="string", length=100)
     */
    protected $link;

    /**
     * @ORM\Column(type="string", length=100)
     */
    protected $content;

    /**
     * @ORM\OneToOne(targetEntity="Fhm\MediaBundle\Entity\Media", nullable=true)
     */
    protected $image;

    /**
     * @ORM\OneToMany(targetEntity="Fhm\PartnerBundle\Entity\PartnerGroup", nullable=true, cascade={"persist"})
     */
    protected $partnergroups;

    /**
     * @ORM\Column(type="integer")
     */
    protected $sort_partnergroup;

    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->partnergroups     = new ArrayCollection();
        $this->sort_partnergroup = 0;
    }

    /**
     * Set link
     *
     * @param string $link
     *
     * @return self
     */
    public function setLink($link)
    {
        $this->link = $link;

        return $this;
    }

    /**
     * Get link
     *
     * @return string $link
     */
    public function getLink()
    {
        return $this->link;
    }

    /**
     * @return mixed
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @param $content
     *
     * @return $this
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Set image
     *
     * @param \Fhm\MediaBundle\Entity\Media $media
     *
     * @return self
     */
    public function setImage($media)
    {
        $this->image = ($media instanceof \Fhm\MediaBundle\Entity\Media) ? $media : null;

        return $this;
    }

    /**
     * Get image
     *
     * @return \Fhm\MediaBundle\Entity\Media $media
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * Get partnergroups
     *
     * @return mixed
     */
    public function getPartnergroups()
    {
        return $this->partnergroups;
    }

    /**
     * Set partnergroups
     *
     * @param ArrayCollection $partnergroups
     *
     * @return $this
     */
    public function setPartnergroups(ArrayCollection $partnergroups)
    {
        $this->resetPartnergroups();
        foreach($partnergroups as $partnergroup)
        {
            $partnergroup->addPartner($this);
        }
        $this->partnergroups = $partnergroups;

        return $this;
    }

    /**
     * Add partnergroup
     *
     * @param \Fhm\PartnerBundle\Entity\PartnerGroup $partnergroup
     *
     * @return $this
     */
    public function addPartnergroup(\Fhm\PartnerBundle\Entity\PartnerGroup $partnergroup)
    {
        if(!$this->partnergroups->contains($partnergroup))
        {
            $this->partnergroups->add($partnergroup);
            $partnergroup->addPartner($this);
        }

        return $this;
    }

    /**
     * Remove partnergroup
     *
     * @param \Fhm\PartnerBundle\Entity\PartnerGroup $partnergroup
     *
     * @return $this
     */
    public function removePartnergroup(\Fhm\PartnerBundle\Entity\PartnerGroup $partnergroup)
    {
        if($this->partnergroups->contains($partnergroup))
        {
            $this->partnergroups->removeElement($partnergroup);
            $partnergroup->removePartner($this);
        }

        return $this;
    }

    /**
     * Reset partnergroups
     *
     * @return $this
     */
    public function resetPartnergroups()
    {
        foreach($this->partnergroups as $partnergroup)
        {
            $partnergroup->removePartner($this);
        }
        $this->partnergroups = new ArrayCollection();

        return $this;
    }

    /**
     * Sort update
     *
     * @return $this
     */
    public function sortUpdate()
    {
        $this->sort_partnergroup = $this->partnergroups->count();

        return parent::sortUpdate();
    }

    /**
     * @ORM\PreRemove()
     */
    public function preRemove()
    {
        $this->resetPartnergroups();

        return parent::preRemove();
    }
}