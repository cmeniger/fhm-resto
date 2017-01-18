<?php
namespace Fhm\GalleryBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Fhm\FhmBundle\Entity\Fhm;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table()
 */
class GalleryAlbum extends Fhm
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
     * @ORM\OneToOne(targetEntity="Fhm\MediaBundle\Media")
     */
    protected $image;

    /**
     * @ORM\Column(type="boolean")
     */
    protected $add_global;

    /**
     * @ORM\Column(type="boolean")
     */
    protected $sort;

    /**
     * @ORM\OneToMany(targetEntity="Fhm\GalleryBundle\Gallery")
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
        $this->add_global   = false;
        $this->sort         = "date_start desc";
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
            $gallery->addAlbum($this);
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
            $gallery->addAlbum($this);
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
            $gallery->removeAlbum($this);
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
            $gallery->removeAlbum($this);
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
     * @ORM\PreRemove()
     */
    public function preRemove()
    {
        $this->resetGalleries();

        return parent::preRemove();
    }
}