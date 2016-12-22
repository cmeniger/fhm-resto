<?php
namespace Fhm\GalleryBundle\Controller\Video;

use Fhm\FhmBundle\Controller\RefApiController as FhmController;
use Fhm\GalleryBundle\Document\Gallery;
use Fhm\GalleryBundle\Document\GalleryVideo;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * @Route("/api/gallery/video")
 * ------------------------------------------
 * Class ApiController
 * @package Fhm\GalleryBundle\Controller\Video
 */
class ApiController extends FhmController
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
     *      path="/",
     *      name="fhm_api_gallery_video"
     * )
     * @Template("::FhmGallery/Api/Video/index.html.twig")
     */
    public function indexAction()
    {
        return parent::indexAction();
    }

    /**
     * @Route
     * (
     *      path="/autocomplete/",
     *      name="fhm_api_gallery_video_autocomplete"
     * )
     * @Template("::FhmGallery/Api/Video/autocomplete.html.twig")
     */
    public function autocompleteAction(Request $request)
    {
        return parent::autocompleteAction($request);
    }
}