<?php

namespace Fhm\GalleryBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Fhm\FhmBundle\Entity\Fhm;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table()
 */
class GalleryVideo extends Fhm
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
     * @ORM\Column(type="string", length=100)
     */
    protected $video;

    /**
     * @ORM\Column(type="string", length=100)
     */
    protected $link;

    /**
     * @ORM\OneToMany(targetEntity="Gallery")
     */
    protected $galleries;

    /**
     * @ORM\Column(type="integer")
     */
    protected $sort_gallery;

    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->galleries    = new ArrayCollection();
        $this->sort_gallery = 0;
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
     * Set video
     *
     * @param string $video
     *
     * @return self
     */
    public function setVideo($video)
    {
        $this->video = $video;

        return $this;
    }

    /**
     * Get video
     *
     * @return string $video
     */
    public function getVideo()
    {
        return $this->video;
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
     * Set galleries
     *
     * @param ArrayCollection $galleries
     *
     * @return $this
     */
    public function setGalleries(ArrayCollection $galleries)
    {
        $this->resetGalleries();
        foreach($galleries as $gallery)
        {
            $gallery->addVideo($this);
        }
        $this->galleries = $galleries;

        return $this;
    }

    /**
     * Get galleries
     *
     * @return mixed
     */
    public function getGalleries()
    {
        return $this->galleries;
    }

    /**
     * Add gallery
     *
     * @param \Fhm\GalleryBundle\Entity\Gallery $gallery
     *
     * @return $this
     */
    public function addGallery(\Fhm\GalleryBundle\Entity\Gallery $gallery)
    {
        if(!$this->galleries->contains($gallery))
        {
            $this->galleries->add($gallery);
            $gallery->addVideo($this);
        }

        return $this;
    }

    /**
     * Remove gallery
     *
     * @param \Fhm\GalleryBundle\Entity\Gallery $gallery
     *
     * @return $this
     */
    public function removeGallery(\Fhm\GalleryBundle\Entity\Gallery $gallery)
    {
        if($this->galleries->contains($gallery))
        {
            $this->galleries->removeElement($gallery);
            $gallery->removeVideo($this);
        }

        return $this;
    }

    /**
     * Reset galleries
     *
     * @return $this
     */
    public function resetGalleries()
    {
        foreach($this->galleries as $gallery)
        {
            $gallery->removeVideo($this);
        }
        $this->galleries = new ArrayCollection();

        return $this;
    }

    /**
     * Sort update
     *
     * @return $this
     */
    public function sortUpdate()
    {
        $this->sort_gallery = $this->galleries->count();

        return parent::sortUpdate();
    }

    /**
     * @MongoDB\PreRemove()
     */
    public function preRemove()
    {
        $this->resetGalleries();

        return parent::preRemove();
    }
}