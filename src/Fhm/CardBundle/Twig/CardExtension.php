<?php
namespace Fhm\CardBundle\Twig;

use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\HttpKernel\KernelInterface;

/**
 * Class CardExtension
 * @package Fhm\CardBundle\Twig
 */
class CardExtension extends \Twig_Extension
{
    /**
     * {@inheritdoc}
     */
    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('cardCategoryBreadcrumbs', array($this, 'getCategoryBreadcrumbs')),
            new \Twig_SimpleFilter('cardCategoryRoute', array($this, 'getCategoryRoute')),
            new \Twig_SimpleFilter('cardCategoryInline', array($this, 'getCategoryInline')),
            new \Twig_SimpleFilter('cardIngredientHtml', array($this, 'getIngredientHtml')),
            new \Twig_SimpleFilter('cardIngredientString', array($this, 'getIngredientString')),
            new \Twig_SimpleFilter('cardTreeProduct', array($this, 'getTreeProduct')),
        );
    }

    /**
     * Returns the name of the extension.
     *
     * @return string The extension name
     */
    public function getName()
    {
        return 'card_extension';
    }

    /**
     * @param      $category
     * @param bool $multiple
     *
     * @return string
     */
    public function getCategoryBreadcrumbs($category, $multiple = true)
    {
        $routes = array();
        $used = new ArrayCollection();
        $html = "";
        foreach ($category->getParents() as $parent) {
            $array = array($parent);
            $this->__route($parent, $array, $routes, $used);
        }
        if (count($routes) > 0) {
            foreach ($routes as $route) {
                $html .= "<ul class='card category breadcrumbs'>";
                foreach ($route as $object) {
                    $html .= "<li class=''>";
                    $html .= "<a href='#' category-id='".$object->getId()."'>".$object->getName()."</a></li>";
                }
                $html .= "<li class='current'>";
                $html .= "<a href='#' category-id='".$category->getId()."'>".$category->getName()."</a></li>";
                $html .= "</ul>";
                if (!$multiple) {
                    break;
                }
            }
        } else {
            $html .= "<ul class='card category breadcrumbs'>";
            $html .= "<li class='current'><a href='#' category-id='".$category->getId()."'>".$category->getName();
            $html .= "</a></li></ul>";
        }

        return $html;
    }

    /**
     * @param      $category
     * @param bool $multiple
     *
     * @return string
     */
    public function getCategoryRoute($category, $multiple = true)
    {
        $routes = array();
        $used = new ArrayCollection();
        $html = "";
        foreach ($category->getParents() as $parent) {
            $array = array($parent);
            $this->__route($parent, $array, $routes, $used);
        }
        if (count($routes) > 0) {
            foreach ($routes as $route) {
                $html .= "<div class='card category route'>";
                foreach ($route as $object) {
                    $html .= $object->getName()."<span class='separator'>></span>";
                }
                $html .= $category->getName();
                $html .= "</div>";
                if (!$multiple) {
                    break;
                }
            }
        } else {
            $html .= "<div class='card category route'>";
            $html .= $category->getName();
            $html .= "</div>";
        }

        return $html;
    }

    /**
     * @param      $category
     * @param bool $multiple
     *
     * @return string
     */
    public function getCategoryInline($category, $multiple = true)
    {
        $routes = array();
        $used = new ArrayCollection();
        $html = "";
        foreach ($category->getParents() as $parent) {
            $array = array($parent);
            $this->__route($parent, $array, $routes, $used);
        }
        if (count($routes) > 0) {
            $html .= "<div class='card category route'>";
            foreach ($routes as $route) {
                foreach ($route as $object) {
                    $html .= $object->getName()."<span class='separator'>></span>";
                }
                $html .= $category->getName();
                if (!$multiple) {
                    break;
                }
                $html .= $route === end($routes) ? "" : "<span class='separator master'>/</span>";
            }
            $html .= "</div>";
        } else {
            $html .= "<div class='card category route'>";
            $html .= $category->getName();
            $html .= "</div>";
        }

        return $html;
    }

    /**
     * @param $product
     *
     * @return string
     */
    public function getIngredientHtml($product)
    {
        $html = "";

        return $html;
    }

    /**
     * @param $product
     *
     * @return string
     */
    public function getIngredientString($product)
    {
        $array = array();
        foreach ($product->getIngredients() as $ingredient) {
            $array[] = $ingredient->getName();
        }

        return implode(', ', $array);
    }

    /**
     * @param $category
     * @param $route
     * @param $all
     * @param $used
     *
     * @return $this
     */
    private function __route($category, &$route, &$all, &$used)
    {
        $first = true;
        if ($used->contains($category)) {
            return false;
        } else {
            $used->add($category);
        }
        if ($category->getParents()->count() == 0) {
            array_push($all, $route);
        } else {
            foreach ($category->getParents() as $parent) {
                array_unshift($route, $parent);
                if ($first) {
                    $first = false;
                    $this->__route($parent, $route, $all, $used);
                } else {
                    $current = $route;
                    $this->__route($parent, $current, $all, $used);
                }
            }
        }

        return $this;
    }
}
