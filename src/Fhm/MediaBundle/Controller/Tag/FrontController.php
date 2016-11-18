<?php
namespace Fhm\MediaBundle\Controller\Tag;

use Fhm\FhmBundle\Controller\RefFrontController as FhmController;
use Fhm\MediaBundle\Document\Media;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * @Route("/mediatag")
 */
class FrontController extends FhmController
{
    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct('Fhm', 'Media', 'media_tag', 'MediaTag');
        $this->form->type->create = 'Fhm\\MediaBundle\\Form\\Type\\Front\\Tag\\CreateType';
        $this->form->type->update = 'Fhm\\MediaBundle\\Form\\Type\\Front\\Tag\\UpdateType';
        $this->translation        = array('FhmMediaBundle', 'media.tag');
    }

    /**
     * @Route
     * (
     *      path="/",
     *      name="fhm_media_tag"
     * )
     * @Template("::FhmMedia/Front/Tag/index.html.twig")
     */
    public function indexAction()
    {
        return parent::indexAction();
    }

    /**
     * @Route
     * (
     *      path="/create",
     *      name="fhm_media_tag_create"
     * )
     * @Template("::FhmMedia/Front/Tag/create.html.twig")
     */
    public function createAction(Request $request)
    {
        // For activate this route, delete next line
        throw $this->createNotFoundException($this->get('translator')->trans('fhm.error.route', array(), 'FhmFhmBundle'));

        return parent::createAction($request);
    }

    /**
    * @Route
    * (
    *      path="/duplicate/{id}",
    *      name="fhm_media_tag_duplicate",
    *      requirements={"id"="[a-z0-9]*"}
    * )
    * @Template("::FhmMedia/Front/Tag/create.html.twig")
    */
    public function duplicateAction(Request $request, $id)
    {
        // For activate this route, delete next line
        throw $this->createNotFoundException($this->get('translator')->trans('fhm.error.route', array(), 'FhmFhmBundle'));

        return parent::duplicateAction($request, $id);
    }

    /**
    * @Route
    * (
    *      path="/update/{id}",
    *      name="fhm_media_tag_update",
    *      requirements={"id"="[a-z0-9]*"}
    * )
    * @Template("::FhmMedia/Front/Tag/update.html.twig")
    */
    public function updateAction(Request $request, $id)
    {
        // For activate this route, delete next line
        throw $this->createNotFoundException($this->get('translator')->trans('fhm.error.route', array(), 'FhmFhmBundle'));

        return parent::updateAction($request, $id);
    }

    /**
    * @Route
    * (
    *      path="/detail/{id}",
    *      name="fhm_media_tag_detail",
    *      requirements={"id"=".+"}
    * )
    * @Template("::FhmMedia/Front/Tag/detail.html.twig")
    */
    public function detailAction($id)
    {
        return parent::detailAction($id);
    }

    /**
    * @Route
    * (
    *      path="/delete/{id}",
    *      name="fhm_media_tag_delete",
    *      requirements={"id"="[a-z0-9]*"}
    * )
    */
    public function deleteAction($id)
    {
        // For activate this route, delete next line
        throw $this->createNotFoundException($this->get('translator')->trans('fhm.error.route', array(), 'FhmFhmBundle'));

        return parent::deleteAction($id);
    }

    /**
    * @Route
    * (
    *      path="/{id}",
    *      name="fhm_media_tag_lite",
    *      requirements={"id"=".+"}
    * )
    * @Template("::FhmMedia/Front/Tag/detail.html.twig")
    */
    public function liteAction($id)
    {
        return $this->detailAction($id);
    }
}