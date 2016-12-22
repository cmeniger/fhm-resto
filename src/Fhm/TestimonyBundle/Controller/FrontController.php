<?php
namespace Fhm\TestimonyBundle\Controller;

use Fhm\FhmBundle\Controller\RefFrontController as FhmController;
use Fhm\TestimonyBundle\Document\Testimony;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * @Route("/testimony")
 * ----------------------------------------
 * Class FrontController
 * @package Fhm\TestimonyBundle\Controller
 */
class FrontController extends FhmController
{
    /**
     * FrontController constructor.
     */
    public function __construct()
    {
        self::$repository = "FhmTestimonyBundle:Testimony";
        self::$source = "fhm";
        self::$domain = "FhmTestimonyBundle";
        self::$translation = "testimony";
        self::$class = Testimony::class;
        self::$route = "testimony";
    }

    /**
     * @Route
     * (
     *      path="/",
     *      name="fhm_testimony"
     * )
     * @Template("::FhmTestimony/Front/index.html.twig")
     */
    public function indexAction()
    {
        return parent::indexAction();
    }

    /**
     * @Route
     * (
     *      path="/detail/{id}",
     *      name="fhm_testimony_detail",
     *      requirements={"id"=".+"}
     * )
     * @Template("::FhmTestimony/Front/detail.html.twig")
     */
    public function detailAction($id)
    {
        return parent::detailAction($id);
    }

    /**
     * @Route
     * (
     *      path="/{id}",
     *      name="fhm_testimony_lite",
     *      requirements={"id"=".+"}
     * )
     * @Template("::FhmTestimony/Front/detail.html.twig")
     */
    public function liteAction($id)
    {
        return $this->detailAction($id);
    }
}