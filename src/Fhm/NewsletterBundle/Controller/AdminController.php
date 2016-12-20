<?php
namespace Fhm\NewsletterBundle\Controller;

use Fhm\FhmBundle\Controller\RefAdminController as FhmController;
use Fhm\FhmBundle\Form\Handler\Admin\CreateHandler;
use Fhm\FhmBundle\Form\Handler\Admin\UpdateHandler;
use Fhm\NewsletterBundle\Document\Newsletter;
use Fhm\NewsletterBundle\Form\Type\Admin\CreateType;
use Fhm\NewsletterBundle\Form\Type\Admin\UpdateType;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * @Route("/admin/newsletter")
 * ---------------------------------------
 * Class AdminController
 * @package Fhm\NewsletterBundle\Controller
 */
class AdminController extends FhmController
{
    /**
     * FrontController constructor.
     * @param string $repository
     * @param string $source
     * @param string $domain
     * @param string $translation
     * @param $document
     * @param string $route
     */
    public function __construct(
        $repository = "FhmNewsletterBundle:Newsletter",
        $source = "fhm",
        $domain = "FhmNewsletterBundle",
        $translation = "newsletter",
        $document = Newsletter::class,
        $route = 'newsletter'
    ) {
        self::$repository = $repository;
        self::$source = $source;
        self::$domain = $domain;
        self::$translation = $translation;
        self::$document = new $document();
        self::$class = get_class(self::$document);
        self::$route = $route;
    }
    /**
     * @Route
     * (
     *      path="/",
     *      name="fhm_admin_newsletter"
     * )
     * @Template("::FhmNewsletter/Admin/index.html.twig")
     */
    public function indexAction()
    {
        return parent::indexAction();
    }

    /**
     * @Route
     * (
     *      path="/create",
     *      name="fhm_admin_newsletter_create"
     * )
     * @Template("::FhmNewsletter/Admin/create.html.twig")
     */
    public function createAction(Request $request)
    {
        self::$form = new \stdClass();
        self::$form->type = CreateType::class;
        self::$form->handler = CreateHandler::class;
        return parent::createAction($request);
    }

    /**
     * @Route
     * (
     *      path="/duplicate/{id}",
     *      name="fhm_admin_newsletter_duplicate",
     *      requirements={"id"="[a-z0-9]*"}
     * )
     * @Template("::FhmNewsletter/Admin/create.html.twig")
     */
    public function duplicateAction(Request $request, $id)
    {
        self::$form = new \stdClass();
        self::$form->type = CreateType::class;
        self::$form->handler = CreateHandler::class;
        return parent::duplicateAction($request, $id);
    }

    /**
     * @Route
     * (
     *      path="/update/{id}",
     *      name="fhm_admin_newsletter_update",
     *      requirements={"id"="[a-z0-9]*"}
     * )
     * @Template("::FhmNewsletter/Admin/update.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        self::$form = new \stdClass();
        self::$form->type = UpdateType::class;
        self::$form->handler = UpdateHandler::class;
        return parent::updateAction($request, $id);
    }

    /**
     * @Route
     * (
     *      path="/detail/{id}",
     *      name="fhm_admin_newsletter_detail",
     *      requirements={"id"="[a-z0-9]*"}
     * )
     * @Template("::FhmNewsletter/Admin/detail.html.twig")
     */
    public function detailAction($id)
    {
        return parent::detailAction($id);
    }

    /**
     * @Route
     * (
     *      path="/delete/{id}",
     *      name="fhm_admin_newsletter_delete",
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
     *      name="fhm_admin_newsletter_undelete",
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
     *      name="fhm_admin_newsletter_activate",
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
     *      name="fhm_admin_newsletter_deactivate",
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
     *      name="fhm_admin_newsletter_import"
     * )
     * @Template("::FhmNewsletter/Admin/import.html.twig")
     */
    public function importAction(Request $request)
    {
        return parent::importAction($request);
    }

    /**
     * @Route
     * (
     *      path="/export",
     *      name="fhm_admin_newsletter_export"
     * )
     * @Template("::FhmNewsletter/Admin/export.html.twig")
     */
    public function exportAction(Request $request)
    {
        return parent::exportAction($request);
    }
}