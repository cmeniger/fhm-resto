<?php
namespace Fhm\MediaBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Fhm\FhmBundle\Entity\Fhm;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="Fhm\MediaBundle\Entity\Repository\MediaTagRepository")
 * @ORM\Table()
 */
class MediaTag extends Fhm
{
    /**
     * @ORM\ManyToOne(targetEntity="MediaTag" , cascade={"persist"})
     */
    protected $parent;

    /**
     * @ORM\ManyToMany(targetEntity="Media", inversedBy="tags")
     */
    protected $medias;
    /**
     * @ORM\Column(type="string", length=100)
     */
    protected $route;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    protected $color;

    /**
     * @ORM\Column(type="boolean")
     */
    protected $private;

    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->parent = null;
        $this->route = '';
        $this->color = null;
        $this->private = false;
        $this->medias = new ArrayCollection();
    }

    /**
     * Get parent
     *
     * @return \Fhm\MediaBundle\Entity\MediaTag $parent
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * Set parent
     *
     * @param \Fhm\MediaBundle\Entity\MediaTag $parent
     *
     * @return self
     */
    public function setParent($parent)
    {
        $this->parent = ($parent instanceof \Fhm\MediaBundle\Entity\MediaTag) ? $parent : null;

        return $this;
    }

    /**
     * Get route
     *
     * @return string $route
     */
    public function getRoute()
    {
        return $this->route;
    }

    /**
     * Get route parent
     *
     * @return string $route
     */
    public function getRouteParent()
    {
        return $this->parent ? $this->parent->getRouteParent().' > '.$this->name : $this->name;
    }

    /**
     * Get route array
     *
     * @return array $route
     */
    public function getRouteArray()
    {
        return explode(' > ', $this->route);
    }

    /**
     * Get route object
     *
     * @return array $route
     */
    public function getRouteObject()
    {
        return $this->parent ? array_merge($this->parent->getRouteObject(), array($this)) : array($this);
    }

    /**
     * Set route
     *
     * @param string $route
     *
     * @return self
     */
    public function setRoute($route)
    {
        $this->route = $route;

        return $this;
    }

    /**
     * Get color
     *
     * @return string $color
     */
    public function getColor()
    {
        return $this->color;
    }

    /**
     * Set color
     *
     * @param string $color
     *
     * @return self
     */
    public function setColor($color)
    {
        $this->color = $color;

        return $this;
    }

    /**
     * Set private
     *
     * @param boolean $private
     *
     * @return self
     */
    public function setPrivate($private)
    {
        $this->private = $private;

        return $this;
    }

    /**
     * Get private
     *
     * @return boolean $private
     */
    public function getPrivate()
    {
        return $this->private;
    }

    /**
     * @ORM\PrePersist()
     */
    public function prePersist()
    {
        $this->route = $this->getRouteParent();

        return $this;
    }

    /**
     * @ORM\PreUpdate()
     */
    public function preUpdate()
    {
        $this->route = $this->getRouteParent();

        return $this;
    }

    /**
     * @return mixed
     */
    public function getMedias()
    {
        return $this->medias;
    }

    /**
     * @param mixed $medias
     * @return MediaTag
     */
    public function setMedias($medias)
    {
        $this->medias = $medias;

        return $this;
    }

    /**
     * @param Media $media
     * @return $this
     */
    public function addMedia(Media $media)
    {
        if (!$this->medias->contains($media)) {
            $this->medias->add($media);
            $media->addTag($this);
        }

        return $this;
    }
}