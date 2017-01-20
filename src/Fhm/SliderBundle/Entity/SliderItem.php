<?php
namespace Fhm\SliderBundle\Entity;
use Doctrine\Common\Collections\ArrayCollection;
use Fhm\FhmBundle\Entity\Fhm;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table()
 */
class SliderItem extends Fhm
{
    /**
     * @ORM\Column(type="string", length=100)
     */
    protected $title;

    /**
     * @ORM\Column(type="string", length=100)
     */
    protected $subtitle;

    /**
     * @ORM\Column(type="string", length=100)
     */
    protected $content;

    /**
     * @ORM\OneToOne(targetEntity="Fhm\MediaBundle\Entity\Media", nullable=true)
     */
    protected $image;

    /**
     * @ORM\Column(type="string", length=100)
     */
    protected $link;

    /**
     * @ORM\OneToMany(targetEntity="Fhm\SliderBundle\Entity\Slider", nullable=true, cascade={"persist"})
     */
    protected $sliders;

    /**
     * @ORM\Column(type="integer")
     */
    protected $sort_slider;

    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->sliders     = new ArrayCollection();
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
     * @param \Fhm\SliderBundle\Entity\Slider $slider
     *
     * @return $this
     */
    public function addSlider(\Fhm\SliderBundle\Entity\Slider $slider)
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
     * @param \Fhm\SliderBundle\Entity\Slider $slider
     *
     * @return $this
     */
    public function removeSlider(\Fhm\SliderBundle\Entity\Slider $slider)
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
     * @ORM\PreRemove()
     */
    public function preRemove()
    {
        $this->resetSliders();

        return parent::preRemove();
    }
}