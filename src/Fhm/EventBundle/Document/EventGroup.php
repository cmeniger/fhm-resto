<?php
namespace Fhm\EventBundle\Document;

use Fhm\FhmBundle\Document\Fhm as FhmFhm;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * EventGroup
 * @MongoDB\Document(repositoryClass="Fhm\EventBundle\Repository\EventGroupRepository")
 */
class EventGroup extends FhmFhm
{
    /**
     * @MongoDB\ReferenceMany(targetDocument="Fhm\EventBundle\Document\Event", nullable=true, cascade={"persist"})
     */
    protected $events;

    /**
     * @MongoDB\Field(type="boolean")
     */
    protected $add_global;

    /**
     * @MongoDB\Field(type="int")
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
     * @param \Fhm\EventBundle\Document\Event $event
     *
     * @return $this
     */
    public function addEvent(\Fhm\EventBundle\Document\Event $event)
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
     * @param \Fhm\EventBundle\Document\Event $event
     *
     * @return $this
     */
    public function removeEvent(\Fhm\EventBundle\Document\Event $event)
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
     * @MongoDB\PreRemove()
     */
    public function preRemove()
    {
        $this->resetEvents();

        return parent::preRemove();
    }
}