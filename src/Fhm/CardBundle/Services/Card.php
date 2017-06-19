<?php

namespace Fhm\CardBundle\Services;

use Doctrine\Common\Collections\ArrayCollection;
use Fhm\FhmBundle\Services\Tools;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Session\Session;

/**
 * Class Card
 *
 * @package Fhm\CardBundle\Services
 */
class Card
{
    private $categories;
    private $products;
    private $ingredients;
    private $tools;

    /**
     * Card constructor.
     *
     * @param Tools $tools
     */
    public function __construct(Tools $tools)
    {
        $this->tools = $tools;
        $this->__initialization();
    }

    /**
     * @param $parent
     *
     * @return \Fhm\CardBundle\Document\Card
     */
    public function generate($parent)
    {
        $this->__initialization();
        // Card
        $card = new \Fhm\CardBundle\Document\Card();
        $card->setUserCreate($parent->getUserCreate());
        $card->setName($parent->getName());
        $card->setAlias($this->getAlias('', $parent->getName()));
        $card->setActive(true);
        $card->setParent($parent);
        $this->tools->dmPersist($card);
        // Categories
        $categories = $this->tools->dmRepository('FhmCardBundle:CardCategory')->getDefault();
        foreach($categories as $category)
        {
            if($category->getParents()->count() == 0)
            {
                $this->__duplicateCategory($card, $category);
            }
        }
        // Products
        $products = $this->tools->dmRepository('FhmCardBundle:CardProduct')->getDefault();
        foreach($products as $product)
        {
            $this->__duplicateProduct($card, $product);
        }
        // Ingredients
        $ingredients = $this->tools->dmRepository('FhmCardBundle:CardIngredient')->getDefault();
        foreach($ingredients as $ingredient)
        {
            $this->__duplicateIngredient($card, $ingredient);
        }

        return $card;
    }

    /**
     * @param \Fhm\CardBundle\Document\Card         $card
     * @param \Fhm\CardBundle\Document\CardCategory $category
     * @param null                                  $parent
     *
     * @return $this
     */
    private function __duplicateCategory(
        \Fhm\CardBundle\Document\Card $card,
        \Fhm\CardBundle\Document\CardCategory $category,
        $parent = null
    ) {
        if($this->categories->contains($category))
        {
            return $this;
        }
        else
        {
            $this->categories->add($category);
        }
        // Category
        $data = new \Fhm\CardBundle\Document\CardCategory();
        $data->setName($category->getName());
        $data->setDescription($category->getDescription());
        $data->setOrder($category->getOrder());
        $data->setSeoTitle($category->getSeoTitle());
        $data->setSeoDescription($category->getSeoDescription());
        $data->setSeoKeywords($category->getSeoKeywords());
        $data->setPrice($category->getPrice());
        $data->setCurrency($category->getCurrency());
        $data->setMenu($category->getMenu());
        $data->setImage($category->getImage());
        $data->setDefault(false);
        $data->setActive(true);
        $data->setDelete(false);
        $data->setCard($card);
        if($parent)
        {
            $data->addParent($parent);
        }
        $this->tools->dmPersist($data);
        // Card
        $card->addCategory($data, false);
        $this->tools->dmPersist($card);
        // Sons
        foreach($category->getSons() as $son)
        {
            $this->__duplicateCategory($card, $son, $data);
        }
        // Products
        foreach($category->getProducts() as $product)
        {
            $this->__duplicateProduct($card, $product, $data);
        }

        return $this;
    }

    /**
     * @param \Fhm\CardBundle\Document\Card        $card
     * @param \Fhm\CardBundle\Document\CardProduct $product
     * @param null                                 $parent
     *
     * @return $this
     */
    private function __duplicateProduct(
        \Fhm\CardBundle\Document\Card $card,
        \Fhm\CardBundle\Document\CardProduct $product,
        $parent = null
    ) {
        if($this->products->contains($product))
        {
            return $this;
        }
        else
        {
            $this->products->add($product);
        }
        // Product
        $data = new \Fhm\CardBundle\Document\CardProduct();
        $data->setName($product->getName());
        $data->setDescription($product->getDescription());
        $data->setOrder($product->getOrder());
        $data->setSeoTitle($product->getSeoTitle());
        $data->setSeoDescription($product->getSeoDescription());
        $data->setSeoKeywords($product->getSeoKeywords());
        $data->setPrice($product->getPrice());
        $data->setCurrency($product->getCurrency());
        $data->setForward($product->getForward());
        $data->setImage($product->getImage());
        $data->setDefault(false);
        $data->setActive(true);
        $data->setDelete(false);
        $data->setCard($card);
        if($parent)
        {
            $data->addCategory($parent);
        }
        $this->tools->dmPersist($data);
        // Ingredients
        foreach($product->getIngredients() as $ingredient)
        {
            $this->__duplicateIngredient($card, $ingredient, $data);
        }

        return $this;
    }

    /**
     * @param \Fhm\CardBundle\Document\Card           $card
     * @param \Fhm\CardBundle\Document\CardIngredient $ingredient
     * @param null                                    $parent
     *
     * @return $this
     */
    private function __duplicateIngredient(
        \Fhm\CardBundle\Document\Card $card,
        \Fhm\CardBundle\Document\CardIngredient $ingredient,
        $parent = null
    ) {
        if($this->ingredients->contains($ingredient))
        {
            return $this;
        }
        else
        {
            $this->ingredients->add($ingredient);
        }
        // Category
        $data = new \Fhm\CardBundle\Document\CardIngredient();
        $data->setName($ingredient->getName());
        $data->setDescription($ingredient->getDescription());
        $data->setOrder($ingredient->getOrder());
        $data->setSeoTitle($ingredient->getSeoTitle());
        $data->setSeoDescription($ingredient->getSeoDescription());
        $data->setSeoKeywords($ingredient->getSeoKeywords());
        $data->setImage($ingredient->getImage());
        $data->setDefault(false);
        $data->setActive(true);
        $data->setDelete(false);
        $data->setCard($card);
        if($parent)
        {
            $data->addProduct($parent);
        }
        $this->tools->dmPersist($data);

        return $this;
    }

    /**
     * @return $this
     */
    private function __initialization()
    {
        $this->categories  = new ArrayCollection();
        $this->products    = new ArrayCollection();
        $this->ingredients = new ArrayCollection();

        return $this;
    }
}