<?php

namespace Fhm\FhmBundle\Document;

use Fhm\FhmBundle\Document\Fhm as FhmFhm;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Menu
 *
 * @MongoDB\EmbeddedDocument
 * @MongoDB\Document(repositoryClass="Fhm\FhmBundle\Document\Repository\MenuRepository")
 */
class Menu extends FhmFhm
{
    /**
     * @MongoDB\ReferenceMany(targetDocument="Menu", nullable=true, cascade={"all"})
     */
    protected $childs;

    /**
     * @MongoDB\Field(type="string")
     */
    protected $parent = 0;

    /**
     * @MongoDB\Field(type="string")
     */
    protected $route = null;

    /**
     * @MongoDB\Field(type="string")
     */
    protected $icon = null;

    /**
     * @MongoDB\Field(type="hash")
     */
    protected $module = array();

    /**
     * @MongoDB\Field(type="boolean")
     */
    protected $external = false;

    /**
     * @MongoDB\Field(type="int")
     * @Assert\Range(min = 0)
     */
    protected $order = 0;

    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->childs = new ArrayCollection();
    }

    /**
     * Get parent
     *
     * @return string $parent
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * Set parent
     *
     * @param string $parent
     *
     * @return self
     */
    public function setParent($parent)
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * Get order
     *
     * @return string $order
     */
    public function getOrder()
    {
        return $this->order;
    }

    /**
     * Set order
     *
     * @param string $order
     *
     * @return self
     */
    public function setOrder($order)
    {
        $this->order = $order;

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
     * Get icon
     *
     * @return string $icon
     */
    public function getIcon()
    {
        return $this->icon;
    }

    /**
     * Set icon
     *
     * @param string $icon
     *
     * @return self
     */
    public function setIcon($icon)
    {
        $this->icon = $icon;

        return $this;
    }

    /**
     * Get module
     *
     * @return string $module
     */
    public function getModule()
    {
        return $this->module;
    }

    /**
     * set module
     *
     * @param $module
     *
     * @return  $this
     */
    public function setModule($module)
    {
        $this->module = $module;

        return $this;
    }

    /**
     * Get childs
     *
     * @return string $childs
     */
    public function getChilds()
    {
        return $this->childs;
    }

    /**
     * set childs
     *
     * @param $childs
     *
     * @return  $this
     */
    public function setChilds($childs)
    {
        $this->childs = $childs;

        return $this;
    }

    /**
     * add child
     *
     * @param \Fhm\FhmBundle\Document\Menu $menu
     *
     * @return  $this
     */
    public function addChild(\Fhm\FhmBundle\Document\Menu $menu)
    {
        if(!$this->childs->contains($menu))
        {
            $this->childs->add($menu);
        }

        return $this;
    }

    /**
     * Remove child
     *
     * @param \Fhm\FhmBundle\Document\Menu $menu
     *
     * @return $this
     */
    public function removeChild(\Fhm\FhmBundle\Document\Menu $menu)
    {
        if($this->childs->contains($menu))
        {
            $this->childs->removeElement($menu);
        }

        return $this;
    }

    /**
     * @return mixed
     */
    public function getExternal()
    {
        return $this->external;
    }

    /**
     * @param $external
     *
     * @return $this
     */
    public function setExternal($external)
    {
        $this->external = $external;

        return $this;
    }
}