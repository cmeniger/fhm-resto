<?php
namespace Fhm\NewsBundle\Document;

use Fhm\FhmBundle\Document\Fhm as FhmFhm;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * NewsTag
 * @MongoDB\Document(repositoryClass="Fhm\NewsBundle\Repository\NewsTagRepository")
 */
class NewsTag extends FhmFhm
{
    /**
     * @MongoDB\ReferenceMany(targetDocument="Fhm\NewsBundle\Document\News", nullable=true, cascade={"all"})
     */
    protected $primaries;

    /**
     * @MongoDB\ReferenceMany(targetDocument="Fhm\NewsBundle\Document\News", nullable=true, cascade={"all"})
     */
    protected $secondaries;

    /**
     * @MongoDB\Field(type="int")
     */
    protected $sort_primary;

    /**
     * @MongoDB\Field(type="int")
     */
    protected $sort_secondary;

    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->primaries      = new ArrayCollection();
        $this->secondaries    = new ArrayCollection();
        $this->sort_primary   = 0;
        $this->sort_secondary = 0;
    }

    /**
     * Get primaries
     *
     * @return mixed
     */
    public function getPrimaries()
    {
        return $this->primaries;
    }

    /**
     * Set primaries
     *
     * @param ArrayCollection $primaries
     *
     * @return $this
     */
    public function setPrimaries($primaries)
    {
        $this->resetPrimaries();
        foreach($primaries as $news)
        {
            $news->setTag($this);
        }
        $this->primaries = $primaries;

        return $this;
    }

    /**
     * Add primaries
     *
     * @param \Fhm\NewsBundle\Document\News $news
     *
     * @return $this
     */
    public function addPrimary(\Fhm\NewsBundle\Document\News $news)
    {
        if(!$this->primaries->contains($news))
        {
            $this->primaries->add($news);
        }

        return $this;
    }

    /**
     * Remove primaries
     *
     * @param \Fhm\NewsBundle\Document\News $news
     *
     * @return $this
     */
    public function removePrimary(\Fhm\NewsBundle\Document\News $news)
    {
        if($this->primaries->contains($news))
        {
            $this->primaries->removeElement($news);
        }

        return $this;
    }

    /**
     * Reset primaries
     *
     * @return $this
     */
    public function resetPrimaries()
    {
        foreach($this->primaries as $news)
        {
            $news->setTag(null);
        }
        $this->primaries = new ArrayCollection();

        return $this;
    }

    /**
     * Get secondaries
     *
     * @return mixed
     */
    public function getSecondaries()
    {
        return $this->secondaries;
    }

    /**
     * Set secondaries
     *
     * @param ArrayCollection $secondaries
     *
     * @return $this
     */
    public function setSecondaries($secondaries)
    {
        $this->resetSecondaries();
        $this->secondaries = $secondaries;

        return $this;
    }

    /**
     * Add secondaries
     *
     * @param \Fhm\NewsBundle\Document\News $news
     *
     * @return $this
     */
    public function addSecondary(\Fhm\NewsBundle\Document\News $news)
    {
        if(!$this->secondaries->contains($news))
        {
            $this->secondaries->add($news);
        }

        return $this;
    }

    /**
     * Remove secondaries
     *
     * @param \Fhm\NewsBundle\Document\News $news
     *
     * @return $this
     */
    public function removeSecondary(\Fhm\NewsBundle\Document\News $news)
    {
        if($this->secondaries->contains($news))
        {
            $this->secondaries->removeElement($news);
        }

        return $this;
    }

    /**
     * Reset secondaries
     *
     * @return $this
     */
    public function resetSecondaries()
    {
        foreach($this->secondaries as $news)
        {
            $news->removeTag($this);
        }
        $this->secondaries = new ArrayCollection();

        return $this;
    }

    /**
     * Sort update
     *
     * @return $this
     */
    public function sortUpdate()
    {
        $this->sort_primary   = $this->primaries->count();
        $this->sort_secondary = $this->secondaries->count();

        return parent::sortUpdate();
    }

    /**
     * @MongoDB\PreRemove()
     */
    public function preRemove()
    {
        $this->resetPrimaries();
        $this->resetSecondaries();

        return parent::preRemove();
    }
}