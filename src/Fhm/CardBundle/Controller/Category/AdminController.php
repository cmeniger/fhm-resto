<?php
namespace Fhm\CardBundle\Controller\Category;

use Doctrine\Common\Collections\ArrayCollection;
use Fhm\FhmBundle\Controller\RefAdminController as FhmController;
use Fhm\CardBundle\Document\CardCategory;
use Fhm\FhmBundle\Services\Tools;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * @Route("/admin/cardcategory", service="fhm_card_controller_category_admin")
 */
class AdminController extends FhmController
{
    /**
     * AdminController constructor.
     * @param Tools $tools
     */
    public function __construct(Tools $tools)
    {
        $this->setFhmTools($tools);
        parent::__construct('Fhm', 'Card', 'card_category', 'CardCategory');
        $this->form->type->create = 'Fhm\\CardBundle\\Form\\Type\\Admin\\Category\\CreateType';
        $this->form->type->update = 'Fhm\\CardBundle\\Form\\Type\\Admin\\Category\\UpdateType';
        $this->translation        = array('FhmCardBundle', 'card.category');
    }

    /**
     * @Route
     * (
     *      path="/",
     *      name="fhm_admin_card_category"
     * )
     * @Template("::FhmCard/Admin/Category/index.html.twig")
     */
    public function indexAction()
    {
        return parent::indexAction();
    }

    /**
     * @Route
     * (
     *      path="/create",
     *      name="fhm_admin_card_category_create"
     * )
     * @Template("::FhmCard/Admin/Category/create.html.twig")
     */
    public function createAction(Request $request)
    {
        return parent::createAction($request);
    }

    /**
     * @Route
     * (
     *      path="/duplicate/{id}",
     *      name="fhm_admin_card_category_duplicate",
     *      requirements={"id"="[a-z0-9]*"}
     * )
     * @Template("::FhmCard/Admin/Category/create.html.twig")
     */
    public function duplicateAction(Request $request, $id)
    {
        return parent::duplicateAction($request, $id);
    }

    /**
     * @Route
     * (
     *      path="/update/{id}",
     *      name="fhm_admin_card_category_update",
     *      requirements={"id"="[a-z0-9]*"}
     * )
     * @Template("::FhmCard/Admin/Category/update.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        return parent::updateAction($request, $id);
    }

    /**
     * @Route
     * (
     *      path="/detail/{id}",
     *      name="fhm_admin_card_category_detail",
     *      requirements={"id"="[a-z0-9]*"}
     * )
     * @Template("::FhmCard/Admin/Category/detail.html.twig")
     */
    public function detailAction($id)
    {
        return parent::detailAction($id);
    }

    /**
     * @Route
     * (
     *      path="/delete/{id}",
     *      name="fhm_admin_card_category_delete",
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
     *      name="fhm_admin_card_category_undelete",
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
     *      name="fhm_admin_card_category_activate",
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
     *      name="fhm_admin_card_category_deactivate",
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
     *      name="fhm_admin_card_category_import"
     * )
     * @Template("::FhmCard/Admin/Category/import.html.twig")
     */
    public function importAction(Request $request)
    {
        return parent::importAction($request);
    }

    /**
     * @Route
     * (
     *      path="/export",
     *      name="fhm_admin_card_category_export"
     * )
     * @Template("::FhmCard/Admin/Category/export.html.twig")
     */
    public function exportAction(Request $request)
    {
        return parent::exportAction($request);
    }

    /**
     * @Route
     * (
     *      path="/grouping",
     *      name="fhm_admin_card_category_grouping"
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
     *      name="fhm_admin_card_category_sort"
     * )
     */
    public function sortAction(Request $request)
    {
        $id   = $request->get('master');
        $list = json_decode($request->get('list'));
        $this->_treeSort($id, $list);

        return new Response();
    }
}