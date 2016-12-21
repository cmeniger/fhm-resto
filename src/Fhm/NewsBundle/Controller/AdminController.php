<?php
namespace Fhm\NewsBundle\Controller;

use Fhm\FhmBundle\Controller\RefAdminController as FhmController;
use Fhm\FhmBundle\Form\Handler\Admin\CreateHandler;
use Fhm\FhmBundle\Form\Handler\Admin\UpdateHandler;
use Fhm\NewsBundle\Document\News;
use Fhm\NewsBundle\Form\Type\Admin\CreateType;
use Fhm\NewsBundle\Form\Type\Admin\UpdateType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * @Route("/admin/news")
 * ----------------------------------
 * Class AdminController
 * @package Fhm\NewsBundle\Controller
 */
class AdminController extends FhmController
{
    /**
     * AdminController constructor.
     * @param string $repository
     * @param string $source
     * @param string $domain
     * @param string $translation
     * @param $document
     * @param string $route
     */
    public function __construct(
        $repository = "FhmNewsBundle:News",
        $source = "fhm",
        $domain = "FhmNewsBundle",
        $translation = "news",
        $document = News::class,
        $route = 'news'
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
     *      name="fhm_admin_news"
     * )
     * @Template("::FhmNews/Admin/index.html.twig")
     */
    public function indexAction()
    {
        return parent::indexAction();
    }

    /**
     * @Route
     * (
     *      path="/create",
     *      name="fhm_admin_news_create"
     * )
     * @Template("::FhmNews/Admin/create.html.twig")
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
     *      name="fhm_admin_news_duplicate",
     *      requirements={"id"="[a-z0-9]*"}
     * )
     * @Template("::FhmNews/Admin/create.html.twig")
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
     *      name="fhm_admin_news_update",
     *      requirements={"id"="[a-z0-9]*"}
     * )
     * @Template("::FhmNews/Admin/update.html.twig")
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
     *      name="fhm_admin_news_detail",
     *      requirements={"id"="[a-z0-9]*"}
     * )
     * @Template("::FhmNews/Admin/detail.html.twig")
     */
    public function detailAction($id)
    {
        $document = $this->get('fhm_tools')->dmRepository(self::$repository)->find($id);

        return array_merge(
            array(
                'newsgroups1' => $this->get('fhm_tools')->dmRepository('FhmNewsBundle:NewsGroup')->getListEnable(),
                'newsgroups2' => $this->getList($document->getNewsgroups()),
            ),
            parent::detailAction($id)
        );
    }

    /**
     * @Route
     * (
     *      path="/preview/{id}",
     *      name="fhm_admin_news_preview",
     *      requirements={"id"="[a-z0-9]*"}
     * )
     * @Template("::FhmNews/Front/detail.html.twig")
     */
    public function previewAction($id)
    {
        return parent::detailAction($id);
    }

    /**
     * @Route
     * (
     *      path="/delete/{id}",
     *      name="fhm_admin_news_delete",
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
     *      name="fhm_admin_news_undelete",
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
     *      name="fhm_admin_news_activate",
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
     *      name="fhm_admin_news_deactivate",
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
     *      name="fhm_admin_news_import"
     * )
     * @Template("::FhmNews/Admin/import.html.twig")
     */
    public function importAction(Request $request)
    {
        return parent::importAction($request);
    }

    /**
     * @Route
     * (
     *      path="/export",
     *      name="fhm_admin_news_export"
     * )
     * @Template("::FhmNews/Admin/export.html.twig")
     */
    public function exportAction(Request $request)
    {
        return parent::exportAction($request);
    }

    /**
     * @Route
     * (
     *      path="/newsgroup",
     *      name="fhm_admin_news_newsgroup",
     *      requirements={"id"="[a-z0-9]*"}
     * )
     */
    public function newsgroupAction(Request $request)
    {
        $newsgroups = json_decode($request->get('list'));
        $document = $this->get('fhm_tools')->dmRepository(self::$repository)->find($request->get('id'));
        foreach ($document->getNewsgroups() as $newsgroup) {
            $document->removeNewsgroup($newsgroup);
        }
        foreach ($newsgroups as $data) {
            $newsgroup = $this->get('fhm_tools')->dmRepository('FhmNewsBundle:NewsGroup')->find($data->id);
            $document->addNewsgroup($newsgroup);
        }
        $this->get('fhm_tools')->dmPersist($document);

        return new Response();
    }
}