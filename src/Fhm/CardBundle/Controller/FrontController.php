<?php
namespace Fhm\CardBundle\Controller;

use Fhm\FhmBundle\Controller\RefFrontController as FhmController;
use Fhm\CardBundle\Document\Card;
use Symfony\Component\HttpFoundation\Request;
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
     * @param string $repository
     * @param string $source
     * @param string $domaine
     * @param string $translation
     * @param string $document
     * @param string $route
     */
    public function __construct(
        $repository = "FhmCardBundle:Card",
        $source = "fhm",
        $domaine = "FhmCardBundle",
        $translation = "card",
        $document = Card::class,
        $route = "card"
    ) {
        self::$repository = $repository;
        self::$source = $source;
        self::$domain = $domaine;
        self::$translation = $translation;
        self::$document = new $document();
        self::$class = get_class(self::$document);
        self::$route = $route;
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