<?php
namespace Project\ProductBundle\Document;

use Fhm\FhmBundle\Document\Fhm as FhmFhm;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * ProductIngredient
 * @MongoDB\Document(repositoryClass="Project\ProductBundle\Document\Repository\ProductIngredientRepository")
 */
class ProductIngredient extends FhmFhm
{
    /**
     * @MongoDB\ReferenceOne(targetDocument="Project\ProductBundle\Document\ProductIngredient", nullable=true)
     */
    protected $parent;

    /**
     * @MongoDB\Field(type="string")
     */
    protected $route;

//    /**
//     * @MongoDB\Field(type="string")
//     */
//    protected $color;

    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->parent = null;
        $this->route  = '';
        $this->color  = null;
    }

    /**
     * Get parent
     *
     * @return \Project\ProductBundle\Document\ProductIngredient $parent
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * Set parent
     *
     * @param \Project\ProductBundle\Document\ProductIngredient $parent
     *
     * @return self
     */
    public function setParent(\Project\ProductBundle\Document\ProductIngredient $parent)
    {
        $this->parent = $parent;

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
        return $this->parent ? $this->parent->getRouteParent() . ' > ' . $this->name : $this->name;
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

//    /**
//     * Get color
//     *
//     * @return string $color
//     */
//    public function getColor()
//    {
//        return $this->color;
//    }
//
//    /**
//     * Set color
//     *
//     * @param string $color
//     *
//     * @return self
//     */
//    public function setColor($color)
//    {
//        $this->color = $color;
//
//        return $this;
//    }

    /**
     * @MongoDB\PrePersist()
     */
    public function prePersist()
    {
        parent::prePersist();
        $this->route = $this->getRouteParent();

        return $this;
    }

    /**
     * @MongoDB\PreUpdate()
     */
    public function preUpdate()
    {
        parent::preUpdate();
        $this->route = $this->getRouteParent();

        return $this;
    }
}