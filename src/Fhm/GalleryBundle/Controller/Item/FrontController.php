<?php
namespace Fhm\GalleryBundle\Controller\Item;

use Fhm\FhmBundle\Controller\RefFrontController as FhmController;
use Fhm\GalleryBundle\Document\Gallery;
use Fhm\GalleryBundle\Document\GalleryItem;
use Fhm\GalleryBundle\Form\Type\Admin\Item\CreateType;
use Fhm\GalleryBundle\Form\Type\Admin\Item\UpdateType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * @Route("/galleryitem")
 * ------------------------------------------
 * Class FrontController
 * @package Fhm\GalleryBundle\Controller\Item
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
        $translation = "gallery.item",
        $document = GalleryItem::class,
        $route = 'gallery_item'
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
     *      name="fhm_gallery_item_lite",
     *      requirements={"id"=".+"}
     * )
     * @Template("::FhmGallery/Front/Item/detail.html.twig")
     */
    public function liteAction($id)
    {
        return $this->detailAction($id);
    }
}