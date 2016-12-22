<?php
namespace Fhm\GalleryBundle\Controller\Item;

use Fhm\FhmBundle\Controller\RefAdminController as FhmController;
use Fhm\FhmBundle\Form\Handler\Admin\CreateHandler;
use Fhm\GalleryBundle\Document\GalleryItem;
use Fhm\GalleryBundle\Form\Type\Admin\Item\CreateType;
use Fhm\GalleryBundle\Form\Type\Admin\Item\MultipleType;
use Fhm\GalleryBundle\Form\Type\Admin\Item\UpdateType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * @Route("/admin/galleryitem")
 * -----------------------------------------
 * Class AdminController
 * @package Fhm\GalleryBundle\Controller\Item
 */
class AdminController extends FhmController
{
    /**
     * AdminController constructor.
     */
    public function __construct()
    {
        self::$repository = "FhmGalleryBundle:GalleryItem";
        self::$source = "fhm";
        self::$domain = "FhmGalleryBundle";
        self::$translation = "gallery.item";
        self::$class = GalleryItem::class;
        self::$route = "gallery_item";
        self::$form = new \stdClass();
        self::$form->createType    = CreateType::class;
        self::$form->createHandler = CreateHandler::class;
        self::$form->updateType    = UpdateType::class;
    }

    /**
     * @Route
     * (
     *      path="/",
     *      name="fhm_admin_gallery_item"
     * )
     * @Template("::FhmGallery/Admin/Item/index.html.twig")
     */
    public function indexAction()
    {
        return parent::indexAction();
    }

    /**
     * @Route
     * (
     *      path="/create",
     *      name="fhm_admin_gallery_item_create"
     * )
     * @Template("::FhmGallery/Admin/Item/create.html.twig")
     */
    public function createAction(Request $request)
    {
        return parent::createAction($request);
    }

    /**
     * @Route
     * (
     *      path="/multiple",
     *      name="fhm_admin_gallery_item_multiple"
     * )
     * @Template("::FhmGallery/Admin/Item/multiple.html.twig")
     */
    public function multipleAction(Request $request)
    {
        $document = new self::$class;
        $classType = MultipleType::class;
        $classHandler = CreateHandler::class;
        $form = $this->createForm(
            $classType,
            $document,
            array(
                'data_class' => self::$class,
                'translation_domain' => self::$domain,
                'translation_route' => self::$translation,
                'user_admin' => $this->getUser()->hasRole('ROLE_ADMIN'),
            )
        );
        $handler = new $classHandler($form, $request);
        $process = $handler->process();
        if ($process) {
            $data = $request->get($form->getName());
            // File
            $fileData = array(
                'tmp_name' => isset($_FILES['file']) ? $_FILES['file']['tmp_name'] : $_FILES[$form->getName(
                )]['tmp_name']['file'],
                'name' => isset($_FILES['file']) ? $_FILES['file']['name'] : $_FILES[$form->getName()]['name']['file'],
                'type' => isset($_FILES['file']) ? $_FILES['file']['type'] : $_FILES[$form->getName()]['type']['file'],
            );
            $file = new UploadedFile($fileData['tmp_name'], $fileData['name'], $fileData['type']);
            $tab = explode('.', $fileData['name']);
            $name = $data['title'] ? $this->get('fhm_tools')->getUnique(null, $data['title'], true) : $tab[0];
            // Persist media
            $media = new \Fhm\MediaBundle\Document\Media();
            $media->setName($name);
            $media->setFile($file);
            $media->setUserCreate($this->getUser());
            $media->setAlias($this->get('fhm_tools')->getAlias($media->getId(), $media->getName()));
            $media->setWatermark((array)$request->get('watermark'));
            // Tag
            if (isset($data['tag']) && $data['tag']) {
                $tag = $this->get('fhm_tools')->dmRepository('FhmMediaBundle:MediaTag')->getByName($data['tag']);
                if ($tag == "") {
                    $tag = new \Fhm\MediaBundle\Document\MediaTag();
                    $tag->setName($data['tag']);
                    $tag->setActive(true);
                }
                if (isset($data['parent']) && $data['parent']) {
                    $tag->setParent(
                        $this->get('fhm_tools')->dmRepository('FhmMediaBundle:MediaTag')->find($data['parent'])
                    );
                }
                $this->get('fhm_tools')->dmPersist($tag);
                $media->addTag($tag);
            }
            if (isset($data['tags']) && $data['tags']) {
                foreach ($data['tags'] as $tagId) {
                    $media->addTag($this->get('fhm_tools')->dmRepository('FhmMediaBundle:MediaTag')->find($tagId));
                }
            }
            $this->get('fhm_tools')->dmPersist($media);
            //upload file
            $this->get($this->get('fhm_tools')->getParameter('service', 'fhm_media'))->setDocument(
                $media
            )->setWatermark(
                $request->get('watermark')
            )->execute();
            $document->setTitle($name);
            $document->setUserCreate($this->getUser());
            $document->setAlias($this->get('fhm_tools')->getAlias($document->getId(), $document->getName()));
            $document->setImage($media);
            $this->get('fhm_tools')->dmPersist($document);
        }

        return array(
            'form' => $form->createView(),
            'watermarks' => $this->get('fhm_tools')->getParameters('watermark', 'fhm_media') ? $this->get(
                'fhm_tools'
            )->getParameter(
                'files',
                'fhm_media'
            ) : '',
            'breadcrumbs' => array(
                array(
                    'link' => $this->getUrl('project_home'),
                    'text' => $this->trans('project.home.breadcrumb', array(), 'ProjectDefaultBundle'),
                ),
                array(
                    'link' => $this->getUrl('fhm_admin'),
                    'text' => $this->trans('fhm.admin.breadcrumb', array(), 'FhmFhmBundle'),
                ),
                array(
                    'link' => $this->getUrl('fhm_admin_'.self::$route),
                    'text' => $this->trans('.admin.index.breadcrumb'),
                ),
                array(
                    'link' => $this->getUrl('fhm_admin_'.self::$route.'_create'),
                    'text' => $this->ftrans('.admin.create.breadcrumb'),
                    'current' => true,
                ),
            ),
        );
    }

    /**
     * @Route
     * (
     *      path="/duplicate/{id}",
     *      name="fhm_admin_gallery_item_duplicate",
     *      requirements={"id"="[a-z0-9]*"}
     * )
     * @Template("::FhmGallery/Admin/Item/create.html.twig")
     */
    public function duplicateAction(Request $request, $id)
    {
        return parent::duplicateAction($request, $id);
    }

    /**
     * @Route
     * (
     *      path="/update/{id}",
     *      name="fhm_admin_gallery_item_update",
     *      requirements={"id"="[a-z0-9]*"}
     * )
     * @Template("::FhmGallery/Admin/Item/update.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        return parent::updateAction($request, $id);
    }

    /**
     * @Route
     * (
     *      path="/detail/{id}",
     *      name="fhm_admin_gallery_item_detail",
     *      requirements={"id"="[a-z0-9]*"}
     * )
     * @Template("::FhmGallery/Admin/Item/detail.html.twig")
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
     *      name="fhm_admin_gallery_item_delete",
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
     *      name="fhm_admin_gallery_item_undelete",
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
     *      name="fhm_admin_gallery_item_activate",
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
     *      name="fhm_admin_gallery_item_deactivate",
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
     *      name="fhm_admin_gallery_item_import"
     * )
     * @Template("::FhmGallery/Admin/Item/import.html.twig")
     */
    public function importAction(Request $request)
    {
        return parent::importAction($request);
    }

    /**
     * @Route
     * (
     *      path="/export",
     *      name="fhm_admin_gallery_item_export"
     * )
     * @Template("::FhmGallery/Admin/Item/export.html.twig")
     */
    public function exportAction(Request $request)
    {
        return parent::exportAction($request);
    }

    /**
     * @Route
     * (
     *      path="/list/gallery",
     *      name="fhm_admin_gallery_item_gallery",
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