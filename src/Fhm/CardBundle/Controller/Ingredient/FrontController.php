<?php
namespace Fhm\CardBundle\Controller\Ingredient;

use Fhm\CardBundle\Form\Type\Front\Ingredient\CreateType;
use Fhm\CardBundle\Form\Type\Front\Ingredient\UpdateType;
use Fhm\FhmBundle\Controller\RefFrontController as FhmController;
use Fhm\CardBundle\Document\CardIngredient;
use Fhm\FhmBundle\Services\Tools;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * @Route("/cardingredient")
 * --------------------------------------------
 * Class FrontController
 * @package Fhm\CardBundle\Controller\Ingredient
 */
class FrontController extends FhmController
{
    /**
     * AdminController constructor.
     *
     * @param string $repository
     * @param string $source
     * @param string $domain
     * @param string $translation
     * @param string $document
     * @param string $route
     */
    public function __construct(
        $repository = "FhmCardBundle:CardIngredient",
        $source = "fhm",
        $domain = "FhmCardBundle",
        $translation = "card.ingredient",
        $document = CardIngredient::class,
        $route = "card_ingredient"
    ) {
        self::$repository = $repository;
        self::$source = $source;
        self::$domain = $domain;
        self::$translation = $translation;
        self::$document = new $document();
        self::$class = get_class(self::$document);
        self::$route = $route;
    }

    /**
     * @Route
     * (
     *      path="/",
     *      name="fhm_card_ingredient"
     * )
     * @Template("::FhmCard/Front/Ingredient/index.html.twig")
     */
    public function indexAction()
    {
        return parent::indexAction();
    }

    /**
     * @Route
     * (
     *      path="/detail/{id}",
     *      name="fhm_card_ingredient_detail",
     *      requirements={"id"=".+"}
     * )
     * @Template("::FhmCard/Front/Ingredient/detail.html.twig")
     */
    public function detailAction($id)
    {
        return parent::detailAction($id);
    }

    /**
     * @Route
     * (
     *      path="/{id}",
     *      name="fhm_card_ingredient_lite",
     *      requirements={"id"=".+"}
     * )
     * @Template("::FhmCard/Front/Ingredient/detail.html.twig")
     */
    public function liteAction($id)
    {
        return $this->detailAction($id);
    }
}