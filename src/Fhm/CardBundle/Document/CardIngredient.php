<?php
namespace Fhm\CardBundle\Document;

use Fhm\FhmBundle\Document\Fhm as FhmFhm;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Doctrine\Common\Collections\ArrayCollection;
use Fhm\MediaBundle\Document\Media;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * CardIngredient
 * @MongoDB\Document(repositoryClass="Fhm\CardBundle\Document\Repository\CardIngredientRepository")
 */
class CardIngredient extends FhmFhm
{
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
    protected $sort_product;

    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->products     = new ArrayCollection();
        $this->sort_card    = "";
        $this->sort_product = 0;
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
            $this->card->addIngredient($this, false);
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
            $this->card->removeIngredient($this, false);
        }

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
        foreach($products as $product)
        {
            $product->addIngredient($this);
        }
        $this->products = $products;

        return $this;
    }

    /**
     * @param \Fhm\CardBundle\Document\CardProduct $product
     *
     * @return $this
     */
    public function addProduct(\Fhm\CardBundle\Document\CardProduct $product)
    {
        if(!$this->products->contains($product))
        {
            $this->products->add($product);
            $product->addIngredient($this);
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
            $product->removeIngredient($this);
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
            $product->removeIngredient($this);
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
        $this->sort_product = $this->products->count();

        return parent::sortUpdate();
    }

    /**
     * @MongoDB\PreRemove()
     */
    public function preRemove()
    {
        $this->resetCard();
        $this->resetProducts();

        return parent::preRemove();
    }
}