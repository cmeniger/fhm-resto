<?php
namespace Fhm\MediaBundle\Controller\Tag;

use Fhm\FhmBundle\Controller\RefAdminController as FhmController;
use Fhm\FhmBundle\Form\Handler\Admin\CreateHandler;
use Fhm\FhmBundle\Form\Handler\Admin\UpdateHandler;
use Fhm\MediaBundle\Form\Type\Admin\Tag\CreateType;
use Fhm\MediaBundle\Form\Type\Admin\Tag\UpdateType;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * @Route("/admin/mediatag")
 * ----------------------------------------------
 * Class AdminController
 * @package Fhm\MediaBundle\Controller\Tag
 */
class AdminController extends FhmController
{
    /**
     * AdminController constructor.
     */
    public function __construct()
    {
        self::$repository = "FhmMediaBundle:MediaTag";
        self::$source = "fhm";
        self::$domain = "FhmMediaBundle";
        self::$translation = "media.tag";
        self::$route = "media_tag";
        self::$form = new \stdClass();
        self::$form->createType = CreateType::class;
        self::$form->createHandler = CreateHandler::class;
        self::$form->updateType = UpdateType::class;
        self::$form->updateHandler = UpdateHandler::class;
    }

    /**
     * @Route
     * (
     *      path="/",
     *      name="fhm_admin_media_tag"
     * )
     * @Template("::FhmMedia/Admin/Tag/index.html.twig")
     */
    public function indexAction()
    {
        return parent::indexAction();
    }

    /**
     * @Route
     * (
     *      path="/create",
     *      name="fhm_admin_media_tag_create"
     * )
     * @Template("::FhmMedia/Admin/Tag/create.html.twig")
     */
    public function createAction(Request $request)
    {
        return parent::createAction($request);
    }

    /**
     * @Route
     * (
     *      path="/duplicate/{id}",
     *      name="fhm_admin_media_tag_duplicate",
     *      requirements={"id"="[a-z0-9]*"}
     * )
     * @Template("::FhmMedia/Admin/Tag/create.html.twig")
     */
    public function duplicateAction(Request $request, $id)
    {
        return parent::duplicateAction($request, $id);
    }

    /**
     * @Route
     * (
     *      path="/update/{id}",
     *      name="fhm_admin_media_tag_update",
     *      requirements={"id"="[a-z0-9]*"}
     * )
     * @Template("::FhmMedia/Admin/Tag/update.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        return parent::updateAction($request, $id);
    }

    /**
     * @Route
     * (
     *      path="/detail/{id}",
     *      name="fhm_admin_media_tag_detail",
     *      requirements={"id"="[a-z0-9]*"}
     * )
     * @Template("::FhmMedia/Admin/Tag/detail.html.twig")
     */
    public function detailAction($id)
    {
        return parent::detailAction($id);
    }

    /**
     * @Route
     * (
     *      path="/delete/{id}",
     *      name="fhm_admin_media_tag_delete",
     *      requirements={"id"="[a-z0-9]*"}
     * )
     */
    public function deleteAction($id)
    {
        $response = parent::deleteAction($id);
        $object = $this->get('fhm_tools')->dmRepository(self::$repository)->find($id);
        $this->tagDelete($id, $object ? false : true);

        return $response;
    }

    /**
     * @Route
     * (
     *      path="/undelete/{id}",
     *      name="fhm_admin_media_tag_undelete",
     *      requirements={"id"="[a-z0-9]*"}
     * )
     */
    public function undeleteAction($id)
    {
        $this->_tagUndelete($id);

        return parent::undeleteAction($id);
    }

    /**
     * @Route
     * (
     *      path="/activate/{id}",
     *      name="fhm_admin_media_tag_activate",
     *      requirements={"id"="[a-z0-9]*"}
     * )
     */
    public function activateAction($id)
    {
        $this->_tagActive($id, true);

        return parent::activateAction($id);
    }

    /**
     * @Route
     * (
     *      path="/deactivate/{id}",
     *      name="fhm_admin_media_tag_deactivate",
     *      requirements={"id"="[a-z0-9]*"}
     * )
     */
    public function deactivateAction($id)
    {
        $this->_tagActive($id, false);

        return parent::deactivateAction($id);
    }

    /**
     * @Route
     * (
     *      path="/import",
     *      name="fhm_admin_media_tag_import"
     * )
     * @Template("::FhmMedia/Admin/Tag/import.html.twig")
     */
    public function importAction(Request $request)
    {
        return parent::importAction($request);
    }

    /**
     * @Route
     * (
     *      path="/export",
     *      name="fhm_admin_media_tag_export"
     * )
     * @Template("::FhmMedia/Admin/Tag/export.html.twig")
     */
    public function exportAction(Request $request)
    {
        return parent::exportAction($request);
    }

    /**
     * Tag delete
     *
     * @param String $id
     * @param Boolean $delete
     *
     * @return self
     */
    private function tagDelete($id, $delete)
    {
        $objects = $this->get('fhm_tools')->dmRepository(self::$repository)->getSons($id);
        foreach ($objects as $object) {
            $this->tagDelete($object->getId(), $delete);
            if ($delete) {
                $medias = $this->get('fhm_tools')->dmRepository("FhmMediaBundle:Media")->getByTag($object->getId());
                foreach ($medias as $media) {
                    $media->removeTag($object);
                    $this->get('fhm_tools')->dmPersist($media);
                }
                $this->get('fhm_tools')->dmRemove($object);
            } else {
                $object->setDelete(true);
                $this->get('fhm_tools')->dmPersist($object);
            }
        }

        return $this;
    }

    /**
     * Tag undelete
     *
     * @param String $id
     *
     * @return self
     */
    private function _tagUndelete($id)
    {
        $objects = $this->get('fhm_tools')->dmRepository(self::$repository)->getSons($id);
        foreach ($objects as $object) {
            $this->_tagUndelete($object->getId());
            $object->setDelete(false);
            $this->get('fhm_tools')->dmPersist($object);
        }

        return $this;
    }

    /**
     * Tag active
     *
     * @param String $id
     * @param Boolean $active
     *
     * @return self
     */
    private function _tagActive($id, $active)
    {
        $objects = $this->get('fhm_tools')->dmRepository(self::$repository)->getSons($id);
        foreach ($objects as $object) {
            $this->_tagActive($object->getId(), $active);
            $object->setActive($active);
            $this->get('fhm_tools')->dmPersist($object);
        }

        return $this;
    }
}