<?php
namespace Fhm\GalleryBundle\Controller\Album;

use Fhm\FhmBundle\Controller\RefApiController as FhmController;
use Fhm\GalleryBundle\Document\Gallery;
use Fhm\GalleryBundle\Document\GalleryAlbum;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * @Route("/api/gallery/album")
 * -------------------------------------------
 * Class ApiController
 * @package Fhm\GalleryBundle\Controller\Album
 */
class ApiController extends FhmController
{
    /**
     * ApiController constructor.
     */
    public function __construct()
    {
        self::$repository = "FhmGalleryBundle:GalleryAlbum";
        self::$source = "fhm";
        self::$domain = "FhmGalleryBundle";
        self::$translation = "gallery.album";
        self::$class = GalleryAlbum::class;
        self::$route = "gallery_album";
    }

    /**
     * @Route
     * (
     *      path="/",
     *      name="fhm_api_gallery_album"
     * )
     * @Template("::FhmGallery/Api/Album/index.html.twig")
     */
    public function indexAction()
    {
        return parent::indexAction();
    }

    /**
     * @Route
     * (
     *      path="/autocomplete/",
     *      name="fhm_api_gallery_album_autocomplete"
     * )
     * @Template("::FhmGallery/Api/Album/autocomplete.html.twig")
     */
    public function autocompleteAction(Request $request)
    {
        return parent::autocompleteAction($request);
    }

    /**
     * @Route
     * (
     *      path="/detail/{template}/{id}",
     *      name="fhm_api_gallery_album_detail",
     *      requirements={"id"=".+"},
     *      defaults={"template"="album"}
     * )
     */
    public function detailAction($template, $id)
    {
        $document = $this->get('fhm_tools')->dmRepository(self::$repository)->getById($id);
        $document = ($document) ? $document : $this->get('fhm_tools')->dmRepository(self::$repository)->getByAlias($id);
        $document = ($document) ? $document : $this->get('fhm_tools')->dmRepository(self::$repository)->getByName($id);
        // ERROR - unknown
        if ($document == "") {
            throw $this->createNotFoundException(
                $this->trans('gallery.album.error.unknown', array(), 'FhmGalleryBundle')
            );
        } // ERROR - Forbidden
        elseif (!$this->getUser()->hasRole('ROLE_ADMIN') && ($document->getDelete() || !$document->getActive())) {
            throw new HttpException(
                403,
                $this->trans('gallery.album.error.forbidden', array(), 'FhmGalleryBundle')
            );
        }

        return new Response(
            $this->renderView(
                "::FhmGallery/Template/".$template.".html.twig",
                array(
                    'document' => $document,
                    'galleries' => $this->get('fhm_tools')->dmRepository("FhmGalleryBundle:Gallery")->getByGroupAll(
                        $document
                    ),
                )
            )
        );
    }
}