<?php

namespace Fhm\GalleryBundle\Controller;

use Fhm\FhmBundle\Controller\RefApiController as FhmController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * @Route("/api/gallery")
 * ------------------------------------
 * Class ApiController
 *
 * @package Fhm\GalleryBundle\Controller
 */
class ApiController extends FhmController
{
    /**
     * ApiController constructor.
     */
    public function __construct()
    {
        self::$repository  = "FhmGalleryBundle:Gallery";
        self::$source      = "fhm";
        self::$domain      = "FhmGalleryBundle";
        self::$translation = "gallery";
        self::$route       = "gallery";
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
        if($id == 'demo')
        {
            return new Response(
                $this->renderView(
                    "::FhmGallery/Template/" . $template . ".html.twig",
                    array(
                        'document' => null,
                        'items'    => null,
                        'videos'   => null,
                        'demo'     => true
                    )
                )
            );
        }
        $object = $this->get('fhm_tools')->dmRepository(self::$repository)->getById($id);
        $object = ($object) ?: $this->get('fhm_tools')->dmRepository(self::$repository)->getByAlias($id);
        $object = ($object) ?: $this->get('fhm_tools')->dmRepository(self::$repository)->getByName($id);
        // ERROR - unknown
        if($object == "")
        {
            throw $this->createNotFoundException(
                $this->trans('gallery.item.error.unknown', array(), 'FhmGalleryBundle')
            );
        }
        // ERROR - Forbidden
        elseif($object->getDelete() || !$object->getActive())
        {
            throw new HttpException(403, $this->trans('gallery.item.error.forbidden', array(), 'FhmGalleryBundle'));
        }

        return new Response(
            $this->renderView(
                "::FhmGallery/Template/" . $template . ".html.twig",
                array(
                    'document' => $object,
                    'items'    => $this->get('fhm_tools')->dmRepository("FhmGalleryBundle:GalleryItem")->getByGroupAll(
                        $object
                    ),
                    'videos'   => $this->get('fhm_tools')->dmRepository("FhmGalleryBundle:GalleryVideo")->getByGroupAll(
                        $object
                    ),
                )
            )
        );
    }
}

