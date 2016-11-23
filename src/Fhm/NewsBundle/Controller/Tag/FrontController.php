<?php
namespace Fhm\NewsBundle\Controller\Tag;

use Fhm\FhmBundle\Controller\RefFrontController as FhmController;
use Fhm\NewsBundle\Document\NewsTag;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * @Route("/newstag", service="fhm_news_controller_tag_front")
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
        parent::__construct('Fhm', 'News', 'news_tag', 'NewsTag');
        $this->form->type->create = 'Fhm\\NewsBundle\\Form\\Type\\Front\\Tag\\CreateType';
        $this->form->type->update = 'Fhm\\NewsBundle\\Form\\Type\\Front\\Tag\\UpdateType';
        $this->translation        = array('FhmNewsBundle', 'news.tag');
    }

    /**
     * @Route
     * (
     *      path="/",
     *      name="fhm_news_tag"
     * )
     * @Template("::FhmNews/Front/Tag/index.html.twig")
     */
    public function indexAction()
    {
        return parent::indexAction();
    }

    /**
     * @Route
     * (
     *      path="/create",
     *      name="fhm_news_tag_create"
     * )
     * @Template("::FhmNews/Front/Tag/create.html.twig")
     */
    public function createAction(Request $request)
    {
        // For activate this route, delete next line
        throw $this->createNotFoundException($this->fhm_tools->trans('fhm.error.route', array(), 'FhmFhmBundle'));

        return parent::createAction($request);
    }

    /**
    * @Route
    * (
    *      path="/duplicate/{id}",
    *      name="fhm_news_tag_duplicate",
    *      requirements={"id"="[a-z0-9]*"}
    * )
    * @Template("::FhmNews/Front/Tag/create.html.twig")
    */
    public function duplicateAction(Request $request, $id)
    {
        // For activate this route, delete next line
        throw $this->createNotFoundException($this->fhm_tools->trans('fhm.error.route', array(), 'FhmFhmBundle'));

        return parent::duplicateAction($request, $id);
    }

    /**
    * @Route
    * (
    *      path="/update/{id}",
    *      name="fhm_news_tag_update",
    *      requirements={"id"="[a-z0-9]*"}
    * )
    * @Template("::FhmNews/Front/Tag/update.html.twig")
    */
    public function updateAction(Request $request, $id)
    {
        // For activate this route, delete next line
        throw $this->createNotFoundException($this->fhm_tools->trans('fhm.error.route', array(), 'FhmFhmBundle'));

        return parent::updateAction($request, $id);
    }

    /**
    * @Route
    * (
    *      path="/detail/{id}",
    *      name="fhm_news_tag_detail",
    *      requirements={"id"=".+"}
    * )
    * @Template("::FhmNews/Front/Tag/detail.html.twig")
    */
    public function detailAction($id)
    {
        return parent::detailAction($id);
    }

    /**
    * @Route
    * (
    *      path="/delete/{id}",
    *      name="fhm_news_tag_delete",
    *      requirements={"id"="[a-z0-9]*"}
    * )
    */
    public function deleteAction($id)
    {
        // For activate this route, delete next line
        throw $this->createNotFoundException($this->fhm_tools->trans('fhm.error.route', array(), 'FhmFhmBundle'));

        return parent::deleteAction($id);
    }

    /**
    * @Route
    * (
    *      path="/{id}",
    *      name="fhm_news_tag_lite",
    *      requirements={"id"=".+"}
    * )
    * @Template("::FhmNews/Front/Tag/detail.html.twig")
    */
    public function liteAction($id)
    {
        return $this->detailAction($id);
    }
}