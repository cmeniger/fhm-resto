<?php

namespace Fhm\ArticleBundle\Controller;

use Fhm\ArticleBundle\Form\Type\Admin\CreateType;
use Fhm\ArticleBundle\Form\Type\Admin\UpdateType;
use Fhm\FhmBundle\Controller\RefAdminController as FhmController;
use Fhm\FhmBundle\Form\Handler\Admin\CreateHandler;
use Fhm\FhmBundle\Form\Handler\Admin\UpdateHandler;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Doctrine\Common\Annotations\Annotation;

/**
 * @Route("/admin/article")
 * --------------------------------------
 * Class AdminController
 *
 * @package Fhm\ArticleBundle\Controller
 */
class AdminController extends FhmController
{
    /**
     * AdminController constructor.
     */
    public function __construct()
    {
        self::$repository  = "FhmArticleBundle:Article";
        self::$domain      = "FhmArticleBundle";
        self::$translation = "article";
        self::$route       = "article";
        self::$source      = "fhm";
        self::$form = new \stdClass();
        self::$form->createType    = CreateType::class;
        self::$form->updateType    = UpdateType::class;
        self::$form->createHandler = CreateHandler::class;
        self::$form->updateHandler = UpdateHandler::class;
    }

    /**
     * @Route
     * (
     *      path="/",
     *      name="fhm_admin_article"
     * )
     * @Template("::FhmArticle/Admin/index.html.twig")
     */
    public function indexAction()
    {
        return parent::indexAction();
    }

    /**
     * @Route
     * (
     *      path="/create",
     *      name="fhm_admin_article_create"
     * )
     * @Template("::FhmArticle/Admin/create.html.twig")
     */
    public function createAction(Request $request)
    {
        return parent::createAction($request);
    }

    /**
     * @Route
     * (
     *      path="/duplicate/{id}",
     *      name="fhm_admin_article_duplicate",
     *      requirements={"id"="[a-z0-9]*"}
     * )
     * @Template("::FhmArticle/Admin/create.html.twig")
     */
    public function duplicateAction(Request $request, $id)
    {
        return parent::duplicateAction($request, $id);
    }

    /**
     * @Route
     * (
     *      path="/update/{id}",
     *      name="fhm_admin_article_update",
     *      requirements={"id"="[a-z0-9]*"}
     * )
     * @Template("::FhmArticle/Admin/update.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        return parent::updateAction($request, $id);
    }

    /**
     * @Route
     * (
     *      path="/detail/{id}",
     *      name="fhm_admin_article_detail",
     *      requirements={"id"="[a-z0-9]*"}
     * )
     * @Template("::FhmArticle/Admin/detail.html.twig")
     */
    public function detailAction($id)
    {
        return parent::detailAction($id);
    }

    /**
     * @Route
     * (
     *      path="/preview/{id}",
     *      name="fhm_admin_article_preview",
     *      requirements={"id"="[a-z0-9]*"}
     * )
     * @Template("::FhmArticle/Front/detail.html.twig")
     */
    public function previewAction($id)
    {
        return parent::detailAction($id);
    }

    /**
     * @Route
     * (
     *      path="/delete/{id}",
     *      name="fhm_admin_article_delete",
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
     *      name="fhm_admin_article_undelete",
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
     *      name="fhm_admin_article_activate",
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
     *      name="fhm_admin_article_deactivate",
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
     *      name="fhm_admin_article_import"
     * )
     * @Template("::FhmArticle/Admin/import.html.twig")
     */
    public function importAction(Request $request)
    {
        return parent::importAction($request);
    }

    /**
     * @Route
     * (
     *      path="/export",
     *      name="fhm_admin_article_export"
     * )
     * @Template("::FhmArticle/Admin/export.html.twig")
     */
    public function exportAction(Request $request)
    {
        return parent::exportAction($request);
    }
}