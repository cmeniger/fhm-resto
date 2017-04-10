<?php
namespace Project\CategoryBundle\Document;

use Fhm\FhmBundle\Document\Fhm as FhmFhm;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Category
 * @MongoDB\Document(repositoryClass="Project\CategoryBundle\Document\Repository\CategoryRepository")
 */
class Category extends FhmFhm
{
    /**
     * @MongoDB\ReferenceOne(targetDocument="Project\CategoryBundle\Document\Category", nullable=true)
     */
    protected $parent;

    /**
     * @MongoDB\Field(type="string")
     */
    protected $route;

    /**
     * @MongoDB\Field(type="boolean")
     */
    protected $activeImage;

    /**
     * @MongoDB\Field(type="boolean")
     */
    protected $activemenumode;

    /**
     * @MongoDB\ReferenceMany(targetDocument="Project\ProductBundle\Document\Product", nullable=true, cascade={"persist"})
     */
    protected $products;

    /**
     * @MongoDB\ReferenceMany(targetDocument="Project\CategoryBundle\Document\Category", nullable=true, cascade={"persist"})
     */
    protected $sons;

    /**
     * @MongoDB\Field(type="string")
     */
    protected $content;

    /**
     * @MongoDB\Field(type="int")
     */
    protected $price;


    /**
     * Constructor
     */
    public function __construct()
    {
        $this->products    = new ArrayCollection();
        $this->sons        = new ArrayCollection();
        $this->activeImage = false;
        $this->parent      = null;
        $this->route       = '';
        parent::__construct();
    }

    /**
     * Get parent
     *
     * @return \Project\CategoryBundle\Document\Category $parent
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * Set parent
     *
     * @param \Project\CategoryBundle\Document\Category $parent
     *
     * @return self
     */
    public function setParent(\Project\CategoryBundle\Document\Category $parent)
    {
        $this->parent = $parent;
        $parent->addSon($this);

        return $this;
    }

    /**
     * Remove parent
     *
     * @param \Project\CategoryBundle\Document\Category $parent
     *
     * @return self
     */
    public function removeParent(\Project\CategoryBundle\Document\Category $parent)
    {
        $this->parent = null;

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

    /**
     * Set activeImage
     *
     * @param boolean $activeImage
     *
     * @return self
     */
    public function setActiveImage($activeImage)
    {
        $this->activeImage = $activeImage;

        return $this;
    }

    /**
     * Get activeImage
     *
     * @return boolean $activeImage
     */
    public function getActiveImage()
    {
        return $this->activeImage;
    }

    /**
     * Get products
     *
     * @return mixed
     */
    public function getProducts()
    {
        return $this->products;
    }

    /**
     * Set products
     *
     * @param ArrayCollection $products
     *
     * @return $this
     */
    public function setProducts(ArrayCollection $products)
    {
        foreach($products as $product)
        {
            $product->addCategory($this);
        }
        $this->products = $products;

        return $this;
    }

    /**
     * Add products
     *
     * @param \Project\ProductBundle\Document\Product $products
     *
     * @return $this
     */
    public function addProducts(\Project\ProductBundle\Document\Product $products)
    {
        if(!$this->products->contains($products))
        {
            $this->products->add($products);
            $products->addCategory($this);
        }

        return $this;
    }

    /**
     * Remove products
     *
     * @param \Project\ProductBundle\Document\Product $products
     *
     * @return $this
     */
    public function removeProducts(\Project\ProductBundle\Document\Product $products)
    {
        if($this->products->contains($products))
        {
            $this->products->removeElement($products);
            $products->removeCategory($this);
        }

        return $this;
    }

    /**
     * Get sons
     *
     * @return mixed
     */
    public function getSons()
    {
        return $this->sons;
    }

    /**
     * Set sons
     *
     * @param ArrayCollection $sons
     *
     * @return $this
     */
    public function setSons(ArrayCollection $sons)
    {
        foreach($sons as $son)
        {
            $son->setParent($this);
        }
        $this->sons = $sons;

        return $this;
    }

    /**
     * Add son
     *
     * @param \Project\CategoryBundle\Document\Category $son
     *
     * @return $this
     */
    public function addSon(\Project\CategoryBundle\Document\Category $son)
    {
        if(!$this->sons->contains($son))
        {
            $this->sons->add($son);
            $son->setParent($this);
        }

        return $this;
    }

    /**
     * Set activemenumode
     *
     * @param boolean $activemenumode
     *
     * @return self
     */
    public function setActivemenumode($activemenumode)
    {
        $this->activemenumode = $activemenumode;

        return $this;
    }

    /**
     * Get activemenumode
     *
     * @return boolean $activemenumode
     */
    public function getActivemenumode()
    {
        return $this->activemenumode;
    }

    /**
     * @param mixed $content
     */
    public function setContent($content)
    {
        $this->content = $content;
    }

    /**
     * @return mixed
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @param mixed $price
     */
    public function setPrice($price)
    {
        $this->price = $price;
    }

    /**
     * @return mixed
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Remove son
     *
     * @param \Project\CategoryBundle\Document\Category $son
     *
     * @return $this
     */
    public function removeSon(\Project\CategoryBundle\Document\Category $son)
    {
        if($this->sons->contains($son))
        {
            $this->sons->removeElement($son);
            $son->removeParent($this);
        }

        return $this;
    }

    /**
     * @MongoDB\PreRemove()
     */
    public function preRemove()
    {
        parent::preRemove();
        foreach($this->products as $products)
        {
            $products->removeCategory($this);
        }
        foreach($this->sons as $son)
        {
            $son->removeSon($this);
            $son->removeParent($this);

        }
        if ($this->parent){
        $this->parent->removeSon($this);
        }
    }

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