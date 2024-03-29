<?php
namespace Fhm\EventBundle\Document;

use Fhm\FhmBundle\Document\Fhm as FhmFhm;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Event
 * @MongoDB\Document(repositoryClass="Fhm\EventBundle\Document\Repository\EventRepository")
 */
class Event extends FhmFhm
{
    /**
     * @MongoDB\Field(type="string")
     */
    protected $title;

    /**
     * @MongoDB\Field(type="string")
     */
    protected $subtitle;

    /**
     * @MongoDB\Field(type="string")
     */
    protected $resume;

    /**
     * @MongoDB\Field(type="string")
     */
    protected $content;

    /**
     * @MongoDB\Field(type="date")
     * @Assert\Type("\DateTime")
     */
    protected $date_start;

    /**
     * @MongoDB\Field(type="date")
     * @Assert\Type("\DateTime")
     */
    protected $date_end;

    /**
     * @MongoDB\ReferenceOne(targetDocument="Fhm\MediaBundle\Document\Media", nullable=true)
     */
    protected $image;

    /**
     * @MongoDB\ReferenceMany(targetDocument="Fhm\EventBundle\Document\EventGroup", nullable=true, cascade={"persist"})
     */
    protected $eventgroups;

    /**
     * @MongoDB\Field(type="int")
     */
    protected $sort_eventgroup;

    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->eventgroups = new ArrayCollection();
        $this->date_start = null;
        $this->date_end = null;
        $this->sort_eventgroup = 0;
    }

    /**
     * Set title
     *
     * @param string $title
     *
     * @return self
     */
    public function setTitle($title)
    {
        $this->title = $title;
        $this->name = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string $title
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set subtitle
     *
     * @param string $subtitle
     *
     * @return self
     */
    public function setSubtitle($subtitle)
    {
        $this->subtitle = $subtitle;

        return $this;
    }

    /**
     * Get subtitle
     *
     * @return string $subtitle
     */
    public function getSubtitle()
    {
        return $this->subtitle;
    }

    /**
     * Set resume
     *
     * @param string $resume
     *
     * @return self
     */
    public function setResume($resume)
    {
        $this->resume = $resume;

        return $this;
    }

    /**
     * Get resume
     *
     * @return string $resume
     */
    public function getResume()
    {
        return $this->resume;
    }

    /**
     * Set content
     *
     * @param string $content
     *
     * @return self
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Get content
     *
     * @return string $content
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set date start
     *
     * @param \datetime $dateStart
     *
     * @return self
     */
    public function setDateStart($dateStart)
    {
        $this->date_start = $dateStart;

        return $this;
    }

    /**
     * Get date start
     *
     * @return \datetime $dateStart
     */
    public function getDateStart()
    {
        return $this->date_start;
    }

    /**
     * Set date end
     *
     * @param \datetime $dateEnd
     *
     * @return self
     */
    public function setDateEnd($dateEnd)
    {
        $this->date_end = $dateEnd;

        return $this;
    }

    /**
     * Get date end
     *
     * @return \datetime $dateEnd
     */
    public function getDateEnd()
    {
        return $this->date_end;
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
     * Get eventgroups
     *
     * @return mixed
     */
    public function getEventgroups()
    {
        return $this->eventgroups;
    }

    /**
     * Set eventgroups
     *
     * @param ArrayCollection $eventgroups
     *
     * @return $this
     */
    public function setEventgroups(ArrayCollection $eventgroups)
    {
        foreach ($eventgroups as $eventgroup) {
            $eventgroup->addEvent($this);
        }
        $this->eventgroups = $eventgroups;

        return $this;
    }

    /**
     * Add eventgroup
     *
     * @param \Fhm\EventBundle\Document\EventGroup $eventgroup
     *
     * @return $this
     */
    public function addEventgroup(\Fhm\EventBundle\Document\EventGroup $eventgroup)
    {
        if (!$this->eventgroups->contains($eventgroup)) {
            $this->eventgroups->add($eventgroup);
            $eventgroup->addEvent($this);
        }

        return $this;
    }

    /**
     * Remove eventgroup
     *
     * @param \Fhm\EventBundle\Document\EventGroup $eventgroup
     *
     * @return $this
     */
    public function removeEventgroup(\Fhm\EventBundle\Document\EventGroup $eventgroup)
    {
        if ($this->eventgroups->contains($eventgroup)) {
            $this->eventgroups->removeElement($eventgroup);
            $eventgroup->removeEvent($this);
        }

        return $this;
    }

    /**
     * Reset eventgroups
     *
     * @return $this
     */
    public function resetEventgroups()
    {
        foreach ($this->eventgroups as $eventgroup) {
            $eventgroup->removeEvent($this);
        }
        $this->eventgroups = new ArrayCollection();

        return $this;
    }

    /**
     * Sort update
     *
     * @return $this
     */
    public function sortUpdate()
    {
        $this->sort_eventgroup = $this->eventgroups->count();

        return parent::sortUpdate();
    }

    /**
     * @MongoDB\PreRemove()
     */
    public function preRemove()
    {
        $this->resetEventgroups();

        return parent::preRemove();
    }
}