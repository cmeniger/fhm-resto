<?php
namespace Fhm\MediaBundle\Controller\Tag;

use Fhm\FhmBundle\Controller\RefAdminController as FhmController;
use Fhm\MediaBundle\Document\Media;
use Fhm\MediaBundle\Form\Type\Admin\Tag\CreateType;
use Fhm\MediaBundle\Form\Type\Admin\Tag\UpdateType;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * @Route("/admin/mediatag", service="fhm_media_controller_tag_admin")
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
        parent::__construct('Fhm', 'Media', 'media_tag', 'MediaTag');
        $this->form->type->create = CreateType::class;
        $this->form->type->update = UpdateType::class;
        $this->translation = array('FhmMediaBundle', 'media.tag');
        $this->fhm_tools->setSort('route');
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
        $document = $this->fhm_tools->dmRepository()->find($id);
        $this->_tagDelete($id, $document ? false : true);

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
     * @Route
     * (
     *      path="/grouping",
     *      name="fhm_admin_media_tag_grouping"
     * )
     */
    public function groupingAction(Request $request)
    {
        return parent::groupingAction($request);
    }

    /**
     * Tag delete
     *
     * @param String $id
     * @param Boolean $delete
     *
     * @return self
     */
    private function _tagDelete($id, $delete)
    {
        $documents = $this->fhm_tools->dmRepository()->getSons($id);
        foreach ($documents as $document) {
            $this->_tagDelete($document->getId(), $delete);
            if ($delete) {
                $medias = $this->fhm_tools->dmRepository("FhmMediaBundle:Media")->getByTag($document->getId());
                foreach ($medias as $media) {
                    $media->removeTag($document);
                    $this->fhm_tools->dmPersist($media);
                }
                $this->fhm_tools->dmRemove($document);
            } else {
                $document->setDelete(true);
                $this->fhm_tools->dmPersist($document);
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
        $documents = $this->fhm_tools->dmRepository()->getSons($id);
        foreach ($documents as $document) {
            $this->_tagUndelete($document->getId());
            $document->setDelete(false);
            $this->fhm_tools->dmPersist($document);
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
        $documents = $this->fhm_tools->dmRepository()->getSons($id);
        foreach ($documents as $document) {
            $this->_tagActive($document->getId(), $active);
            $document->setActive($active);
            $this->fhm_tools->dmPersist($document);
        }

        return $this;
    }
}