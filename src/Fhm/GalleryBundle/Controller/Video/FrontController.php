<?php
namespace Fhm\GalleryBundle\Controller\Video;

use Fhm\FhmBundle\Controller\RefFrontController as FhmController;
use Fhm\GalleryBundle\Document\Gallery;
use Fhm\GalleryBundle\Document\GalleryVideo;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * @Route("/galleryvideo")
 * ------------------------------------------
 * Class FrontController
 * @package Fhm\GalleryBundle\Controller\Video
 */
class FrontController extends FhmController
{
    /**
     * FrontController constructor.
     */
    public function __construct()
    {
        self::$repository = "FhmGalleryBundle:GalleryVideo";
        self::$source = "fhm";
        self::$domain = "FhmGalleryBundle";
        self::$translation = "gallery.video";
        self::$class = GalleryVideo::class;
        self::$route = "gallery_video";
    }

    /**
     * @Route
     * (
     *      path="/{id}",
     *      name="fhm_gallery_video_lite",
     *      requirements={"id"=".+"}
     * )
     * @Template("::FhmGallery/Front/Video/detail.html.twig")
     */
    public function liteAction($id)
    {
        return $this->detailAction($id);
    }

    /**
     * @Route
     * (
     *      path="/detail/{id}",
     *      name="fhm_gallery_video_detail",
     *      requirements={"id"=".+"}
     * )
     * @Template("::FhmGallery/Front/Video/detail.html.twig")
     */
    public function detailAction($id)
    {
        return parent::detailAction($id);
    }
}