<?php
namespace Fhm\SliderBundle\Document;

use Fhm\FhmBundle\Document\Fhm as FhmFhm;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * SliderItem
 * @MongoDB\Document(repositoryClass="Fhm\SliderBundle\Document\Repository\SliderItemRepository")
 */
class SliderItem extends FhmFhm
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
    protected $content;

    /**
     * @MongoDB\Field(type="string")
     */
    protected $link;

    /**
     * @MongoDB\Field(type="boolean")
     */
    protected $caption;

    /**
     * @MongoDB\ReferenceOne(targetDocument="Fhm\MediaBundle\Document\Media", nullable=true)
     */
    protected $image;

    /**
     * @MongoDB\ReferenceMany(targetDocument="Fhm\SliderBundle\Document\Slider", nullable=true, cascade={"persist"})
     */
    protected $sliders;

    /**
     * @MongoDB\Field(type="int")
     */
    protected $sort_slider;

    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->sliders     = new ArrayCollection();
        $this->caption         = true;
        $this->sort_slider = 0;
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
     * Get caption
     *
     * @return bool
     */
    public function getCaption()
    {
        return $this->caption;
    }

    /**
     * Set caption
     *
     * @param $caption
     *
     * @return $this
     */
    public function setCaption($caption)
    {
        $this->caption = $caption;

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
     * Set link
     *
     * @param $link
     *
     * @return $this
     */
    public function setLink($link)
    {
        $this->link = $link;

        return $this;
    }

    /**
     * Get link
     *
     * @return mixed
     */
    public function getLink()
    {
        return $this->link;
    }

    /**
     * Set sliders
     *
     * @param ArrayCollection $sliders
     *
     * @return $this
     */
    public function setSliders(ArrayCollection $sliders)
    {
        $this->resetSliders();
        foreach($sliders as $slider)
        {
            $slider->addItem($this);
        }
        $this->sliders = $sliders;

        return $this;
    }

    /**
     * Get sliders
     *
     * @return mixed
     */
    public function getSliders()
    {
        return $this->sliders;
    }

    /**
     * Add slider
     *
     * @param \Fhm\SliderBundle\Document\Slider $slider
     *
     * @return $this
     */
    public function addSlider(\Fhm\SliderBundle\Document\Slider $slider)
    {
        if(!$this->sliders->contains($slider))
        {
            $this->sliders->add($slider);
            $slider->addItem($this);
        }

        return $this;
    }

    /**
     * Remove slider
     *
     * @param \Fhm\SliderBundle\Document\Slider $slider
     *
     * @return $this
     */
    public function removeSlider(\Fhm\SliderBundle\Document\Slider $slider)
    {
        if($this->sliders->contains($slider))
        {
            $this->sliders->removeElement($slider);
            $slider->removeItem($this);
        }

        return $this;
    }

    /**
     * Reset sliders
     *
     * @return $this
     */
    public function resetSliders()
    {
        foreach($this->sliders as $slider)
        {
            $slider->removeItem($this);
        }
        $this->sliders = new ArrayCollection();

        return $this;
    }

    /**
     * Sort update
     *
     * @return $this
     */
    public function sortUpdate()
    {
        $this->sort_slider = $this->sliders->count();

        return parent::sortUpdate();
    }

    /**
     * @MongoDB\PreRemove()
     */
    public function preRemove()
    {
        $this->resetSliders();

        return parent::preRemove();
    }
}