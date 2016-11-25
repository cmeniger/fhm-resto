<?php
namespace Fhm\GalleryBundle\Controller;

use Fhm\FhmBundle\Controller\RefAdminController as FhmController;
use Fhm\GalleryBundle\Document\Gallery;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * @Route("/admin/gallery", service="fhm_gallery_controller_admin")
 */
class AdminController extends FhmController
{
    /**
     * AdminController constructor.
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
     *      name="fhm_admin_gallery"
     * )
     * @Template("::FhmGallery/Admin/index.html.twig")
     */
    public function indexAction()
    {
        return parent::indexAction();
    }

    /**
     * @Route
     * (
     *      path="/create",
     *      name="fhm_admin_gallery_create"
     * )
     * @Template("::FhmGallery/Admin/create.html.twig")
     */
    public function createAction(Request $request)
    {
        return parent::createAction($request);
    }

    /**
     * @Route
     * (
     *      path="/duplicate/{id}",
     *      name="fhm_admin_gallery_duplicate",
     *      requirements={"id"="[a-z0-9]*"}
     * )
     * @Template("::FhmGallery/Admin/create.html.twig")
     */
    public function duplicateAction(Request $request, $id)
    {
        return parent::duplicateAction($request, $id);
    }

    /**
     * @Route
     * (
     *      path="/update/{id}",
     *      name="fhm_admin_gallery_update",
     *      requirements={"id"="[a-z0-9]*"}
     * )
     * @Template("::FhmGallery/Admin/update.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        return parent::updateAction($request, $id);
    }

    /**
     * @Route
     * (
     *      path="/detail/{id}",
     *      name="fhm_admin_gallery_detail",
     *      requirements={"id"="[a-z0-9]*"}
     * )
     * @Template("::FhmGallery/Admin/detail.html.twig")
     */
    public function detailAction($id)
    {
        $document = $this->fhm_tools->dmRepository()->find($id);
        $instance = $this->fhm_tools->instanceData($document);

        return array_merge(
            array(
                'item1' => $this->fhm_tools->dmRepository('FhmGalleryBundle:GalleryItem')->getAllEnable(
                    $instance->grouping->current
                ),
                'item2' => $this->getList($document->getItems()),
                'video1' => $this->fhm_tools->dmRepository('FhmGalleryBundle:GalleryVideo')->getAllEnable(
                    $instance->grouping->current
                ),
                'video2' => $this->getList($document->getVideos()),
                'album1' => $this->fhm_tools->dmRepository('FhmGalleryBundle:GalleryAlbum')->getAllEnable(
                    $instance->grouping->current
                ),
                'album2' => $this->getList($document->getAlbums()),
            ),
            parent::detailAction($id)
        );
    }

    /**
     * @Route
     * (
     *      path="/preview/{id}",
     *      name="fhm_admin_gallery_preview",
     *      requirements={"id"="[a-z0-9]*"}
     * )
     * @Template("::FhmGallery/Front/detail.html.twig")
     */
    public function previewAction($id)
    {
        return parent::detailAction($id);
    }

    /**
     * @Route
     * (
     *      path="/delete/{id}",
     *      name="fhm_admin_gallery_delete",
     *      requirements={"id"="[a-z0-9]*"}
     * )
     */
    public function deleteAction($id)
    {
        return parent::deleteAction($id);
    }

    /**
     * @Route
     * (
     *      path="/undelete/{id}",
     *      name="fhm_admin_gallery_undelete",
     *      requirements={"id"="[a-z0-9]*"}
     * )
     */
    public function undeleteAction($id)
    {
        return parent::undeleteAction($id);
    }

    /**
     * @Route
     * (
     *      path="/activate/{id}",
     *      name="fhm_admin_gallery_activate",
     *      requirements={"id"="[a-z0-9]*"}
     * )
     */
    public function activateAction($id)
    {
        return parent::activateAction($id);
    }

    /**
     * @Route
     * (
     *      path="/deactivate/{id}",
     *      name="fhm_admin_gallery_deactivate",
     *      requirements={"id"="[a-z0-9]*"}
     * )
     */
    public function deactivateAction($id)
    {
        return parent::deactivateAction($id);
    }

    /**
     * @Route
     * (
     *      path="/import",
     *      name="fhm_admin_gallery_import"
     * )
     * @Template("::FhmGallery/Admin/import.html.twig")
     */
    public function importAction(Request $request)
    {
        return parent::importAction($request);
    }

    /**
     * @Route
     * (
     *      path="/export",
     *      name="fhm_admin_gallery_export"
     * )
     * @Template("::FhmGallery/Admin/export.html.twig")
     */
    public function exportAction(Request $request)
    {
        return parent::exportAction($request);
    }

    /**
     * @Route
     * (
     *      path="/grouping",
     *      name="fhm_admin_gallery_grouping"
     * )
     */
    public function groupingAction(Request $request)
    {
        return parent::groupingAction($request);
    }

    /**
     * @Route
     * (
     *      path="/list/item",
     *      name="fhm_admin_gallery_list_item",
     *      requirements={"id"="[a-z0-9]*"}
     * )
     */
    public function listItemAction(Request $request)
    {
        $datas = json_decode($request->get('list'));
        $document = $this->fhm_tools->dmRepository()->find($request->get('id'));
        foreach ($document->getItems() as $item) {
            $document->removeItem($item);
        }
        foreach ($datas as $key => $data) {
            $document->addItem($this->fhm_tools->dmRepository('FhmGalleryBundle:GalleryItem')->find($data->id));
        }
        $this->fhm_tools->dmPersist($document);

        return new Response();
    }

    /**
     * @Route
     * (
     *      path="/list/video",
     *      name="fhm_admin_gallery_list_video",
     *      requirements={"id"="[a-z0-9]*"}
     * )
     */
    public function listVideoAction(Request $request)
    {
        $datas = json_decode($request->get('list'));
        $document = $this->fhm_tools->dmRepository()->find($request->get('id'));
        foreach ($document->getVideos() as $video) {
            $document->removeVideo($video);
        }
        foreach ($datas as $key => $data) {
            $document->addVideo($this->fhm_tools->dmRepository('FhmGalleryBundle:GalleryVideo')->find($data->id));
        }
        $this->fhm_tools->dmPersist($document);

        return new Response();
    }

    /**
     * @Route
     * (
     *      path="/list/album",
     *      name="fhm_admin_gallery_list_album",
     *      requirements={"id"="[a-z0-9]*"}
     * )
     */
    public function listAlbumAction(Request $request)
    {
        $datas = json_decode($request->get('list'));
        $document = $this->fhm_tools->dmRepository()->find($request->get('id'));
        foreach ($document->getAlbums() as $album) {
            $document->removeAlbum($album);
        }
        foreach ($datas as $key => $data) {
            $document->addAlbum($this->fhm_tools->dmRepository('FhmGalleryBundle:GalleryAlbum')->find($data->id));
        }
        $this->fhm_tools->dmPersist($document);

        return new Response();
    }
}