<?php
namespace Fhm\PartnerBundle\Document;

use Fhm\FhmBundle\Document\Fhm as FhmFhm;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Partner
 * @MongoDB\Document(repositoryClass="Fhm\PartnerBundle\Repository\PartnerRepository")
 */
class Partner extends FhmFhm
{
    /**
     * @MongoDB\Field(type="string")
     */
    protected $link;

    /**
     * @MongoDB\Field(type="string")
     */
    protected $content;

    /**
     * @MongoDB\ReferenceOne(targetDocument="Fhm\MediaBundle\Document\Media", nullable=true)
     */
    protected $image;

    /**
     * @MongoDB\ReferenceMany(targetDocument="Fhm\PartnerBundle\Document\PartnerGroup", nullable=true, cascade={"persist"})
     */
    protected $partnergroups;

    /**
     * @MongoDB\Field(type="int")
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
     * @param \Fhm\MediaBundle\Document\Media $media
     *
     * @return self
     */
    public function setImage($media)
    {
        $this->image = ($media instanceof \Fhm\MediaBundle\Document\Media) ? $media : null;

        return $this;
    }

    /**
     * Get image
     *
     * @return \Fhm\MediaBundle\Document\Media $media
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
     * @param \Fhm\PartnerBundle\Document\PartnerGroup $partnergroup
     *
     * @return $this
     */
    public function addPartnergroup(\Fhm\PartnerBundle\Document\PartnerGroup $partnergroup)
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
     * @param \Fhm\PartnerBundle\Document\PartnerGroup $partnergroup
     *
     * @return $this
     */
    public function removePartnergroup(\Fhm\PartnerBundle\Document\PartnerGroup $partnergroup)
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
     * @MongoDB\PreRemove()
     */
    public function preRemove()
    {
        $this->resetPartnergroups();

        return parent::preRemove();
    }
}