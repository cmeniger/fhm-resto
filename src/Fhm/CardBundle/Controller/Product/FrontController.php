<?php
namespace Fhm\CardBundle\Controller\Product;

use Fhm\FhmBundle\Controller\RefFrontController as FhmController;
use Fhm\CardBundle\Document\CardProduct;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * @Route("/cardproduct")
 */
class FrontController extends FhmController
{
    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct('Fhm', 'Card', 'card_product', 'CardProduct');
        $this->form->type->create = 'Fhm\\CardBundle\\Form\\Type\\Front\\Product\\CreateType';
        $this->form->type->update = 'Fhm\\CardBundle\\Form\\Type\\Front\\Product\\UpdateType';
        $this->translation        = array('FhmCardBundle', 'card.product');
    }

    /**
     * @Route
     * (
     *      path="/",
     *      name="fhm_card_product"
     * )
     * @Template("::FhmCard/Front/Product/index.html.twig")
     */
    public function indexAction()
    {
        return parent::indexAction();
    }

    /**
     * @Route
     * (
     *      path="/create",
     *      name="fhm_card_product_create"
     * )
     * @Template("::FhmCard/Front/Product/create.html.twig")
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
     *      name="fhm_card_product_duplicate",
     *      requirements={"id"="[a-z0-9]*"}
     * )
     * @Template("::FhmCard/Front/Product/create.html.twig")
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
     *      name="fhm_card_product_update",
     *      requirements={"id"="[a-z0-9]*"}
     * )
     * @Template("::FhmCard/Front/Product/update.html.twig")
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
     *      name="fhm_card_product_detail",
     *      requirements={"id"=".+"}
     * )
     * @Template("::FhmCard/Front/Product/detail.html.twig")
     */
    public function detailAction($id)
    {
        return parent::detailAction($id);
    }

    /**
     * @Route
     * (
     *      path="/delete/{id}",
     *      name="fhm_card_product_delete",
     *      requirements={"id"="[a-z0-9]*"}
     * )
     */
    public function deleteAction($id)
    {
        $document = $this->dmRepository()->find($id);
        // ERROR - Unknown
        if($document == "")
        {
            throw $this->createNotFoundException($this->get('translator')->trans($this->translation[1] . '.error.unknown', array(), $this->translation[0]));
        }
        // Deactivate
        $document->setDelete(true);
        $this->dmPersist($document);
        // Message
        $this->get('session')->getFlashBag()->add('notice', $this->get('translator')->trans($this->translation[1] . '.admin.activate.flash.ok', array(), $this->translation[0]));

        return $this->redirect($this->getLastRoute());
    }

    /**
     * @Route
     * (
     *      path="/undelete/{id}",
     *      name="fhm_card_product_undelete",
     *      requirements={"id"="[a-z0-9]*"}
     * )
     */
    public function undeleteAction($id)
    {
        $document = $this->dmRepository()->find($id);
        // ERROR - Unknown
        if($document == "")
        {
            throw $this->createNotFoundException($this->get('translator')->trans($this->translation[1] . '.error.unknown', array(), $this->translation[0]));
        }
        // Undelete
        $document->setDelete(false);
        $this->dmPersist($document);
        // Message
        $this->get('session')->getFlashBag()->add('notice', $this->get('translator')->trans($this->translation[1] . '.admin.undelete.flash.ok', array(), $this->translation[0]));

        return $this->redirect($this->getLastRoute());
    }


    /**
     * @Route
     * (
     *      path="/activate/{id}",
     *      name="fhm_card_product_activate",
     *      requirements={"id"="[a-z0-9]*"}
     * )
     */
    public function activateAction($id)
    {
        $document = $this->dmRepository()->find($id);
        // ERROR - Unknown
        if($document == "")
        {
            throw $this->createNotFoundException($this->get('translator')->trans($this->translation[1] . '.error.unknown', array(), $this->translation[0]));
        }
        // activate
        $document->setActive(true);
        $this->dmPersist($document);
        // Message
        $this->get('session')->getFlashBag()->add('notice', $this->get('translator')->trans($this->translation[1] . '.admin.activate.flash.ok', array(), $this->translation[0]));

        return $this->redirect($this->getLastRoute());
    }

    /**
     * @Route
     * (
     *      path="/deactivate/{id}",
     *      name="fhm_card_product_deactivate",
     *      requirements={"id"="[a-z0-9]*"}
     * )
     */
    public function deactivateAction($id)
    {
        $document = $this->dmRepository()->find($id);
        // ERROR - Unknown
        if($document == "")
        {
            throw $this->createNotFoundException($this->get('translator')->trans($this->translation[1] . '.error.unknown', array(), $this->translation[0]));
        }
        // Deactivate
        $document->setActive(false);
        $this->dmPersist($document);
        // Message
        $this->get('session')->getFlashBag()->add('notice', $this->get('translator')->trans($this->translation[1] . '.admin.activate.flash.ok', array(), $this->translation[0]));

        return $this->redirect($this->getLastRoute());
    }


    /**
     * @Route
     * (
     *      path="/{id}",
     *      name="fhm_card_product_lite",
     *      requirements={"id"=".+"}
     * )
     * @Template("::FhmCard/Front/Product/detail.html.twig")
     */
    public function liteAction($id)
    {
        return $this->detailAction($id);
    }
}