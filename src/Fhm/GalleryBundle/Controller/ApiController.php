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
 * @Route("/api/gallery", service="fhm_gallery_controller_api")
 */
class ApiController extends FhmController
{
    /**
     * ApiController constructor.
     *
     * @param \Fhm\FhmBundle\Services\Tools $tools
     */
    public function __construct(\Fhm\FhmBundle\Services\Tools $tools)
    {
        $this->setFhmTools($tools);
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
        $document = $this->fhm_tools->dmRepository()->getById($id);
        $document = ($document) ? $document : $this->fhm_tools->dmRepository()->getByAlias($id);
        $document = ($document) ? $document : $this->fhm_tools->dmRepository()->getByName($id);
        $instance = $this->fhm_tools->instanceData($document);
        // ERROR - unknown
        if($document == "")
        {
            throw $this->createNotFoundException($this->fhm_tools->trans('gallery.item.error.unknown', array(), 'FhmGalleryBundle'));
        }
        // ERROR - Forbidden
        elseif(!$instance->user->admin && ($document->getDelete() || !$document->getActive()))
        {
            throw new HttpException(403, $this->fhm_tools->trans('gallery.item.error.forbidden', array(), 'FhmGalleryBundle'));
        }

        return new Response(
            $this->renderView(
                "::FhmGallery/Template/" . $template . ".html.twig",
                array(
                    'document' => $document,
                    'items'    => $this->fhm_tools->dmRepository("FhmGalleryBundle:GalleryItem")->getByGroupAll($document),
                    'videos'   => $this->fhm_tools->dmRepository("FhmGalleryBundle:GalleryVideo")->getByGroupAll($document),
                    'instance' => $instance
                )
            )
        );
    }
}

