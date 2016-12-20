<?php
namespace Fhm\GalleryBundle\Controller;

use Fhm\FhmBundle\Controller\RefApiController as FhmController;
use Fhm\GalleryBundle\Document\Gallery;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * @Route("/api/gallery")
 * ------------------------------------
 * Class ApiController
 * @package Fhm\GalleryBundle\Controller
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
     *      path="/",
     *      name="fhm_api_gallery"
     * )
     * @Template("::FhmGallery/Api/index.html.twig")
     */
    public function indexAction()
    {
        return parent::indexAction();
    }

    /**
     * @Route
     * (
     *      path="/autocomplete/",
     *      name="fhm_api_gallery_autocomplete"
     * )
     * @Template("::FhmGallery/Api/autocomplete.html.twig")
     */
    public function autocompleteAction(Request $request)
    {
        return parent::autocompleteAction($request);
    }

    /**
     * @Route
     * (
     *      path="/detail/{template}/{id}",
     *      name="fhm_api_gallery_detail",
     *      requirements={"id"=".+"},
     *      defaults={"template"="gallery"}
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
                $this->trans('gallery.item.error.unknown', array(), 'FhmGalleryBundle')
            );
        } // ERROR - Forbidden
        elseif (!$this->getUser()->hasRole('ROLE_ADMIN') && ($document->getDelete() || !$document->getActive())) {
            throw new HttpException(403, $this->trans('gallery.item.error.forbidden', array(), 'FhmGalleryBundle'));
        }

        return new Response(
            $this->renderView(
                "::FhmGallery/Template/".$template.".html.twig",
                array(
                    'document' => $document,
                    'items' => $this->get('fhm_tools')->dmRepository("FhmGalleryBundle:GalleryItem")->getByGroupAll(
                        $document
                    ),
                    'videos' => $this->get('fhm_tools')->dmRepository("FhmGalleryBundle:GalleryVideo")->getByGroupAll(
                        $document
                    ),
                )
            )
        );
    }
}

