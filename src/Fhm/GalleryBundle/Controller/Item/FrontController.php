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
     */
    public function __construct()
    {
        self::$repository = "FhmGalleryBundle:GalleryItem";
        self::$source = "fhm";
        self::$domain = "FhmGalleryBundle";
        self::$translation = "gallery.item";
        self::$class = GalleryItem::class;
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