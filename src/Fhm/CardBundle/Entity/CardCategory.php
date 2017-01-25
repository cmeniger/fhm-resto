<?php
namespace Fhm\CardBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Fhm\FhmBundle\Entity\Fhm;

/**
 * @ORM\Entity(repositoryClass="Fhm\CardBundle\Entity\Repository\CardCategoryRepository")
 * @ORM\Table()
 */
class CardCategory extends Fhm
{
    /**
     * @ORM\Column(type="string", length=100)
     */
    protected $price;

    /**
     * @ORM\Column(type="string", length=100)
     */
    protected $currency;

    /**
     * @ORM\Column(type="string", length=100)
     */
    protected $route;

    /**
     * @ORM\Column(type="boolean")
     */
    protected $menu;

    /**
     * @ORM\Column(type="boolean")
     */
    protected $default;

    /**
     * @ORM\OneToOne(targetEntity="Fhm\MediaBundle\Entity\Media", orphanRemoval=true)
     */
    protected $image;

    /**
     * @ORM\OneToOne(targetEntity="Card", cascade={"persist"}, inversedBy="categories")
     */
    protected $card;

    /**
     * @ORM\ManyToMany(targetEntity="CardCategory", cascade={"persist"})
     * @ORM\JoinTable(name="card_category_parents")
     */
    protected $parents;

    /**
     * @ORM\ManyToMany(targetEntity="CardCategory", cascade={"persist"})
     * @ORM\JoinTable(name="card_category_sons")
     */
    protected $sons;

    /**
     * @ORM\ManyToMany(targetEntity="CardProduct", cascade={"persist"}, inversedBy="categories")
     * @ORM\JoinTable(name="card_category_products")
     */
    protected $products;

    /**
     * @ORM\Column(type="string", length=100)
     */
    protected $sort_card;

    /**
     * @ORM\Column(type="integer")
     */
    protected $sort_parent;

    /**
     * @ORM\Column(type="integer")
     */
    protected $sort_son;

    /**
     * @ORM\Column(type="integer")
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
     * @return mixed
     */
    public function getPrice()
    {
        return $this->price;
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
    public function getCurrency()
    {
        return $this->currency;
    }

    /**
     * @param mixed $currency
     */
    public function setCurrency($currency)
    {
        $this->currency = $currency;
    }

    /**
     * @return mixed
     */
    public function getRoute()
    {
        return $this->route;
    }

    /**
     * @param mixed $route
     */
    public function setRoute($route)
    {
        $this->route = $route;
    }

    /**
     * @return mixed
     */
    public function getMenu()
    {
        return $this->menu;
    }

    /**
     * @param mixed $menu
     */
    public function setMenu($menu)
    {
        $this->menu = $menu;
    }

    /**
     * @return mixed
     */
    public function getDefault()
    {
        return $this->default;
    }

    /**
     * @param mixed $default
     */
    public function setDefault($default)
    {
        $this->default = $default;
    }

    /**
     * @return mixed
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * @param mixed $image
     */
    public function setImage($image)
    {
        $this->image = $image;
    }

    /**
     * @return mixed
     */
    public function getCard()
    {
        return $this->card;
    }

    /**
     * @param mixed $card
     */
    public function setCard($card)
    {
        $this->card = $card;
    }

    /**
     * @return mixed
     */
    public function getSortCard()
    {
        return $this->sort_card;
    }

    /**
     * @param mixed $sort_card
     */
    public function setSortCard($sort_card)
    {
        $this->sort_card = $sort_card;
    }

    /**
     * @return mixed
     */
    public function getSortParent()
    {
        return $this->sort_parent;
    }

    /**
     * @param mixed $sort_parent
     */
    public function setSortParent($sort_parent)
    {
        $this->sort_parent = $sort_parent;
    }

    /**
     * @return mixed
     */
    public function getSortSon()
    {
        return $this->sort_son;
    }

    /**
     * @param mixed $sort_son
     */
    public function setSortSon($sort_son)
    {
        $this->sort_son = $sort_son;
    }

    /**
     * @return mixed
     */
    public function getSortProduct()
    {
        return $this->sort_product;
    }

    /**
     * @param mixed $sort_product
     */
    public function setSortProduct($sort_product)
    {
        $this->sort_product = $sort_product;
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
     * @param \Fhm\CardBundle\Entity\CardCategory $parent
     *
     * @return $this
     */
    public function addParent(\Fhm\CardBundle\Entity\CardCategory $parent)
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
     * @param \Fhm\CardBundle\Entity\CardCategory $parent
     *
     * @return $this
     */
    public function removeParent(\Fhm\CardBundle\Entity\CardCategory $parent)
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
     * @param \Fhm\CardBundle\Entity\CardCategory $son
     *
     * @return $this
     */
    public function addSon(\Fhm\CardBundle\Entity\CardCategory $son)
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
     * @param \Fhm\CardBundle\Entity\CardCategory $son
     *
     * @return $this
     */
    public function removeSon(\Fhm\CardBundle\Entity\CardCategory $son)
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
     * @param \Fhm\CardBundle\Entity\CardProduct $product
     *
     * @return $this
     */
    public function addProduct(\Fhm\CardBundle\Entity\CardProduct $product)
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
     * @param \Fhm\CardBundle\Entity\CardProduct $product
     *
     * @return $this
     */
    public function removeProduct(\Fhm\CardBundle\Entity\CardProduct $product)
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
     * @ORM\PrePersist()
     */
    public function prePersist()
    {
        parent::prePersist();
        $this->route = $this->getRouteParent();

        return $this;
    }

    /**
     * @ORM\PreUpdate()
     */
    public function preUpdate()
    {
        parent::preUpdate();
        $this->route = $this->getRouteParent();

        return $this;
    }

    /**
     * @ORM\PreRemove()
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