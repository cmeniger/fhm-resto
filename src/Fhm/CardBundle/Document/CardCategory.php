<?php
namespace Fhm\CardBundle\Document;

use Fhm\FhmBundle\Document\Fhm as FhmFhm;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * CardCategory
 * @MongoDB\Document(repositoryClass="Fhm\CardBundle\Document\Repository\CardCategoryRepository")
 */
class CardCategory extends FhmFhm
{
    /**
     * @MongoDB\Field(type="string")
     */
    protected $price;

    /**
     * @MongoDB\Field(type="string")
     */
    protected $currency;

    /**
     * @MongoDB\Field(type="string")
     */
    protected $route;

    /**
     * @MongoDB\Field(type="boolean")
     */
    protected $menu;

    /**
     * @MongoDB\Field(type="boolean")
     */
    protected $default;

    /**
     * @MongoDB\ReferenceOne(targetDocument="Fhm\MediaBundle\Document\Media", nullable=true)
     */
    protected $image;

    /**
     * @MongoDB\ReferenceOne(targetDocument="Fhm\CardBundle\Document\Card", nullable=true, cascade={"persist"})
     */
    protected $card;

    /**
     * @MongoDB\ReferenceMany(targetDocument="Fhm\CardBundle\Document\CardCategory", nullable=true, cascade={"persist"})
     */
    protected $parents;

    /**
     * @MongoDB\ReferenceMany(targetDocument="Fhm\CardBundle\Document\CardCategory", nullable=true, cascade={"persist"})
     */
    protected $sons;

    /**
     * @MongoDB\ReferenceMany(targetDocument="Fhm\CardBundle\Document\CardProduct", nullable=true, cascade={"persist"})
     */
    protected $products;

    /**
     * @MongoDB\Field(type="string")
     */
    protected $sort_card;

    /**
     * @MongoDB\Field(type="int")
     */
    protected $sort_parent;

    /**
     * @MongoDB\Field(type="int")
     */
    protected $sort_son;

    /**
     * @MongoDB\Field(type="int")
     */
    protected $sort_product;

    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->parents      = new ArrayCollection();
        $this->sons         = new ArrayCollection();
        $this->products     = new ArrayCollection();
        $this->currency     = '€';
        $this->menu         = false;
        $this->route        = '';
        $this->sort_card    = '';
        $this->sort_parent  = 0;
        $this->sort_son     = 0;
        $this->sort_product = 0;
    }

    /**
     * Get price
     *
     * @return string $price
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Set price
     *
     * @param string $price
     *
     * @return self
     */
    public function setPrice($price)
    {
        $this->price = $price;

        return $this;
    }

    /**
     * Get currency
     *
     * @return string $currency
     */
    public function getCurrency()
    {
        return $this->currency;
    }

    /**
     * Set currency
     *
     * @param string $currency
     *
     * @return self
     */
    public function setCurrency($currency)
    {
        $this->currency = $currency;

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
        $route = array();
        if($this->parents->count() > 0)
        {
            foreach($this->parents as $parent)
            {
                $route[] = $parent->getRouteParent() . ' > ' . $this->name;
            }
        }
        else
        {
            $route[] = $this->name;
        }

        return implode(' | ', $route);
    }

    /**
     * Get route array
     *
     * @return array $route
     */
    public function getRouteArray()
    {
        $route = explode(' | ', $this->route);
        foreach($route as &$value)
        {
            $value = explode(' > ', $value);
        }

        return $route;
    }

    /**
     * Get route object
     *
     * @return array $route
     */
    public function getRouteObject()
    {
        $route = array();
        if($this->parents->count() > 0)
        {
            foreach($this->parents as $parent)
            {
                $route[] = array_merge($parent->getRouteObject(), array($this));
            }
        }
        else
        {
            $route[] = $this;
        }

        return $route;
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
     * Get menu
     *
     * @return boolean $menu
     */
    public function getMenu()
    {
        return $this->menu;
    }

    /**
     * Set menu
     *
     * @param boolean $menu
     *
     * @return self
     */
    public function setMenu($menu)
    {
        $this->menu = $menu;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getDefault()
    {
        return $this->default;
    }

    /**
     * @param $default
     *
     * @return $this
     */
    public function setDefault($default)
    {
        $this->default = $default;

        return $this;
    }

    /**
     * @return \Fhm\MediaBundle\Document\Media $image
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * @param \Fhm\MediaBundle\Document\Media $image
     *
     * @return $this
     */
    public function setImage($image)
    {
        $this->image = ($image instanceof \Fhm\MediaBundle\Document\Media) ? $image : null;

        return $this;
    }

    /**
     * @return \Fhm\CardBundle\Document\Card $card
     */
    public function getCard()
    {
        return $this->card;
    }

    /**
     * @param \Fhm\CardBundle\Document\Card $card
     *
     * @return $this
     */
    public function setCard($card)
    {
        $this->resetCard();
        $this->card = ($card instanceof \Fhm\CardBundle\Document\Card) ? $card : null;
        if($this->card instanceof \Fhm\CardBundle\Document\Card)
        {
            $this->card->addCategory($this, false);
        }

        return $this;
    }

    /**
     * @return $this
     */
    public function resetCard()
    {
        if($this->card instanceof \Fhm\CardBundle\Document\Card)
        {
            $this->card->removeCategory($this, false);
        }

        return $this;
    }

    /**
     * Get parents
     *
     * @return mixed
     */
    public function getParents()
    {
        return $this->parents;
    }

    /**
     * Set parents
     *
     * @param ArrayCollection $parents
     *
     * @return $this
     */
    public function setParents(ArrayCollection $parents)
    {
        $this->resetParents();
        foreach($parents as $parent)
        {
            $parent->addSon($this);
        }
        $this->parents = $parents;

        return $this;
    }

    /**
     * Add parent
     *
     * @param \Fhm\CardBundle\Document\CardCategory $parent
     *
     * @return $this
     */
    public function addParent(\Fhm\CardBundle\Document\CardCategory $parent)
    {
        if(!$this->parents->contains($parent))
        {
            $this->parents->add($parent);
            $parent->addSon($this);
        }

        return $this;
    }

    /**
     * Remove parent
     *
     * @param \Fhm\CardBundle\Document\CardCategory $parent
     *
     * @return $this
     */
    public function removeParent(\Fhm\CardBundle\Document\CardCategory $parent)
    {
        if($this->parents->contains($parent))
        {
            $this->parents->removeElement($parent);
            $parent->removeSon($this);
        }

        return $this;
    }

    /**
     * Reset parents
     *
     * @return $this
     */
    public function resetParents()
    {
        foreach($this->parents as $parent)
        {
            $parent->removeSon($this);
        }
        $this->parents = new ArrayCollection();

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
        $this->resetSons();
        foreach($sons as $son)
        {
            $son->addParent($this);
        }
        $this->sons = $sons;

        return $this;
    }

    /**
     * Add son
     *
     * @param \Fhm\CardBundle\Document\CardCategory $son
     *
     * @return $this
     */
    public function addSon(\Fhm\CardBundle\Document\CardCategory $son)
    {
        if(!$this->sons->contains($son))
        {
            $this->sons->add($son);
            $son->addParent($this);
        }

        return $this;
    }

    /**
     * Remove son
     *
     * @param \Fhm\CardBundle\Document\CardCategory $son
     *
     * @return $this
     */
    public function removeSon(\Fhm\CardBundle\Document\CardCategory $son)
    {
        if($this->sons->contains($son))
        {
            $this->sons->removeElement($son);
            $son->removeParent($this);
        }

        return $this;
    }

    /**
     * Reset sons
     *
     * @return $this
     */
    public function resetSons()
    {
        foreach($this->sons as $son)
        {
            $son->removeParent($this);
        }
        $this->sons = new ArrayCollection();

        return $this;
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
        $this->resetProducts();
        foreach($products as $product)
        {
            $product->addCategory($this);
        }
        $this->products = $products;

        return $this;
    }

    /**
     * Add product
     *
     * @param \Fhm\CardBundle\Document\CardProduct $product
     *
     * @return $this
     */
    public function addProduct(\Fhm\CardBundle\Document\CardProduct $product)
    {
        if(!$this->products->contains($product))
        {
            $this->products->add($product);
            $product->addCategory($this);
        }

        return $this;
    }

    /**
     * Remove product
     *
     * @param \Fhm\CardBundle\Document\CardProduct $product
     *
     * @return $this
     */
    public function removeProduct(\Fhm\CardBundle\Document\CardProduct $product)
    {
        if($this->products->contains($product))
        {
            $this->products->removeElement($product);
            $product->removeCategory($this);
        }

        return $this;
    }

    /**
     * Reset products
     *
     * @return $this
     */
    public function resetProducts()
    {
        foreach($this->products as $product)
        {
            $product->removeCategory($this);
        }
        $this->products = new ArrayCollection();

        return $this;
    }

    /**
     * Sort update
     *
     * @return $this
     */
    public function sortUpdate()
    {
        $this->sort_card    = $this->card ? $this->card->getAlias() : '';
        $this->sort_parent  = $this->parents->count();
        $this->sort_son     = $this->sons->count();
        $this->sort_product = $this->products->count();

        return parent::sortUpdate();
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

    /**
     * @MongoDB\PreRemove()
     */
    public function preRemove()
    {
        $this->resetCard();
        $this->resetParents();
        $this->resetSons();
        $this->resetProducts();

        return parent::preRemove();
    }
}