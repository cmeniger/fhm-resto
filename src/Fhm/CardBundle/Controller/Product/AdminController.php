<?php
namespace Fhm\CardBundle\Controller\Product;

use Fhm\CardBundle\Form\Type\Admin\Product\CreateType;
use Fhm\CardBundle\Form\Type\Admin\Product\UpdateType;
use Fhm\FhmBundle\Controller\RefAdminController as FhmController;
use Fhm\CardBundle\Document\CardProduct;
use Fhm\FhmBundle\Services\Tools;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * @Route("/admin/cardproduct", service="fhm_card_controller_product_admin")
 */
class AdminController extends FhmController
{
    /**
     * Constructor
     */
    public function __construct(Tools $tools)
    {
        $this->setFhmTools($tools);
        parent::__construct('Fhm', 'Card', 'card_product', 'CardProduct');
        $this->form->type->create = CreateType::class;
        $this->form->type->update = UpdateType::class;
        $this->translation        = array('FhmCardBundle', 'card.product');
    }

    /**
     * @Route
     * (
     *      path="/",
     *      name="fhm_admin_card_product"
     * )
     * @Template("::FhmCard/Admin/Product/index.html.twig")
     */
    public function indexAction()
    {
        return parent::indexAction();
    }

    /**
     * @Route
     * (
     *      path="/create",
     *      name="fhm_admin_card_product_create"
     * )
     * @Template("::FhmCard/Admin/Product/create.html.twig")
     */
    public function createAction(Request $request)
    {
        return parent::createAction($request);
    }

    /**
     * @Route
     * (
     *      path="/duplicate/{id}",
     *      name="fhm_admin_card_product_duplicate",
     *      requirements={"id"="[a-z0-9]*"}
     * )
     * @Template("::FhmCard/Admin/Product/create.html.twig")
     */
    public function duplicateAction(Request $request, $id)
    {
        return parent::duplicateAction($request, $id);
    }

    /**
     * @Route
     * (
     *      path="/update/{id}",
     *      name="fhm_admin_card_product_update",
     *      requirements={"id"="[a-z0-9]*"}
     * )
     * @Template("::FhmCard/Admin/Product/update.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        return parent::updateAction($request, $id);
    }

    /**
     * @Route
     * (
     *      path="/detail/{id}",
     *      name="fhm_admin_card_product_detail",
     *      requirements={"id"="[a-z0-9]*"}
     * )
     * @Template("::FhmCard/Admin/Product/detail.html.twig")
     */
    public function detailAction($id)
    {
        return parent::detailAction($id);
    }

    /**
     * @Route
     * (
     *      path="/delete/{id}",
     *      name="fhm_admin_card_product_delete",
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
     *      name="fhm_admin_card_product_undelete",
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
     *      name="fhm_admin_card_product_activate",
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
     *      name="fhm_admin_card_product_deactivate",
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
     *      name="fhm_admin_card_product_import"
     * )
     * @Template("::FhmCard/Admin/Product/import.html.twig")
     */
    public function importAction(Request $request)
    {
        return parent::importAction($request);
    }

    /**
     * @Route
     * (
     *      path="/export",
     *      name="fhm_admin_card_product_export"
     * )
     * @Template("::FhmCard/Admin/Product/export.html.twig")
     */
    public function exportAction(Request $request)
    {
        return parent::exportAction($request);
    }

    /**
     * @Route
     * (
     *      path="/grouping",
     *      name="fhm_admin_card_product_grouping"
     * )
     */
    public function groupingAction(Request $request)
    {
        return parent::groupingAction($request);
    }

    /**
     * @Route
     * (
     *      path="/sort",
     *      name="fhm_admin_card_product_sort"
     * )
     */
    public function sortAction(Request $request)
    {
        $id   = $request->get('master');
        $list = json_decode($request->get('list'));
       // $this->_treeSort($id, $list);

        return new Response();
    }
}