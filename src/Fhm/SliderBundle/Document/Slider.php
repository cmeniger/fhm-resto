<?php
namespace Fhm\SliderBundle\Document;

use Fhm\FhmBundle\Document\Fhm as FhmFhm;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Slider
 * @MongoDB\Document(repositoryClass="Fhm\SliderBundle\Repository\SliderRepository")
 */
class Slider extends FhmFhm
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
     * @MongoDB\ReferenceOne(targetDocument="Fhm\MediaBundle\Document\Media", nullable=true)
     */
    protected $image;

    /**
     * @MongoDB\Field(type="boolean")
     */
    protected $add_global;

    /**
     * @MongoDB\Field(type="string")
     */
    protected $sort;

    /**
     * @MongoDB\ReferenceMany(targetDocument="Fhm\SliderBundle\Document\SliderItem", nullable=true, cascade={"persist"})
     */
    protected $items;

    /**
     * @MongoDB\Field(type="int")
     */
    protected $sort_item;

    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->items      = new ArrayCollection();
        $this->add_global = false;
        $this->sort       = "date_start desc";
        $this->sort_item  = 0;
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
        $this->name  = $title;

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
     * Set sort
     *
     * @param string $sort
     *
     * @return self
     */
    public function setSort($sort)
    {
        $this->sort = $sort;

        return $this;
    }

    /**
     * Get sort
     *
     * @return string $sort
     */
    public function getSort()
    {
        return $this->sort;
    }

    /**
     * Get sort field
     *
     * @return string $sort
     */
    public function getSortField()
    {
        $sort = explode(" ", $this->sort);

        return $sort[0];
    }

    /**
     * Get sort order
     *
     * @return string $sort
     */
    public function getSortOrder()
    {
        $sort = explode(" ", $this->sort);

        return isset($sort[1]) ? $sort[1] : 'asc';
    }

    /**
     * Get items
     *
     * @return mixed
     */
    public function getItems()
    {
        return $this->items;
    }

    /**
     * Set items
     *
     * @param ArrayCollection $items
     *
     * @return $this
     */
    public function setItems(ArrayCollection $items)
    {
        $this->resetItems();
        foreach($items as $item)
        {
            $item->addSlider($this);
        }
        $this->items = $items;

        return $this;
    }

    /**
     * Add item
     *
     * @param \Fhm\SliderBundle\Document\SliderItem $item
     *
     * @return $this
     */
    public function addItem(\Fhm\SliderBundle\Document\SliderItem $item)
    {
        if(!$this->items->contains($item))
        {
            $this->items->add($item);
            $item->addSlider($this);
        }

        return $this;
    }

    /**
     * Remove item
     *
     * @param \Fhm\SliderBundle\Document\SliderItem item
     *
     * @return $this
     */
    public function removeItem(\Fhm\SliderBundle\Document\SliderItem $item)
    {
        if($this->items->contains($item))
        {
            $this->items->removeElement($item);
            $item->removeSlider($this);
        }

        return $this;
    }

    /**
     * Reset items
     *
     * @return $this
     */
    public function resetItems()
    {
        foreach($this->items as $item)
        {
            $item->removeSlider($this);
        }
        $this->items = new ArrayCollection();

        return $this;
    }

    /**
     * Sort update
     *
     * @return $this
     */
    public function sortUpdate()
    {
        $this->sort_item = $this->items->count();

        return parent::sortUpdate();
    }

    /**
     * @MongoDB\PreRemove()
     */
    public function preRemove()
    {
        $this->resetItems();

        return parent::preRemove();
    }
}