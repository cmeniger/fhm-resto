<?php
namespace Fhm\SliderBundle\Controller;

use Fhm\FhmBundle\Controller\RefFrontController as FhmController;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * @Route("/slider")
 * ------------------------------------------
 * Class FrontController
 * @package Fhm\SliderBundle\Controller
 */
class FrontController extends FhmController
{
    /**
     * FrontController constructor.
     */
    public function __construct()
    {
        self::$repository = "FhmSliderBundle:Slider";
        self::$source = "fhm";
        self::$domain = "FhmSliderBundle";
        self::$translation = "slider";
        self::$route = "slider";
    }

    /**
     * @Route
     * (
     *      path="/detail/{id}",
     *      name="fhm_slider_detail",
     *      requirements={"id"=".+"}
     * )
     * @Template("::FhmSlider/Front/detail.html.twig")
     */
    public function detailAction($id)
    {
        return parent::detailAction($id);
    }

    /**
     * @Route
     * (
     *      path="/{id}",
     *      name="fhm_slider_lite",
     *      requirements={"id"=".+"}
     * )
     * @Template("::FhmSlider/Front/detail.html.twig")
     */
    public function liteAction($id)
    {
        return $this->detailAction($id);
    }
}