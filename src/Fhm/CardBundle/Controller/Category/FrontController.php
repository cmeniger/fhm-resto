<?php
namespace Fhm\CardBundle\Controller\Category;

use Fhm\CardBundle\Form\Type\Front\Category\CreateType;
use Fhm\CardBundle\Form\Type\Front\Category\UpdateType;
use Fhm\FhmBundle\Controller\RefFrontController as FhmController;
use Fhm\CardBundle\Document\CardCategory;
use Fhm\FhmBundle\Services\Tools;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * @Route("/cardcategory")
 * -------------------------------------------
 * Class FrontController
 * @package Fhm\CardBundle\Controller\Category
 */
class FrontController extends FhmController
{
    /**
     * FrontController constructor.
     *
     * @param string $repository
     * @param string $source
     * @param string $domain
     * @param string $translation
     * @param string $document
     * @param string $route
     */
    public function __construct(
        $repository = "FhmCardBundle:CardCategory",
        $source = "fhm",
        $domain = "FhmCardBundle",
        $translation = "card.category",
        $document = "CardCategory",
        $route = "card_category"
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
     *      path="/{id}",
     *      name="fhm_card_category_lite",
     *      requirements={"id"=".+"}
     * )
     * @Template("::FhmCard/Front/Category/detail.html.twig")
     */
    public function liteAction($id)
    {
        return $this->detailAction($id);
    }
}