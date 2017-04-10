<?php
namespace Project\CategoryBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Fhm\FhmBundle\Entity\Fhm;

/**
 * @ORM\Entity(repositoryClass="Project\CategoryBundle\Entity\Repository\CategoryRepository")
 * @ORM\Table()
 */
class Category extends Fhm
{
    protected $parent;

    /**
     * @ORM\Column(type="string", length=100)
     */
    protected $route;

    protected $activeImage;

    /**
     * @ORM\Column(type="boolean")
     */
    protected $activemenumode;

    protected $products;

    protected $sons;

    /**
     * @ORM\Column(type="string", length=100)
     */
    protected $content;

    /**
     * @ORM\Column(type="integer", length=100)
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
     * @return \Fhm\CategoryBundle\Document\Category $parent
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * Set parent
     *
     * @param \Fhm\CategoryBundle\Document\Category $parent
     *
     * @return self
     */
    public function setParent(\Fhm\CategoryBundle\Document\Category $parent)
    {
        $this->parent = $parent;
        $parent->addSon($this);

        return $this;
    }

    /**
     * Remove parent
     *
     * @param \Fhm\CategoryBundle\Document\Category $parent
     *
     * @return self
     */
    public function removeParent(\Fhm\CategoryBundle\Document\Category $parent)
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
     * @param \Fhm\ProductBundle\Document\Product $products
     *
     * @return $this
     */
    public function addProducts(\Fhm\ProductBundle\Document\Product $products)
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
     * @param \Fhm\ProductBundle\Document\Product $products
     *
     * @return $this
     */
    public function removeProducts(\Fhm\ProductBundle\Document\Product $products)
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
     * @param \Fhm\CategoryBundle\Document\Category $son
     *
     * @return $this
     */
    public function addSon(\Fhm\CategoryBundle\Document\Category $son)
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
     * @param \Fhm\CategoryBundle\Document\Category $son
     *
     * @return $this
     */
    public function removeSon(\Fhm\CategoryBundle\Document\Category $son)
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