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
     * @param string $repository
     * @param string $source
     * @param string $domain
     * @param string $translation
     * @param string $document
     * @param string $route
     */
    public function __construct(
        $repository = "FhmGalleryBundle:Gallery",
        $source = "fhm",
        $domain = "FhmGalleryBundle",
        $translation = "gallery",
        $document = Gallery::class,
        $route = 'gallery'
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