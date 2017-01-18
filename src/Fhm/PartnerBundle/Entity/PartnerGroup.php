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
class PartnerGroup extends Fhm
{
    /**
     * @ORM\OneToMany(targetDocument="Fhm\PartnerBundle\Entity\Partner", cascade={"persist"})
     */
    protected $partners;

    /**
     * @ORM\Column(type="boolean")
     */
    protected $add_global;

    /**
     * @ORM\Column(type="integer")
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
     * @param \Fhm\PartnerBundle\Entity\Partner $partner
     *
     * @return $this
     */
    public function addPartner(\Fhm\PartnerBundle\Entity\Partner $partner)
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
     * @param \Fhm\PartnerBundle\Entity\Partner $partner
     *
     * @return $this
     */
    public function removePartner(\Fhm\PartnerBundle\Entity\Partner $partner)
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
     * @ORM\PreRemove()
     */
    public function preRemove()
    {
        $this->resetPartners();

        return parent::preRemove();
    }
}