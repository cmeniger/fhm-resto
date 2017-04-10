<?php
namespace Project\CategoryBundle\Controller;

use Fhm\FhmBundle\Controller\RefFrontController as FhmController;
use Fhm\CategoryBundle\Document\Category;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * @Route("/category")
 */
class FrontController extends FhmController
{
    /**
     * Constructor
     */
    public function __construct()
    {
        self::$repository  = "ProjectCategoryBundle:Category";
        self::$domain      = "ProjectCategoryBundle";
        self::$translation = "category";
        self::$route       = "category";
        self::$source      = "project";
    }

    /**
     * @Route
     * (
     *      path="/",
     *      name="fhm_category"
     * )
     * @Template("::FhmCategory/Front/index.html.twig")
     */
    public function indexAction()
    {
        return parent::indexAction();
    }


    /**
     * @Route
     * (
     *      path="/create",
     *      name="fhm_category_create"
     * )
     * @Template("::FhmCategory/Front/create.html.twig")
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
     *      name="fhm_category_duplicate",
     *      requirements={"id"="[a-z0-9]*"}
     * )
     * @Template("::FhmCategory/Front/create.html.twig")
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
     *      name="fhm_category_update",
     *      requirements={"id"="[a-z0-9]*"}
     * )
     * @Template("::FhmCategory/Front/update.html.twig")
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
     *      name="fhm_category_detail",
     *      requirements={"id"=".+"}
     * )
     * @Template("::FhmCategory/Front/detail.html.twig")
     */
    public function detailAction($id)
    {
        return parent::detailAction($id);
    }

    /**
     * @Route
     * (
     *      path="/delete/{id}",
     *      name="fhm_category_delete",
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
     *      name="fhm_category_lite",
     *      requirements={"id"=".+"}
     * )
     * @Template("::FhmCategory/Front/detail.html.twig")
     */
    public function liteAction($id)
    {
        $document = $this->get('fhm_tools')->dmRepository(self::$repository)->getById($id);
        $document = ($document) ? $document : $this->get('fhm_tools')->dmRepository(self::$repository)->getByAlias($id);
        $document = ($document) ? $document : $this->get('fhm_tools')->dmRepository(self::$repository)->getByName($id);

        return $this->detailAction($document ? $document->getId() : "");
    }
}