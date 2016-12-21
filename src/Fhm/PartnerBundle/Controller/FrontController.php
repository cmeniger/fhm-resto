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
     * @param string $repository
     * @param string $source
     * @param string $domain
     * @param string $translation
     * @param string $document
     * @param string $route
     */
    public function __construct(
        $repository = "FhmPartnerBundle:Partner",
        $source = "fhm",
        $domain = "FhmPartnerBundle",
        $translation = "partner",
        $document = Partner::class,
        $route = 'partner'
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