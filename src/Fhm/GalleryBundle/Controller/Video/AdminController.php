<?php
namespace Fhm\GalleryBundle\Controller\Video;

use Fhm\FhmBundle\Controller\RefAdminController as FhmController;
use Fhm\FhmBundle\Form\Handler\Admin\CreateHandler;
use Fhm\FhmBundle\Form\Handler\Admin\UpdateHandler;
use Fhm\GalleryBundle\Document\GalleryVideo;
use Fhm\GalleryBundle\Form\Type\Admin\Video\CreateType;
use Fhm\GalleryBundle\Form\Type\Admin\Video\UpdateType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * @Route("/admin/galleryvideo")
 * -------------------------------------------
 * Class AdminController
 * @package Fhm\GalleryBundle\Controller\Video
 */
class AdminController extends FhmController
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
        $translation = "gallery.video",
        $document = GalleryVideo::class,
        $route = 'gallery_video'
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
     *      name="fhm_admin_gallery_video"
     * )
     * @Template("::FhmGallery/Admin/Video/index.html.twig")
     */
    public function indexAction()
    {
        return parent::indexAction();
    }

    /**
     * @Route
     * (
     *      path="/create",
     *      name="fhm_admin_gallery_video_create"
     * )
     * @Template("::FhmGallery/Admin/Video/create.html.twig")
     */
    public function createAction(Request $request)
    {
        self::$form = new \stdClass();
        self::$form->type = CreateType::class;
        self::$form->handler = CreateHandler::class;

        return parent::createAction($request);
    }

    /**
     * @Route
     * (
     *      path="/duplicate/{id}",
     *      name="fhm_admin_gallery_video_duplicate",
     *      requirements={"id"="[a-z0-9]*"}
     * )
     * @Template("::FhmGallery/Admin/Video/create.html.twig")
     */
    public function duplicateAction(Request $request, $id)
    {
        self::$form = new \stdClass();
        self::$form->type = CreateType::class;
        self::$form->handler = CreateHandler::class;

        return parent::duplicateAction($request, $id);
    }

    /**
     * @Route
     * (
     *      path="/update/{id}",
     *      name="fhm_admin_gallery_video_update",
     *      requirements={"id"="[a-z0-9]*"}
     * )
     * @Template("::FhmGallery/Admin/Video/update.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        self::$form = new \stdClass();
        self::$form->type = UpdateType::class;
        self::$form->handler = UpdateHandler::class;

        return parent::updateAction($request, $id);
    }

    /**
     * @Route
     * (
     *      path="/detail/{id}",
     *      name="fhm_admin_gallery_video_detail",
     *      requirements={"id"="[a-z0-9]*"}
     * )
     * @Template("::FhmGallery/Admin/Video/detail.html.twig")
     */
    public function detailAction($id)
    {
        $document = $this->get('fhm_tools')->dmRepository(self::$repository)->find($id);

        return array_merge(
            array(
                'gallery1' => $this->get('fhm_tools')->dmRepository('FhmGalleryBundle:Gallery')->getAllEnable(),
                'gallery2' => $this->getList($document->getGalleries()),
            ),
            parent::detailAction($id)
        );
    }

    /**
     * @Route
     * (
     *      path="/delete/{id}",
     *      name="fhm_admin_gallery_video_delete",
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
     *      name="fhm_admin_gallery_video_undelete",
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
     *      name="fhm_admin_gallery_video_activate",
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
     *      name="fhm_admin_gallery_video_deactivate",
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
     *      name="fhm_admin_gallery_video_import"
     * )
     * @Template("::FhmGallery/Admin/Video/import.html.twig")
     */
    public function importAction(Request $request)
    {
        return parent::importAction($request);
    }

    /**
     * @Route
     * (
     *      path="/export",
     *      name="fhm_admin_gallery_video_export"
     * )
     * @Template("::FhmGallery/Admin/Video/export.html.twig")
     */
    public function exportAction(Request $request)
    {
        return parent::exportAction($request);
    }

    /**
     * @Route
     * (
     *      path="/list/gallery",
     *      name="fhm_admin_gallery_video_gallery",
     *      requirements={"id"="[a-z0-9]*"}
     * )
     */
    public function listGalleryAction(Request $request)
    {
        $datas = json_decode($request->get('list'));
        $document = $this->get('fhm_tools')->dmRepository(self::$repository)->find($request->get('id'));
        foreach ($document->getGalleries() as $gallery) {
            $document->removeGallery($gallery);
        }
        foreach ($datas as $key => $data) {
            $document->addGallery($this->get('fhm_tools')->dmRepository('FhmGalleryBundle:Gallery')->find($data->id));
        }
        $this->get('fhm_tools')->dmPersist($document);

        return new Response();
    }
}