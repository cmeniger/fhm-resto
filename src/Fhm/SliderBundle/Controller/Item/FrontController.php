<?php
namespace Fhm\SliderBundle\Controller\Item;

use Fhm\FhmBundle\Controller\RefFrontController as FhmController;
use Fhm\SliderBundle\Document\Slider;
use Fhm\SliderBundle\Document\SliderItem;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * @Route("/slideritem")
 * -----------------------------------------
 * Class FrontController
 * @package Fhm\SliderBundle\Controller\Item
 */
class FrontController extends FhmController
{
    /**
     * FrontController constructor.
     */
    public function __construct()
    {
        self::$repository = "FhmSliderBundle:SliderItem";
        self::$source = "fhm";
        self::$domain = "FhmSliderBundle";
        self::$translation = "slider.item";
        self::$class = SliderItem::class;
        self::$route = "slider_item";
    }

    /**
     * @Route
     * (
     *      path="/detail/{id}",
     *      name="fhm_slider_item_detail",
     *      requirements={"id"=".+"}
     * )
     * @Template("::FhmSlider/Front/Item/detail.html.twig")
     */
    public function detailAction($id)
    {
        return parent::detailAction($id);
    }

    /**
     * @Route
     * (
     *      path="/{id}",
     *      name="fhm_slider_item_lite",
     *      requirements={"id"=".+"}
     * )
     * @Template("::FhmSlider/Front/Item/detail.html.twig")
     */
    public function liteAction($id)
    {
        return $this->detailAction($id);
    }
}