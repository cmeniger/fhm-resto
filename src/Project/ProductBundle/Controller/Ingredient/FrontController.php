<?php
namespace Project\ProductBundle\Controller\Ingredient;

use Fhm\FhmBundle\Controller\RefFrontController as FhmController;
use Fhm\ProductBundle\Document\Product;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * @Route("/productingredient")
 */
class FrontController extends FhmController
{
    /**
     * Constructor
     */
    public function __construct()
    {
        self::$repository  = "ProjectProductBundle:ProductIngredient";
        self::$domain      = "ProjectProductBundle";
        self::$translation = "ingredient";
        self::$route       = "ingredient";
        self::$source      = "project";
    }

    /**
     * @Route
     * (
     *      path="/",
     *      name="fhm_product_ingredient"
     * )
     * @Template("::FhmProduct/Front/Ingredient/index.html.twig")
     */
    public function indexAction()
    {
        return parent::indexAction();
    }

    /**
     * @Route
     * (
     *      path="/create",
     *      name="fhm_product_ingredient_create"
     * )
     * @Template("::FhmProduct/Front/Ingredient/create.html.twig")
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
    *      name="fhm_product_ingredient_duplicate",
    *      requirements={"id"="[a-z0-9]*"}
    * )
    * @Template("::FhmProduct/Front/Ingredient/create.html.twig")
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
    *      name="fhm_product_ingredient_update",
    *      requirements={"id"="[a-z0-9]*"}
    * )
    * @Template("::FhmProduct/Front/Ingredient/update.html.twig")
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
    *      name="fhm_product_ingredient_detail",
    *      requirements={"id"=".+"}
    * )
    * @Template("::FhmProduct/Front/Ingredient/detail.html.twig")
    */
    public function detailAction($id)
    {
        // ERROR - Unknown route
        if(!$this->routeExists('fhm_' . self::$route) || !$this->routeExists('fhm_' . self::$route . '_detail'))
        {
            throw $this->createNotFoundException($this->get('translator')->trans('fhm.error.route', array(), 'FhmFhmBundle'));
        }
        $products = $this->get('fhm_tools')->dmRepository('FhmProductBundle:Product')->findAll();
        $document = $this->get('fhm_tools')->dmRepository(self::$repository)->getById($id);
        $document = ($document) ? $document : $this->get('fhm_tools')->dmRepository(self::$repository)->getByAlias($id);
        $document = ($document) ? $document : $this->get('fhm_tools')->dmRepository(self::$repository)->getByName($id);

        return array(
            'products'    => $products,
            'document'    => $document,
            'breadcrumbs' => array(
                array(
                    'link' => $this->get('router')->generate('project_home'),
                    'text' => $this->get('translator')->trans('project.home.breadcrumb', array(), 'ProjectDefaultBundle'),
                ),
                array(
                    'link' => $this->get('router')->generate('fhm_' . self::$route),
                    'text' => $this->get('translator')->trans(self::$translation . '.front.index.breadcrumb', array(), self::$translation),
                ),
                array(
                    'link'    => $this->get('router')->generate('fhm_' . self::$route . '_detail', array('id' => $id)),
                    'text'    => $this->get('translator')->trans(self::$translation . '.front.detail.breadcrumb', array('%name%' => $document->getName()), self::$translation),
                    'current' => true
                )
            )
        );
    }





    /**
    * @Route
    * (
    *      path="/delete/{id}",
    *      name="fhm_product_ingredient_delete",
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
    *      name="fhm_product_ingredient_lite",
    *      requirements={"id"=".+"}
    * )
    * @Template("::FhmProduct/Front/Ingredient/detail.html.twig")
    */
    public function liteAction($id)
    {
        return $this->detailAction($id);
    }
}