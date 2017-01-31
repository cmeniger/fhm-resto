<?php
namespace Fhm\SliderBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Fhm\FhmBundle\Entity\Fhm;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="Fhm\SliderBundle\Entity\Repository\SliderRepository")
 * @ORM\Table()
 */
class Slider extends Fhm
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
    protected $resume;

    /**
     * @ORM\Column(type="string", length=100)
     */
    protected $content;

    /**
     * @ORM\OneToOne(targetEntity="Fhm\MediaBundle\Entity\Media", orphanRemoval=true)
     */
    protected $image;

    /**
     * @ORM\Column(type="boolean")
     */
    protected $add_global;

    /**
     * @ORM\Column(type="string", length=100)
     */
    protected $sort;

    /**
     * @ORM\ManyToMany(targetEntity="SliderItem", cascade={"persist"})
     */
    protected $items;

    /**
     * @ORM\Column(type="integer")
     */
    protected $sort_item;

    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->items = new ArrayCollection();
        $this->add_global = false;
        $this->sort = "date_start desc";
        $this->sort_item = 0;
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
        foreach ($items as $item) {
            $item->addSlider($this);
        }
        $this->items = $items;

        return $this;
    }

    /**
     * Add item
     *
     * @param \Fhm\SliderBundle\Entity\SliderItem $item
     *
     * @return $this
     */
    public function addItem(\Fhm\SliderBundle\Entity\SliderItem $item)
    {
        if (!$this->items->contains($item)) {
            $this->items->add($item);
            $item->addSlider($this);
        }

        return $this;
    }

    /**
     * Remove item
     *
     * @param \Fhm\SliderBundle\Entity\SliderItem item
     *
     * @return $this
     */
    public function removeItem(\Fhm\SliderBundle\Entity\SliderItem $item)
    {
        if ($this->items->contains($item)) {
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
        foreach ($this->items as $item) {
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
    }

    /**
     * @ORM\PreRemove()
     */
    public function preRemove()
    {
        $this->resetItems();
    }
}