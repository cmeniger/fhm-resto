<?php
namespace Fhm\CardBundle\Controller\Category;

use Fhm\CardBundle\Form\Type\Front\Category\CreateType;
use Fhm\CardBundle\Form\Type\Front\Category\UpdateType;
use Fhm\FhmBundle\Controller\RefFrontController as FhmController;
use Fhm\CardBundle\Document\CardCategory;
use Fhm\FhmBundle\Form\Handler\Admin\CreateHandler;
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
     */
    public function __construct()
    {
        self::$repository = "FhmCardBundle:CardCategory";
        self::$source = "fhm";
        self::$domain = "FhmCardBundle";
        self::$translation = "card.category";
        self::$class = CardCategory::class;
        self::$route = "card_category";
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