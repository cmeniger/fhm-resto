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
     * AdminController constructor.
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
        $translation = "gallery.video",
        $document = GalleryVideo::class,
        $route = 'gallery_video'
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
}