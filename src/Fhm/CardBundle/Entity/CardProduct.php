<?php
/**
 * Created by PhpStorm.
 * User: reap
 * Date: 17/01/17
 * Time: 12:17
 */

namespace Fhm\CardBundle\Entity;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity
 * @ORM\Table()
 */
class CardProduct
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
     * @ORM\Column(type="boolean")
     */
    protected $forward;

    /**
     * @ORM\Column(type="boolean")
     */
    protected $default;

    /**
     * @ORM\OneToOne(targetEntity="Media")
     */
    protected $image;

    /**
     * @ORM\OneToOne(targetEntity="Card")
     */
    protected $card;

    /**
     * @ORM\OneToMany(targetEntity="CardCategory")
     */
    protected $categories;

    /**
     * @ORM\OneToMany(targetEntity="CardIngredient")
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
     * @return \Fhm\MediaBundle\Entity\Media $image
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * @param \Fhm\MediaBundle\Entity\Media $image
     *
     * @return $this
     */
    public function setImage($image)
    {
        $this->image = ($image instanceof \Fhm\MediaBundle\Entity\Media) ? $image : null;

        return $this;
    }

    /**
     * @return \Fhm\CardBundle\Entity\Card $card
     */
    public function getCard()
    {
        return $this->card;
    }

    /**
     * @param \Fhm\CardBundle\Entity\Card $card
     *
     * @return $this
     */
    public function setCard($card)
    {
        $this->resetCard();
        $this->card = ($card instanceof \Fhm\CardBundle\Entity\Card) ? $card : null;
        if($this->card instanceof \Fhm\CardBundle\Entity\Card)
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
        if($this->card instanceof \Fhm\CardBundle\Entity\Card)
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
     * @param \Fhm\CardBundle\Entity\CardCategory $category
     *
     * @return $this
     */
    public function addCategory(\Fhm\CardBundle\Entity\CardCategory $category)
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
     * @param \Fhm\CardBundle\Entity\CardCategory $category
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
     * @param \Fhm\CardBundle\Entity\CardIngredient $ingredient
     *
     * @return $this
     */
    public function addIngredient(\Fhm\CardBundle\Entity\CardIngredient $ingredient)
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
     * @param \Fhm\CardBundle\Entity\CardIngredient $ingredient
     *
     * @return $this
     */
    public function removeIngredient(\Fhm\CardBundle\Entity\CardIngredient $ingredient)
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