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
 */
class ApiController extends FhmController
{
    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct('Fhm', 'Gallery', 'gallery');
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
        $document = $this->dmRepository()->getById($id);
        $document = ($document) ? $document : $this->dmRepository()->getByAlias($id);
        $document = ($document) ? $document : $this->dmRepository()->getByName($id);
        $instance = $this->instanceData($document);
        // ERROR - unknown
        if($document == "")
        {
            throw $this->createNotFoundException($this->get('translator')->trans('gallery.item.error.unknown', array(), 'FhmGalleryBundle'));
        }
        // ERROR - Forbidden
        elseif(!$instance->user->admin && ($document->getDelete() || !$document->getActive()))
        {
            throw new HttpException(403, $this->get('translator')->trans('gallery.item.error.forbidden', array(), 'FhmGalleryBundle'));
        }

        return new Response(
            $this->renderView(
                "::FhmGallery/Template/" . $template . ".html.twig",
                array(
                    'document' => $document,
                    'items'    => $this->dmRepository("FhmGalleryBundle:GalleryItem")->getByGroupAll($document),
                    'videos'   => $this->dmRepository("FhmGalleryBundle:GalleryVideo")->getByGroupAll($document),
                    'instance' => $instance
                )
            )
        );
    }
}

