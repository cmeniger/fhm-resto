<?php
namespace Fhm\PartnerBundle\Controller;

use Fhm\FhmBundle\Controller\RefFrontController as FhmController;
use Fhm\PartnerBundle\Document\Partner;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * @Route("/partner")
 * ------------------------------------
 * Class FrontController
 * @package Fhm\PartnerBundle\Controller
 */
class FrontController extends FhmController
{
    /**
     * AdminController constructor.
     */
    public function __construct()
    {
        self::$repository = "FhmPartnerBundle:Partner";
        self::$source = "fhm";
        self::$domain = "FhmPartnerBundle";
        self::$translation = "partner";
        self::$class = Partner::class;
        self::$route = "partner";
    }

    /**
     * @Route
     * (
     *      path="/",
     *      name="fhm_partner"
     * )
     * @Template("::FhmPartner/Front/index.html.twig")
     */
    public function indexAction()
    {
        return parent::indexAction();
    }

    /**
     * @Route
     * (
     *      path="/detail/{id}",
     *      name="fhm_partner_detail",
     *      requirements={"id"=".+"}
     * )
     * @Template("::FhmPartner/Front/detail.html.twig")
     */
    public function detailAction($id)
    {
        return parent::detailAction($id);
    }

    /**
     * @Route
     * (
     *      path="/{id}",
     *      name="fhm_partner_lite",
     *      requirements={"id"=".+"}
     * )
     * @Template("::FhmPartner/Front/detail.html.twig")
     */
    public function liteAction($id)
    {
        return $this->detailAction($id);
    }
}