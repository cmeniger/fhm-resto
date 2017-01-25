<?php
namespace Fhm\EventBundle\Entity;
use Doctrine\Common\Collections\ArrayCollection;
use Fhm\FhmBundle\Entity\Fhm;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="Fhm\EventBundle\Entity\Repository\EventGroupRepository")
 * @ORM\Table()
 */
class EventGroup extends Fhm
{
    /**
     * @ORM\ManyToMany(targetEntity="Fhm\EventBundle\Entity\Event", orphanRemoval=true, cascade={"persist"}, inversedBy="eventgroups")
     */
    protected $events;

    /**
     * @ORM\Column(type="boolean")
     */
    protected $add_global;

    /**
     * @ORM\Column(type="integer")
     */
    protected $sort_event;

    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->events     = new ArrayCollection();
        $this->add_global = false;
        $this->sort       = "date_start desc";
        $this->sort_event = 0;
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
     * Get events
     *
     * @return mixed
     */
    public function getEvents()
    {
        return $this->events;
    }

    /**
     * Set events
     *
     * @param ArrayCollection $events
     *
     * @return $this
     */
    public function setEvents(ArrayCollection $events)
    {
        foreach($events as $event)
        {
            $event->addEventgroup($this);
        }
        $this->events = $events;

        return $this;
    }

    /**
     * Add event
     *
     * @param \Fhm\EventBundle\Entity\Event $event
     *
     * @return $this
     */
    public function addEvent(\Fhm\EventBundle\Entity\Event $event)
    {
        if(!$this->events->contains($event))
        {
            $this->events->add($event);
            $event->addEventgroup($this);
        }

        return $this;
    }

    /**
     * Remove event
     *
     * @param \Fhm\EventBundle\Entity\Event $event
     *
     * @return $this
     */
    public function removeEvent(\Fhm\EventBundle\Entity\Event $event)
    {
        if($this->events->contains($event))
        {
            $this->events->removeElement($event);
            $event->removeEventgroup($this);
        }

        return $this;
    }

    /**
     * Reset events
     *
     * @return $this
     */
    public function resetEvents()
    {
        foreach($this->events as $event)
        {
            $event->removeEventgroup($this);
        }
        $this->events = new ArrayCollection();

        return $this;
    }

    /**
     * Sort update
     *
     * @return $this
     */
    public function sortUpdate()
    {
        $this->sort_event = $this->events->count();

        return parent::sortUpdate();
    }

    /**
     * @ORM\PreRemove()
     */
    public function preRemove()
    {
        $this->resetEvents();

        return parent::preRemove();
    }
}