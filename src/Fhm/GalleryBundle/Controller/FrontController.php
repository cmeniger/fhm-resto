<?php
namespace Fhm\GalleryBundle\Controller;

use Fhm\FhmBundle\Controller\RefFrontController as FhmController;
use Fhm\GalleryBundle\Document\Gallery;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * @Route("/gallery")
 * -------------------------------------
 * Class FrontController
 * @package Fhm\GalleryBundle\Controller
 */
class FrontController extends FhmController
{
    /**
     * FrontController constructor.
     */
    public function __construct()
    {
        self::$repository = "FhmGalleryBundle:Gallery";
        self::$source = "fhm";
        self::$domain = "FhmGalleryBundle";
        self::$translation = "gallery";
        self::$class = Gallery::class;
        self::$route = "gallery";
    }

    /**
     * @Route
     * (
     *      path="/detail/{id}",
     *      name="fhm_gallery_detail",
     *      requirements={"id"=".+"}
     * )
     * @Template("::FhmGallery/Front/detail.html.twig")
     */
    public function detailAction($id)
    {
        return parent::detailAction($id);
    }

    /**
     * @Route
     * (
     *      path="/{id}",
     *      name="fhm_gallery_lite",
     *      requirements={"id"=".+"}
     * )
     * @Template("::FhmGallery/Front/detail.html.twig")
     */
    public function liteAction($id)
    {
        return $this->detailAction($id);
    }
}