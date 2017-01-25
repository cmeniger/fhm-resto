<?php
namespace Fhm\CardBundle\Document;

use Fhm\FhmBundle\Document\Fhm as FhmFhm;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Card
 * @MongoDB\Document(repositoryClass="Fhm\CardBundle\Document\Repository\CardRepository")
 */
class Card extends FhmFhm
{
    /**
     * @MongoDB\ReferenceOne(nullable=true)
     */
    protected $parent;

    /**
     * @MongoDB\ReferenceOne(targetDocument="Fhm\MediaBundle\Document\Media", nullable=true)
     */
    protected $image;

    /**
     * @MongoDB\ReferenceMany(targetDocument="Fhm\CardBundle\Document\CardCategory", nullable=true, cascade={"all"})
     */
    protected $categories;

    /**
     * @MongoDB\ReferenceMany(targetDocument="Fhm\CardBundle\Document\CardProduct", nullable=true, cascade={"all"})
     */
    protected $products;

    /**
     * @MongoDB\ReferenceMany(targetDocument="Fhm\CardBundle\Document\CardIngredient", nullable=true, cascade={"all"})
     */
    protected $ingredients;

    /**
     * @MongoDB\Field(type="int")
     */
    protected $sort_category;

    /**
     * @MongoDB\Field(type="int")
     */
    protected $sort_product;

    /**
     * @MongoDB\Field(type="int")
     */
    protected $sort_ingredient;

    /**
     * @MongoDB\Field(type="string")
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
     * @param $parent
     *
     * @return $this
     */
    public function setParent($parent)
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * @param \Fhm\MediaBundle\Document\Media $image
     */
    public function setImage(\Fhm\MediaBundle\Document\Media $image)
    {
        $this->image = $image;
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
    public function addCategory(\Fhm\CardBundle\Document\CardCategory $category, $cascade = true)
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
    public function removeCategory(\Fhm\CardBundle\Document\CardCategory $category, $cascade = true)
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
    public function addProduct(\Fhm\CardBundle\Document\CardProduct $product, $cascade = true)
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
    public function removeProduct(\Fhm\CardBundle\Document\CardProduct $product, $cascade = true)
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
    public function addIngredient(\Fhm\CardBundle\Document\CardIngredient $ingredient, $cascade = true)
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
    public function removeIngredient(\Fhm\CardBundle\Document\CardIngredient $ingredient, $cascade = true)
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