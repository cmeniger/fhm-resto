<?php
namespace Fhm\GalleryBundle\Controller\Album;

use Fhm\FhmBundle\Controller\RefFrontController as FhmController;
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
     */
    public function __construct()
    {
        self::$repository = "FhmGalleryBundle:GalleryAlbum";
        self::$source = "fhm";
        self::$domain = "FhmGalleryBundle";
        self::$translation = "gallery.album";
        self::$route = "gallery_album";
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