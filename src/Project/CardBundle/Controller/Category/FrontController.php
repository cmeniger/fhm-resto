<?php
namespace Fhm\CardBundle\Controller\Category;

use Fhm\FhmBundle\Controller\RefFrontController as FhmController;
use Fhm\CardBundle\Document\CardCategory;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * @Route("/cardcategory")
 */
class FrontController extends FhmController
{
    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct('Fhm', 'Card', 'card_category', 'CardCategory');
        $this->form->type->create = 'Fhm\\CardBundle\\Form\\Type\\Front\\Category\\CreateType';
        $this->form->type->update = 'Fhm\\CardBundle\\Form\\Type\\Front\\Category\\UpdateType';
        $this->translation        = array('FhmCardBundle', 'card.category');
    }

    /**
     * @Route
     * (
     *      path="/",
     *      name="fhm_card_category"
     * )
     * @Template("::FhmCard/Front/Category/index.html.twig")
     */
    public function indexAction()
    {
        // For activate this route, delete next line
        throw $this->createNotFoundException($this->get('translator')->trans('fhm.error.route', array(), 'FhmFhmBundle'));

        return parent::indexAction();
    }

    /**
     * @Route
     * (
     *      path="/create",
     *      name="fhm_card_category_create"
     * )
     * @Template("::FhmCard/Front/Category/create.html.twig")
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
     *      name="fhm_card_category_duplicate",
     *      requirements={"id"="[a-z0-9]*"}
     * )
     * @Template("::FhmCard/Front/Category/create.html.twig")
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
     *      name="fhm_card_category_update",
     *      requirements={"id"="[a-z0-9]*"}
     * )
     * @Template("::FhmCard/Front/Category/update.html.twig")
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
     *      name="fhm_card_category_detail",
     *      requirements={"id"=".+"}
     * )
     * @Template("::FhmCard/Front/Category/detail.html.twig")
     */
    public function detailAction($id)
    {
        // For activate this route, delete next line
        throw $this->createNotFoundException($this->get('translator')->trans('fhm.error.route', array(), 'FhmFhmBundle'));

        return parent::detailAction($id);
    }

    /**
     * @Route
     * (
     *      path="/delete/{id}",
     *      name="fhm_card_category_delete",
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
     *      name="fhm_card_category_lite",
     *      requirements={"id"=".+"}
     * )
     * @Template("::FhmCard/Front/Category/detail.html.twig")
     */
    public function liteAction($id)
    {
        return $this->detailAction($id);
    }
}