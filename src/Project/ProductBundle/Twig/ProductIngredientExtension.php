<?php

namespace Project\ProductBundle\Twig;

use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class ProductIngredientExtension extends \Twig_Extension
{
    protected $container;

    /**
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * {@inheritdoc}
     */
    public function getFilters()
    {
        return array(
            'ingredientBreadcrumbs' => new \Twig_Filter_Method($this, 'getBreadcrumbs'),
            'ingredientRoute'       => new \Twig_Filter_Method($this, 'getRoute'),
            'ingredientLabel'       => new \Twig_Filter_Method($this, 'getIngredient'),
            'ingredientBloc'        => new \Twig_Filter_Method($this, 'getBloc'),
        );
    }

    /**
     * Returns the name of the extension.
     *
     * @return string The extension name
     */
    public function getName()
    {
        return 'product_ingredient_extension';
    }

    /**
     * @param \Project\ProductBundle\Entity\ProductIngredient $ingredient
     *
     * @return string
     */
    public function getBreadcrumbs($ingredient)
    {
        $current = $ingredient->getId();
        $html    = "";
        while($ingredient)
        {
            if($current == $ingredient->getId())
            {
                $html = "<li class='current'><a href='#' product-ingredient='" . $ingredient->getId() . "'>" . $ingredient->getName() . "</a></li>" . $html;
            }
            else
            {
                $html = "<li class=''><a href='#' product-ingredient='" . $ingredient->getId() . "'>" . $ingredient->getName() . "</a></li>" . $html;
            }
            $ingredient = $ingredient->getParent() ? $ingredient->getParent() : false;
        }

        return "<ul class='ingredient breadcrumbs'>" . $html . "</ul>";
    }

    /**
     * @param \Project\ProductBundle\Entity\ProductIngredient $ingredient
     *
     * @return string
     */
    public function getRoute($ingredient)
    {
        $tabs = $ingredient->getRouteArray();
        $html = "<ul class='ingredient'>";
        foreach($tabs as $tab)
        {
            if(end($tabs) === $tab)
            {
                $color = $ingredient->getColor() ? "style='background-color:" . $ingredient->getColor() . ";'" : "";
                $html .= "<li><span class='label round current' " . $color . ">" . $tab . "</span></li>";
            }
            else
            {
                $html .= "<li><span class='label round parent'>" . $tab . "</span></li>";
            }
        }
        $html .= "</ul>";

        return $html;
    }

    /**
     * @param \Project\ProductBundle\Entity\ProductIngredient $ingredient
     *
     * @return string
     */
    public function getIngredient($ingredient)
    {
        $color = $ingredient->getColor() ? "style='background-color:" . $ingredient->getColor() . ";'" : "";

        return "<span class='label round ingredient' " . $color . ">" . $ingredient->getName() . "</span>";
    }

    /**
     * @param \Project\ProductBundle\Entity\ProductIngredient $ingredient
     *
     * @return string
     */
    public function getBloc($ingredient)
    {
        $color = $ingredient->getColor() ? "style='background-color:" . $ingredient->getColor() . ";'" : "";

        return "<div class='data'></div>";

        //return "<div class='data' product-ingredient='" . $ingredient->getId() . "' title='" . $ingredient->getName() . "' " . $color . "><a href='#'><span><i class='fa " . (($ingredient->getShare() || $ingredient->getGlobal()) ? 'fa-ingredients' : 'fa-ingredient') . "'></i></span>" . $ingredient->getName() . "</a>";
    }
}
