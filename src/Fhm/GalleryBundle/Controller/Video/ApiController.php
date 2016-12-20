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
     * ApiController constructor.
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