<?php
namespace Fhm\CardBundle\Controller;

use Fhm\FhmBundle\Controller\RefFrontController as FhmController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * @Route("/card")
 * ---------------------------------------
 * Class FrontController
 * @package Fhm\CardBundle\Controller
 */
class FrontController extends FhmController
{
    /**
     * FrontController constructor.
     */
    public function __construct()
    {
        self::$repository = "FhmCardBundle:Card";
        self::$source = "fhm";
        self::$domain = "FhmCardBundle";
        self::$translation = "card";
        self::$route = 'card';
    }

    /**
     * @Route
     * (
     *      path="/",
     *      name="fhm_card"
     * )
     * @Template("::FhmCard/Front/index.html.twig")
     */
    public function indexAction()
    {
        return parent::indexAction();
    }

    /**
     * @Route
     * (
     *      path="/detail/{id}",
     *      name="fhm_card_detail",
     *      requirements={"id"=".+"}
     * )
     * @Template("::FhmCard/Front/detail.html.twig")
     */
    public function detailAction($id)
    {
        return parent::detailAction($id);
    }

    /**
     * @Route
     * (
     *      path="/{id}",
     *      name="fhm_card_lite",
     *      requirements={"id"=".+"}
     * )
     * @Template("::FhmCard/Front/detail.html.twig")
     */
    public function liteAction($id)
    {
        return $this->detailAction($id);
    }
}