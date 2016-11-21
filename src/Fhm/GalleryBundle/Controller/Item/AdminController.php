<?php
namespace Fhm\GalleryBundle\Controller\Item;

use Fhm\FhmBundle\Controller\RefAdminController as FhmController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * @Route("/admin/galleryitem")
 */
class AdminController extends FhmController
{
    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct('Fhm', 'Gallery', 'gallery_item', 'GalleryItem');
        $this->form->type->create = 'Fhm\\GalleryBundle\\Form\\Type\\Admin\\Item\\CreateType';
        $this->form->type->update = 'Fhm\\GalleryBundle\\Form\\Type\\Admin\\Item\\UpdateType';
        $this->translation        = array('FhmGalleryBundle', 'gallery.item');
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
        // ERROR - Unknown route
        if(!$this->routeExists('fhm_admin_' . $this->route) || !$this->routeExists('fhm_admin_' . $this->route . '_multiple'))
        {
            throw $this->createNotFoundException($this->get('translator')->trans('fhm.error.route', array(), 'FhmFhmBundle'));
        }
        $document     = $this->document;
        $instance     = $this->instanceData();
        $classType    = 'Fhm\\GalleryBundle\\Form\\Type\\Admin\\Item\\MultipleType';
        $classHandler = $this->form->handler->create;
        $form         = $this->createForm(new $classType($instance, $document), $document);
        $handler      = new $classHandler($form, $request);
        $process      = $handler->process();
        if($process)
        {
            $data = $request->get($form->getName());
            // File
            $fileData = array
            (
                'tmp_name' => isset($_FILES['file']) ? $_FILES['file']['tmp_name'] : $_FILES[$form->getName()]['tmp_name']['file'],
                'name'     => isset($_FILES['file']) ? $_FILES['file']['name'] : $_FILES[$form->getName()]['name']['file'],
                'type'     => isset($_FILES['file']) ? $_FILES['file']['type'] : $_FILES[$form->getName()]['type']['file']
            );
            $file     = new UploadedFile($fileData['tmp_name'], $fileData['name'], $fileData['type']);
            $tab      = explode('.', $fileData['name']);
            $name     = $data['title'] ? $this->getUnique(null, $data['title'], true) : $tab[0];
            // Persist media
            $media = new \Fhm\MediaBundle\Document\Media();
            $media->setName($name);
            $media->setFile($file);
            $media->setUserCreate($this->getUser());
            $media->setAlias($this->getAlias($media->getId(), $media->getName()));
            $media->addGrouping($instance->grouping->current);
            $media->setWatermark((array) $request->get('watermark'));
            // Tag
            if(isset($data['tag']) && $data['tag'])
            {
                $tag = $this->dmRepository('FhmMediaBundle:MediaTag')->getByName($data['tag']);
                if($tag == "")
                {
                    $tag = new \Fhm\MediaBundle\Document\MediaTag();
                    $tag->setName($data['tag']);
                    $tag->setActive(true);
                }
                if(isset($data['parent']) && $data['parent'])
                {
                    $tag->setParent($this->dmRepository('FhmMediaBundle:MediaTag')->find($data['parent']));
                }
                $this->dmPersist($tag);
                $media->addTag($tag);
            }
            if(isset($data['tags']) && $data['tags'])
            {
                foreach($data['tags'] as $tagId)
                {
                    $media->addTag($this->dmRepository('FhmMediaBundle:MediaTag')->find($tagId));
                }
            }
            $this->dmPersist($media);
            //upload file
            $this->get($this->getParameters('service','fhm_media'))->setDocument($media)->setWatermark($request->get('watermark'))->execute();
            $document->setTitle($name);
            $document->setUserCreate($this->getUser());
            $document->setAlias($this->getAlias($document->getId(), $document->getName()));
            $document->addGrouping($instance->grouping->current);
            $document->setImage($media);
            $this->dmPersist($document);
        }

        return array(
            'form'        => $form->createView(),
            'instance'    => $instance,
            'watermarks'  => $this->getParameters('watermark', 'fhm_media') ? $this->getParameters('files', 'fhm_media') : '',
            'breadcrumbs' => array(
                array(
                    'link' => $this->get('router')->generate('project_home'),
                    'text' => $this->get('translator')->trans('project.home.breadcrumb', array(), 'ProjectDefaultBundle'),
                ),
                array(
                    'link' => $this->get('router')->generate('fhm_admin'),
                    'text' => $this->get('translator')->trans('fhm.admin.breadcrumb', array(), 'FhmFhmBundle'),
                ),
                array(
                    'link' => $this->get('router')->generate('fhm_admin_' . $this->route),
                    'text' => $this->get('translator')->trans($this->translation[1] . '.admin.index.breadcrumb', array(), $this->translation[0]),
                ),
                array(
                    'link'    => $this->get('router')->generate('fhm_admin_' . $this->route . '_create'),
                    'text'    => $this->get('translator')->trans($this->translation[1] . '.admin.create.breadcrumb', array(), $this->translation[0]),
                    'current' => true
                )
            )
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
        $document = $this->dmRepository()->find($id);
        $instance = $this->instanceData($document);

        return array_merge(
            array(
                'gallery1' => $this->dmRepository('FhmGalleryBundle:Gallery')->getAllEnable($instance->grouping->current),
                'gallery2' => $this->getList($document->getGalleries())
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
     *      path="/grouping",
     *      name="fhm_admin_gallery_item_grouping"
     * )
     */
    public function groupingAction(Request $request)
    {
        return parent::groupingAction($request);
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
        $datas    = json_decode($request->get('list'));
        $document = $this->dmRepository()->find($request->get('id'));
        foreach($document->getGalleries() as $gallery)
        {
            $document->removeGallery($gallery);
        }
        foreach($datas as $key => $data)
        {
            $document->addGallery($this->dmRepository('FhmGalleryBundle:Gallery')->find($data->id));
        }
        $this->dmPersist($document);

        return new Response();
    }
}