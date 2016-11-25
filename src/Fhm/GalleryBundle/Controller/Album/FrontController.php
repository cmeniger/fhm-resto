<?php
namespace Fhm\GalleryBundle\Controller\Album;

use Fhm\FhmBundle\Controller\RefFrontController as FhmController;
use Fhm\GalleryBundle\Document\Gallery;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * @Route("/galleryalbum", service="fhm_gallery_controller_album_front")
 */
class FrontController extends FhmController
{
    /**
     * FrontController constructor.
     *
     * @param \Fhm\FhmBundle\Services\Tools $tools
     */
    public function __construct(\Fhm\FhmBundle\Services\Tools $tools)
    {
        $this->setFhmTools($tools);
        parent::__construct('Fhm', 'Gallery', 'gallery_album', 'GalleryAlbum');
        $this->form->type->create = 'Fhm\\GalleryBundle\\Form\\Type\\Front\\Album\\CreateType';
        $this->form->type->update = 'Fhm\\GalleryBundle\\Form\\Type\\Front\\Album\\UpdateType';
        $this->translation        = array('FhmGalleryBundle', 'gallery.album');
    }

    /**
     * @Route
     * (
     *      path="/",
     *      name="fhm_gallery_album"
     * )
     * @Template("::FhmGallery/Front/Album/index.html.twig")
     */
    public function indexAction()
    {
        return parent::indexAction();
    }

    /**
     * @Route
     * (
     *      path="/create",
     *      name="fhm_gallery_album_create"
     * )
     * @Template("::FhmGallery/Front/Album/create.html.twig")
     */
    public function createAction(Request $request)
    {
        // For activate this route, delete next line
        throw $this->createNotFoundException($this->fhm_tools->trans('fhm.error.route', array(), 'FhmFhmBundle'));
    }

    /**
     * @Route
     * (
     *      path="/duplicate/{id}",
     *      name="fhm_gallery_album_duplicate",
     *      requirements={"id"="[a-z0-9]*"}
     * )
     * @Template("::FhmGallery/Front/Album/create.html.twig")
     */
    public function duplicateAction(Request $request, $id)
    {
        // For activate this route, delete next line
        throw $this->createNotFoundException($this->fhm_tools->trans('fhm.error.route', array(), 'FhmFhmBundle'));
    }

    /**
     * @Route
     * (
     *      path="/update/{id}",
     *      name="fhm_gallery_album_update",
     *      requirements={"id"="[a-z0-9]*"}
     * )
     * @Template("::FhmGallery/Front/Album/update.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        // For activate this route, delete next line
        throw $this->createNotFoundException($this->fhm_tools->trans('fhm.error.route', array(), 'FhmFhmBundle'));
    }

    /**
     * @Route
     * (
     *      path="/detail/{id}",
     *      name="fhm_gallery_album_detail",
     *      requirements={"id"=".+"}
     * )
     * @Template("::FhmGallery/Front/Album/detail.html.twig")
     */
    public function detailAction($id)
    {
        return parent::detailAction($id);
    }

    /**
     * @Route
     * (
     *      path="/delete/{id}",
     *      name="fhm_gallery_album_delete",
     *      requirements={"id"="[a-z0-9]*"}
     * )
     */
    public function deleteAction($id)
    {
        // For activate this route, delete next line
        throw $this->createNotFoundException($this->fhm_tools->trans('fhm.error.route', array(), 'FhmFhmBundle'));
    }

    /**
     * @Route
     * (
     *      path="/{id}",
     *      name="fhm_gallery_album_lite",
     *      requirements={"id"=".+"}
     * )
     * @Template("::FhmGallery/Front/Album/detail.html.twig")
     */
    public function liteAction($id)
    {
        return $this->detailAction($id);
    }
}