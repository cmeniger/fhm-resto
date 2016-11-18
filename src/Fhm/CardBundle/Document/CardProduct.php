<?php
namespace Fhm\CardBundle\Document;

use Fhm\FhmBundle\Document\Fhm as FhmFhm;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * CardProduct
 * @MongoDB\Document(repositoryClass="Fhm\CardBundle\Repository\CardProductRepository")
 */
class CardProduct extends FhmFhm
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
     * @MongoDB\Field(type="boolean")
     */
    protected $forward;

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
    protected $categories;

    /**
     * @MongoDB\ReferenceMany(targetDocument="Fhm\CardBundle\Document\CardIngredient", nullable=true, cascade={"persist"})
     */
    protected $ingredients;

    /**
     * @MongoDB\Field(type="string")
     */
    protected $sort_card;

    /**
     * @MongoDB\Field(type="int")
     */
    protected $sort_category;

    /**
     * @MongoDB\Field(type="int")
     */
    protected $sort_ingredient;

    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->categories      = new ArrayCollection();
        $this->ingredients     = new ArrayCollection();
        $this->currency        = "€";
        $this->forward         = false;
        $this->sort_card       = "";
        $this->sort_category   = 0;
        $this->sort_ingredient = 0;
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
     * Get forward
     *
     * @return bool
     */
    public function getForward()
    {
        return $this->forward;
    }

    /**
     * Set forward
     *
     * @param $forward
     *
     * @return $this
     */
    public function setForward($forward)
    {
        $this->forward = $forward;

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
            $this->card->addProduct($this, false);
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
            $this->card->removeProduct($this, false);
        }

        return $this;
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
        foreach($categories as $category)
        {
            $category->addProduct($this);
        }
        $this->categories = $categories;

        return $this;
    }

    /**
     * @param \Fhm\CardBundle\Document\CardCategory $category
     *
     * @return $this
     */
    public function addCategory(\Fhm\CardBundle\Document\CardCategory $category)
    {
        if(!$this->categories->contains($category))
        {
            $this->categories->add($category);
            $category->addProduct($this);
        }

        return $this;
    }

    /**
     * Remove category
     *
     * @param \Fhm\CardBundle\Document\CardCategory $category
     *
     * @return $this
     */
    public function removeCategory(CardCategory $category)
    {
        if($this->categories->contains($category))
        {
            $this->categories->removeElement($category);
            $category->removeProduct($this);
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
            $category->removeProduct($this);
        }
        $this->categories = new ArrayCollection();

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
        foreach($ingredients as $ingredient)
        {
            $ingredient->addProduct($this);
        }
        $this->ingredients = $ingredients;

        return $this;
    }

    /**
     * @param \Fhm\CardBundle\Document\CardIngredient $ingredient
     *
     * @return $this
     */
    public function addIngredient(\Fhm\CardBundle\Document\CardIngredient $ingredient)
    {
        if(!$this->ingredients->contains($ingredient))
        {
            $this->ingredients->add($ingredient);
            $ingredient->addProduct($this);
        }

        return $this;
    }

    /**
     * Remove ingredient
     *
     * @param \Fhm\CardBundle\Document\\Fhm\CardBundle\Document\CardIngredient $ingredient
     *
     * @return $this
     */
    public function removeIngredient(\Fhm\CardBundle\Document\CardIngredient $ingredient)
    {
        if($this->ingredients->contains($ingredient))
        {
            $this->ingredients->removeElement($ingredient);
            $ingredient->removeProduct($this);
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
            $ingredient->removeProduct($this);
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
        $this->sort_card       = $this->card ? $this->card->getAlias() : '';
        $this->sort_category   = $this->categories->count();
        $this->sort_ingredient = $this->ingredients->count();

        return parent::sortUpdate();
    }

    /**
     * @MongoDB\PreRemove()
     */
    public function preRemove()
    {
        $this->resetCard();
        $this->resetCategories();
        $this->resetIngredients();

        return parent::preRemove();
    }
}