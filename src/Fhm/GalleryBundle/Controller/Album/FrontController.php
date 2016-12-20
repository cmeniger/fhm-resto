<?php
namespace Fhm\GalleryBundle\Controller\Album;

use Fhm\FhmBundle\Controller\RefFrontController as FhmController;
use Fhm\GalleryBundle\Document\Gallery;
use Fhm\GalleryBundle\Document\GalleryAlbum;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * @Route("/galleryalbum")
 * -------------------------------------------
 * Class FrontController
 * @package Fhm\GalleryBundle\Controller\Album
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
        $translation = "gallery.album",
        $document = GalleryAlbum::class,
        $route = 'gallery_album'
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
     *      name="fhm_gallery_album"
     * )
     * @Template("::FhmGallery/Front/Album/index.html.twig")
     */
    public function indexAction()
    {
        return parent::indexAction();
    }

    /**
     * @Route
     * (
     *      path="/detail/{id}",
     *      name="fhm_gallery_album_detail",
     *      requirements={"id"=".+"}
     * )
     * @Template("::FhmGallery/Front/Album/detail.html.twig")
     */
    public function detailAction($id)
    {
        return parent::detailAction($id);
    }

    /**
     * @Route
     * (
     *      path="/{id}",
     *      name="fhm_gallery_album_lite",
     *      requirements={"id"=".+"}
     * )
     * @Template("::FhmGallery/Front/Album/detail.html.twig")
     */
    public function liteAction($id)
    {
        return $this->detailAction($id);
    }
}