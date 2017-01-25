<?php
/**
 * Created by PhpStorm.
 * User: reap
 * Date: 17/01/17
 * Time: 12:02
 */
namespace Fhm\CardBundle\Entity;
use Doctrine\Common\Collections\ArrayCollection;
use Fhm\FhmBundle\Entity\Fhm;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="Fhm\CardBundle\Entity\Repository\CardIngredientRepository")
 * @ORM\Table()
 */
class CardIngredient extends Fhm
{
    /**
     * @ORM\Column(type="boolean")
     */
    protected $default;

    /**
     * @ORM\OneToOne(targetEntity="Fhm\MediaBundle\Entity\Media", orphanRemoval=true)
     */
    protected $image;

    /**
     * @ORM\OneToOne(targetEntity="Fhm\CardBundle\Entity\Card", cascade={"persist"})
     */
    protected $card;

    /**
     * @ORM\ManyToMany(targetEntity="Fhm\CardBundle\Entity\CardProduct", cascade={"persist"}, mappedBy="ingredients")
     */
    protected $products;

    /**
     * @ORM\Column(type="string", length=100)
     */
    protected $sort_card;

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
     * @return \Fhm\CardBundle\Document\Card $card
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
            $this->card->addIngredient($this, false);
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
     * @param \Fhm\CardBundle\Entity\CardProduct $product
     *
     * @return $this
     */
    public function addProduct(\Fhm\CardBundle\Entity\CardProduct $product)
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
     * @param \Fhm\CardBundle\Entity\CardProduct $product
     *
     * @return $this
     */
    public function removeProduct(\Fhm\CardBundle\Entity\CardProduct $product)
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
     * @ORM\PreRemove()
     */
    public function preRemove()
    {
        $this->resetCard();
        $this->resetProducts();

        return parent::preRemove();
    }
}