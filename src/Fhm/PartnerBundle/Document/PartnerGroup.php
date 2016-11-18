<?php
namespace Fhm\PartnerBundle\Document;

use Fhm\FhmBundle\Document\Fhm as FhmFhm;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * PartnerGroup
 * @MongoDB\Document(repositoryClass="Fhm\PartnerBundle\Repository\PartnerGroupRepository")
 */
class PartnerGroup extends FhmFhm
{
    /**
     * @MongoDB\ReferenceMany(targetDocument="Fhm\PartnerBundle\Document\Partner", nullable=true, cascade={"persist"})
     */
    protected $partners;

    /**
     * @MongoDB\Field(type="boolean")
     */
    protected $add_global;

    /**
     * @MongoDB\Field(type="int")
     */
    protected $sort_partner;

    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->partners     = new ArrayCollection();
        $this->add_global   = false;
        $this->sort_partner = 0;
    }

    /**
     * Set add_global
     *
     * @param boolean $add_global
     *
     * @return self
     */
    public function setAddGlobal($add_global)
    {
        $this->add_global = $add_global;

        return $this;
    }

    /**
     * Get add_global
     *
     * @return boolean $add_global
     */
    public function getAddGlobal()
    {
        return $this->add_global;
    }

    /**
     * Get partners
     *
     * @return mixed
     */
    public function getPartners()
    {
        return $this->partners;
    }

    /**
     * Set partners
     *
     * @param ArrayCollection $partners
     *
     * @return $this
     */
    public function setPartners(ArrayCollection $partners)
    {
        $this->resetPartners();
        foreach($partners as $partner)
        {
            $partner->addPartnergroup($this);
        }
        $this->partners = $partners;

        return $this;
    }

    /**
     * Add partner
     *
     * @param \Fhm\PartnerBundle\Document\Partner $partner
     *
     * @return $this
     */
    public function addPartner(\Fhm\PartnerBundle\Document\Partner $partner)
    {
        if(!$this->partners->contains($partner))
        {
            $this->partners->add($partner);
            $partner->addPartnergroup($this);
        }

        return $this;
    }

    /**
     * Remove partner
     *
     * @param \Fhm\PartnerBundle\Document\Partner $partner
     *
     * @return $this
     */
    public function removePartner(\Fhm\PartnerBundle\Document\Partner $partner)
    {
        if($this->partners->contains($partner))
        {
            $this->partners->removeElement($partner);
            $partner->removePartnergroup($this);
        }

        return $this;
    }

    /**
     * Reset partners
     *
     * @return $this
     */
    public function resetPartners()
    {
        foreach($this->partners as $partner)
        {
            $partner->removePartner($this);
        }
        $this->partners = new ArrayCollection();

        return $this;
    }

    /**
     * Sort update
     *
     * @return $this
     */
    public function sortUpdate()
    {
        $this->sort_partner = $this->partners->count();

        return parent::sortUpdate();
    }

    /**
     * @MongoDB\PreRemove()
     */
    public function preRemove()
    {
        $this->resetPartners();

        return parent::preRemove();
    }
}