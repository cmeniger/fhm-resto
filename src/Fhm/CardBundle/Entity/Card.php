<?php
namespace Fhm\CardBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Fhm\FhmBundle\Entity\Fhm;

class Card extends Fhm
{
    /**
     * @ORM\OneToOne(targetEntity="")
     */
    protected $parent;

    /**
     * @ORM\OneToOne(targetEntity="Media")
     */
    protected $image;

    /**
     * @ORM\OneToMany(targetEntity="CardCategory")
     */
    protected $categories;

    /**
     * @ORM\OneToMany(targetEntity="CardProduct")
     */
    protected $products;

    /**
     * @ORM\OneToMany(targetEntity="CardIngredient")
     */
    protected $ingredients;

    /**
     * @ORM\Column(type="integer")
     */
    protected $sort_category;

    /**
     * @ORM\Column(type="integer")
     */
    protected $sort_product;

    /**
     * @ORM\Column(type="integer")
     */
    protected $sort_ingredient;

    /**
     * @ORM\Column(type="string", length=100)
     */
    protected $sort_parent;

    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->categories      = new ArrayCollection();
        $this->products        = new ArrayCollection();
        $this->ingredients     = new ArrayCollection();
        $this->sort_category   = 0;
        $this->sort_product    = 0;
        $this->sort_ingredient = 0;
        $this->sort_parent     = '';
    }

    /**
     * @return mixed
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * @param mixed $parent
     */
    public function setParent($parent)
    {
        $this->parent = $parent;
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
    public function getSortCategory()
    {
        return $this->sort_category;
    }

    /**
     * @param mixed $sort_category
     */
    public function setSortCategory($sort_category)
    {
        $this->sort_category = $sort_category;
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
     * @return mixed
     */
    public function getSortIngredient()
    {
        return $this->sort_ingredient;
    }

    /**
     * @param mixed $sort_ingredient
     */
    public function setSortIngredient($sort_ingredient)
    {
        $this->sort_ingredient = $sort_ingredient;
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
    public function getCategories()
    {
        return $this->categories;
    }

    /**
     * Set categories
     *
     * @param ArrayCollection $categories
     *
     * @return $this
     */
    public function setCategories($categories)
    {
        $this->resetCategories();
        $this->categories = $categories;

        return $this;
    }

    /**
     * Add category
     *
     * @param CardCategory $category
     * @param bool         $cascade
     *
     * @return $this
     */
    public function addCategory(\Fhm\CardBundle\Entity\CardCategory $category, $cascade = true)
    {
        if(!$this->categories->contains($category))
        {
            $this->categories->add($category);
            if($cascade)
            {
                $category->setCard($this);
            }
        }

        return $this;
    }

    /**
     * Remove category
     *
     * @param CardCategory $category
     * @param bool         $cascade
     *
     * @return $this
     */
    public function removeCategory(\Fhm\CardBundle\Entity\CardCategory $category, $cascade = true)
    {
        if($this->categories->contains($category))
        {
            $this->categories->removeElement($category);
            if($cascade)
            {
                $category->setCard(null);
            }
        }

        return $this;
    }

    /**
     * Reset categories
     *
     * @return $this
     */
    public function resetCategories()
    {
        foreach($this->categories as $category)
        {
            $category->setCard(null);
        }
        $this->categories = new ArrayCollection();

        return $this;
    }

    /**
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
    public function setProducts($products)
    {
        $this->resetProducts();
        $this->products = $products;

        return $this;
    }

    /**
     * @param CardProduct $product
     * @param bool        $cascade
     *
     * @return $this
     */
    public function addProduct(\Fhm\CardBundle\Entity\CardProduct $product, $cascade = true)
    {
        if(!$this->products->contains($product))
        {
            $this->products->add($product);
            if($cascade)
            {
                $product->setCard($this);
            }
        }

        return $this;
    }

    /**
     * @param CardProduct $product
     * @param bool        $cascade
     *
     * @return $this
     */
    public function removeProduct(\Fhm\CardBundle\Entity\CardProduct $product, $cascade = true)
    {
        if($this->products->contains($product))
        {
            $this->products->removeElement($product);
            if($cascade)
            {
                $product->setCard(null);
            }
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
            $product->setCard(null);
        }
        $this->products = new ArrayCollection();

        return $this;
    }

    /**
     * @return mixed
     */
    public function getIngredients()
    {
        return $this->ingredients;
    }

    /**
     * Set ingredients
     *
     * @param ArrayCollection $ingredients
     *
     * @return $this
     */
    public function setIngredients($ingredients)
    {
        $this->resetIngredients();
        $this->ingredients = $ingredients;

        return $this;
    }

    /**
     * @param CardIngredient $ingredient
     * @param bool           $cascade
     *
     * @return $this
     */
    public function addIngredient(\Fhm\CardBundle\Entity\CardIngredient $ingredient, $cascade = true)
    {
        if(!$this->ingredients->contains($ingredient))
        {
            $this->ingredients->add($ingredient);
            if($cascade)
            {
                $ingredient->setCard($this);
            }
        }

        return $this;
    }

    /**
     * @param CardIngredient $ingredient
     * @param bool           $cascade
     *
     * @return $this
     */
    public function removeIngredient(\Fhm\CardBundle\Entity\CardIngredient $ingredient, $cascade = true)
    {
        if($this->ingredients->contains($ingredient))
        {
            $this->ingredients->removeElement($ingredient);
            if($cascade)
            {
                $ingredient->setCard(null);
            }
        }

        return $this;
    }

    /**
     * Reset ingredients
     *
     * @return $this
     */
    public function resetIngredients()
    {
        foreach($this->ingredients as $ingredient)
        {
            $ingredient->setCard(null);
        }
        $this->ingredients = new ArrayCollection();

        return $this;
    }

    /**
     * Sort update
     *
     * @return $this
     */
    public function sortUpdate()
    {
        $this->sort_category   = $this->categories->count();
        $this->sort_product    = $this->products->count();
        $this->sort_ingredient = $this->ingredients->count();
        $this->sort_parent     = $this->parent ? $this->parent->getAlias() : '';

        return parent::sortUpdate();
    }

    /**
     * @MongoDB\PreRemove()
     */
    public function preRemove()
    {
        $this->resetCategories();
        $this->resetProducts();
        $this->resetIngredients();

        return parent::preRemove();
    }
}