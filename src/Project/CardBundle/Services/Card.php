<?php
namespace Fhm\CardBundle\Services;

use Fhm\FhmBundle\Controller\FhmController;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Session\Session;

/**
 * Class Card
 *
 * @package Fhm\CardBundle\Services
 */
class Card extends FhmController
{
    private $categories;
    private $products;
    private $ingredients;

    /**
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container   = $container;
        $this->categories  = new ArrayCollection();
        $this->products    = new ArrayCollection();
        $this->ingredients = new ArrayCollection();
        parent::__construct();
    }

    /**
     * @param $parent
     */
    public function generate($parent)
    {
        // Card
        $card = new \Fhm\CardBundle\Document\Card();
        $card->setUserCreate($parent->getUserCreate());
        $card->setName($parent->getName());
        $card->setAlias($this->getAlias('', $parent->getName()));
        $card->setLanguages($parent->getLanguages());
        $card->setActive(true);
        $card->setParent($parent);
        $this->dmPersist($card);
        // Categories
        $categories = $this->dmRepository('FhmCardBundle:CardCategory')->getDefault();
        foreach($categories as $category)
        {
            if($category->getParents()->count() == 0)
            {
                $this->_duplicateCategory($card, $category);
            }
        }
        // Products
        $products = $this->dmRepository('FhmCardBundle:CardProduct')->getDefault();
        foreach($products as $product)
        {
            $this->_duplicateProduct($card, $product);
        }
        // Ingredients
        $ingredients = $this->dmRepository('FhmCardBundle:CardIngredient')->getDefault();
        foreach($ingredients as $ingredient)
        {
            $this->_duplicateIngredient($card, $ingredient);
        }
    }

    /**
     * @param \Fhm\CardBundle\Document\Card         $card
     * @param \Fhm\CardBundle\Document\CardCategory $category
     * @param null                                  $parent
     *
     * @return $this
     */
    private function _duplicateCategory(\Fhm\CardBundle\Document\Card $card, \Fhm\CardBundle\Document\CardCategory $category, $parent = null)
    {
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
        $data->setGrouping($category->getGrouping());
        $data->setLanguages($category->getLanguages());
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
        $this->dmPersist($data);
        // Card
        $card->addCategory($data, false);
        $this->dmPersist($card);
        // Sons
        foreach($category->getSons() as $son)
        {
            $this->_duplicateCategory($card, $son, $data);
        }
        // Products
        foreach($category->getProducts() as $product)
        {
            $this->_duplicateProduct($card, $product, $data);
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
    private function _duplicateProduct(\Fhm\CardBundle\Document\Card $card, \Fhm\CardBundle\Document\CardProduct $product, $parent = null)
    {
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
        $data->setGrouping($product->getGrouping());
        $data->setLanguages($product->getLanguages());
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
        $this->dmPersist($data);
        // Ingredients
        foreach($product->getIngredients() as $ingredient)
        {
            $this->_duplicateIngredient($card, $ingredient, $data);
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
    private function _duplicateIngredient(\Fhm\CardBundle\Document\Card $card, \Fhm\CardBundle\Document\CardIngredient $ingredient, $parent = null)
    {
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
        $data->setGrouping($ingredient->getGrouping());
        $data->setLanguages($ingredient->getLanguages());
        $data->setImage($ingredient->getImage());
        $data->setDefault(false);
        $data->setActive(true);
        $data->setDelete(false);
        $data->setCard($card);
        if($parent)
        {
            $data->addProduct($parent);
        }
        $this->dmPersist($data);

        return $this;
    }
}