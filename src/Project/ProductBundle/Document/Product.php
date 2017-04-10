<?php
namespace Project\ProductBundle\Document;

use Fhm\FhmBundle\Document\Fhm as FhmFhm;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Product
 * @MongoDB\Document(repositoryClass="Project\ProductBundle\Document\Repository\ProductRepository")
 */
class Product extends FhmFhm
{


    /**
     * @MongoDB\ReferenceMany(targetDocument="Project\ProductBundle\Document\ProductIngredient", nullable=true)
     */
    protected $ingredients;

    /**
     * @MongoDB\Field(type="string")
     */
    protected $title;

    /**
     * @MongoDB\Field(type="string")
     */
    protected $price;

    /**
     * @MongoDB\ReferenceOne(targetDocument="Fhm\MediaBundle\Document\Media", nullable=true)
     */
    protected $media;

    /**
     * @MongoDB\ReferenceMany(targetDocument="Project\CategoryBundle\Document\Category", nullable=true, cascade={"persist"})
     */
    protected $categories;

    //    /**
    //     * @MongoDB\ReferenceMany(targetDocument="Fhm\CategoryBundle\Document\Category", nullable=true, cascade={"persist"})
    //     */
    //    protected $category;
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->ingredients = new ArrayCollection();
        $this->categories  = new ArrayCollection();
        parent::__construct();
    }


    /**
     * Get ingredients
     *
     * @return \Project\ProductBundle\Document\ProductIngredient $ingredient
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
     * @return self
     */
    public function setIngredients(ArrayCollection $ingredients)
    {
        $this->ingredients = $ingredients;

        return $this;
    }

    /**
     * Add ingredient
     *
     * @param \Project\ProductBundle\Document\ProductIngredient $ingredient
     *
     * @return $this
     */
    public function addIngredient(\Project\ProductBundle\Document\ProductIngredient $ingredient)
    {
        if(!$this->ingredients->contains($ingredient))
        {
            $this->ingredients->add($ingredient);
        }

        return $this;
    }

    /**
     * Remove ingredient
     *
     * @param \Project\ProductBundle\Document\ProductIngredient $ingredient
     *
     * @return $this
     */
    public function removeIngredient(\Project\ProductBundle\Document\ProductIngredient $ingredient)
    {
        if($this->ingredients->contains($ingredient))
        {
            $this->ingredients->removeElement($ingredient);
        }

        return $this;
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
     * Get price
     *
     * @return string $price
     */
    public function getPrice()
    {
        return $this->price;
    }


    /**
     * @param mixed $media
     */
    public function setMedia($media)
    {
        $this->media = $media;
    }

    /**
     * @return mixed
     */
    public function getMedia()
    {
        return $this->media;
    }

    /**
     * Get categories
     *
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
    public function setCategories(ArrayCollection $categories)
    {
        foreach($categories as $category)
        {
            $category->addProducts($this);
        }
        $this->categories = $categories;

        return $this;
    }

    /**
     * Add category
     *
     * @param \Project\CategoryBundle\Document\Category $category
     *
     * @return $this
     */
    public function addCategory(\Project\CategoryBundle\Document\Category $category)
    {
        if(!$this->categories->contains($category))
        {
            $this->categories->add($category);
            $category->addProducts($this);
        }

        return $this;
    }

    /**
     * Remove category
     *
     * @param \Project\CategoryBundle\Document\Category $category
     *
     * @return $this
     */
    public function removeCategory(\Project\CategoryBundle\Document\Category $category)
    {
        if($this->categories->contains($category))
        {
            $this->categories->removeElement($category);
            $category->removeProducts($this);
        }

        return $this;
    }

    /**
     * @MongoDB\PreRemove()
     */
    public function preRemove()
    {
        parent::preRemove();
        foreach($this->categories as $category)
        {
            $category->removeProducts($this);
        }
    }
}