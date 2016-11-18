<?php
namespace Fhm\GalleryBundle\Controller\Album;

use Fhm\FhmBundle\Controller\RefApiController as FhmController;
use Fhm\GalleryBundle\Document\Gallery;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * @Route("/api/gallery/album")
 */
class ApiController extends FhmController
{
    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct('Fhm', 'Gallery', 'gallery_album', 'GalleryAlbum');
        $this->translation = array('FhmGalleryBundle', 'gallery.album');
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
        $document = $this->dmRepository()->getById($id);
        $document = ($document) ? $document : $this->dmRepository()->getByAlias($id);
        $document = ($document) ? $document : $this->dmRepository()->getByName($id);
        $instance = $this->instanceData($document);
        // ERROR - unknown
        if($document == "")
        {
            throw $this->createNotFoundException($this->get('translator')->trans('gallery.album.error.unknown', array(), 'FhmGalleryBundle'));
        }
        // ERROR - Forbidden
        elseif(!$instance->user->admin && ($document->getDelete() || !$document->getActive()))
        {
            throw new HttpException(403, $this->get('translator')->trans('gallery.album.error.forbidden', array(), 'FhmGalleryBundle'));
        }

        return new Response(
            $this->renderView(
                "::FhmGallery/Template/" . $template . ".html.twig",
                array(
                    'document'  => $document,
                    'galleries' => $this->dmRepository("FhmGalleryBundle:Gallery")->getByGroupAll($document),
                    'instance'  => $instance
                )
            )
        );
    }
}