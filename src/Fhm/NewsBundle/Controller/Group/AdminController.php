<?php
namespace Fhm\NewsBundle\Controller\Group;

use Fhm\FhmBundle\Controller\RefAdminController as FhmController;
use Fhm\FhmBundle\Form\Handler\Admin\CreateHandler;
use Fhm\FhmBundle\Form\Handler\Admin\UpdateHandler;
use Fhm\FhmBundle\Form\Type\Admin\SearchType;
use Fhm\NewsBundle\Form\Type\Admin\Group\CreateType;
use Fhm\NewsBundle\Form\Type\Admin\Group\UpdateType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * @Route("/admin/newsgroup")
 * ----------------------------------------
 * Class AdminController
 * @package Fhm\NewsBundle\Controller\Group
 */
class AdminController extends FhmController
{
    /**
     * AdminController constructor.
     */
    public function __construct()
    {
        self::$repository = "FhmNewsBundle:NewsGroup";
        self::$source = "fhm";
        self::$domain = "FhmNewsBundle";
        self::$translation = "news.group";
        self::$route = "news_group";
        self::$form = new \stdClass();
        self::$form->createType = CreateType::class;
        self::$form->createHandler = CreateHandler::class;
        self::$form->updateType = UpdateType::class;
        self::$form->updateHandler = UpdateHandler::class;
    }

    /**
     * @Route
     * (
     *      path="/",
     *      name="fhm_admin_news_group"
     * )
     * @Template("::FhmNews/Admin/Group/index.html.twig")
     */
    public function indexAction()
    {
        return parent::indexAction();
    }

    /**
     * @Route
     * (
     *      path="/create",
     *      name="fhm_admin_news_group_create"
     * )
     * @Template("::FhmNews/Admin/Group/create.html.twig")
     */
    public function createAction(Request $request)
    {
        return parent::createAction($request);
    }

    /**
     * @Route
     * (
     *      path="/duplicate/{id}",
     *      name="fhm_admin_news_group_duplicate",
     *      requirements={"id"="[a-z0-9]*"}
     * )
     * @Template("::FhmNews/Admin/Group/create.html.twig")
     */
    public function duplicateAction(Request $request, $id)
    {
        return parent::duplicateAction($request, $id);
    }

    /**
     * @Route
     * (
     *      path="/update/{id}",
     *      name="fhm_admin_news_group_update",
     *      requirements={"id"="[a-z0-9]*"}
     * )
     * @Template("::FhmNews/Admin/Group/update.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        return parent::updateAction($request, $id);
    }

    /**
     * @Route
     * (
     *      path="/detail/{id}",
     *      name="fhm_admin_news_group_detail",
     *      requirements={"id"="[a-z0-9]*"}
     * )
     * @Template("::FhmNews/Admin/Group/detail.html.twig")
     */
    public function detailAction($id)
    {
        $repository = $this->get('fhm.object.manager')->getCurrentRepository(self::$repository);
        $object = $repository->find($id);

        return array_merge(
            array(
                'news1' => $this->get('fhm.object.manager')->getCurrentRepository('FhmNewsBundle:News')->getAllEnable(),
                'news2' => $repository->getListByNews($object->getNews()),
            ),
            parent::detailAction($id)
        );
    }

    /**
     * @Route
     * (
     *      path="/preview/{id}",
     *      name="fhm_admin_news_group_preview",
     *      requirements={"id"="[a-z0-9]*"}
     * )
     * @Template("::FhmNews/Front/Group/detail.html.twig")
     */
    public function previewAction($id)
    {
        $response = parent::detailAction($id);
        $document = $response['object'];
        $form = $this->createForm(SearchType::class);
        $form->setData($this->get('request_stack')->getCurrentRequest()->get($form->getName()));
        $dataSearch = $form->getData();
        $documents = $this->get('fhm_tools')->dmRepository("FhmNewsBundle:News")->getNewsByGroupIndex(
            $document,
            $dataSearch['search']
        );

        return array_merge(
            $response,
            array(
                'documents' => $documents,
                'form' => $form->createView(),
            )
        );
    }

    /**
     * @Route
     * (
     *      path="/delete/{id}",
     *      name="fhm_admin_news_group_delete",
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
     *      name="fhm_admin_news_group_undelete",
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
     *      name="fhm_admin_news_group_activate",
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
     *      name="fhm_admin_news_group_deactivate",
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
     *      name="fhm_admin_news_group_import"
     * )
     * @Template("::FhmNews/Admin/Group/import.html.twig")
     */
    public function importAction(Request $request)
    {
        return parent::importAction($request);
    }

    /**
     * @Route
     * (
     *      path="/export",
     *      name="fhm_admin_news_group_export"
     * )
     * @Template("::FhmNews/Admin/Group/export.html.twig")
     */
    public function exportAction(Request $request)
    {
        return parent::exportAction($request);
    }

    /**
     * @Route
     * (
     *      path="/news",
     *      name="fhm_admin_news_group_news",
     *      requirements={"id"="[a-z0-9]*"}
     * )
     */
    public function newsAction(Request $request)
    {
        $news = json_decode($request->get('list'));
        $document = $this->get('fhm_tools')->dmRepository(self::$repository)->find($request->get('id'));
        foreach ($document->getNews() as $new) {
            $document->removeNews($new);
        }
        foreach ($news as $key => $data) {
            $new = $this->get('fhm_tools')->dmRepository('FhmNewsBundle:News')->find($data->id);
            $document->addNews($new);
        }
        $this->get('fhm_tools')->dmPersist($document);

        return new Response();
    }
}