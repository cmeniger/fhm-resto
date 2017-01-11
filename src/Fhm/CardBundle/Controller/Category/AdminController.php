<?php
namespace Fhm\CardBundle\Controller\Category;

use Fhm\CardBundle\Form\Type\Admin\Category\CreateType;
use Fhm\CardBundle\Form\Type\Admin\Category\UpdateType;
use Fhm\FhmBundle\Controller\RefAdminController as FhmController;
use Fhm\CardBundle\Document\CardCategory;
use Fhm\FhmBundle\Form\Handler\Admin\CreateHandler;
use Fhm\FhmBundle\Form\Handler\Admin\UpdateHandler;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * @Route("/admin/cardcategory")
 * ------------------------------------------
 * Class AdminController
 * @package Fhm\CardBundle\Controller\Category
 */
class AdminController extends FhmController
{
    /**
     * AdminController constructor.
     */
    public function __construct()
    {
        self::$repository = "FhmCardBundle:CardCategory";
        self::$source = "fhm";
        self::$domain = "FhmCardBundle";
        self::$translation = "card.category";
        self::$class = CardCategory::class;
        self::$route = "card_category";
        self::$form = new \stdClass();
        self::$form->createType    = CreateType::class;
        self::$form->createHandler = CreateHandler::class;
        self::$form->updateType    = UpdateType::class;
        self::$form->updateHandler = CreateHandler::class;
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
     *      path="/sort",
     *      name="fhm_admin_card_category_sort"
     * )
     */
    public function sortAction(Request $request)
    {
        $id   = $request->get('master');
        $list = json_decode($request->get('list'));
        $this->get('fhm_tools')->_treeSort($id, $list);

        return new Response();
    }
}