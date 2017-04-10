<?php
namespace Project\ProductBundle\Controller\Ingredient;

use Fhm\FhmBundle\Controller\RefAdminController as FhmController;
use Fhm\FhmBundle\Form\Handler\Admin\CreateHandler;
use Fhm\FhmBundle\Form\Handler\Admin\UpdateHandler;
use Project\ProductBundle\Document\Product;
use Project\ProductBundle\Form\Type\Admin\Ingredient\CreateType;
use Project\ProductBundle\Form\Type\Admin\Ingredient\UpdateType;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * @Route("/admin/productingredient")
 */
class AdminController extends FhmController
{
    /**
     * Constructor
     */
    public function __construct()
    {
        self::$repository  = "FhmProductBundle:ProductIngredient";
        self::$domain      = "FhmProductBundle";
        self::$translation = "product.ingredient";
        self::$route       = "product_ingredient";
        self::$source      = "fhm";

        self::$form  = new \stdClass();

        self::$form->createType    = CreateType::class;
        self::$form->updateType    = UpdateType::class;
        self::$form->createHandler = CreateHandler::class;
        self::$form->updateHandler = UpdateHandler::class;
    }

    /**
     * @Route
     * (
     *      path="/",
     *      name="fhm_admin_product_ingredient"
     * )
     * @Template("::FhmProduct/Admin/Ingredient/index.html.twig")
     */
    public function indexAction()
    {
        return parent::indexAction();
    }

    /**
     * @Route
     * (
     *      path="/create",
     *      name="fhm_admin_product_ingredient_create"
     * )
     * @Template("::FhmProduct/Admin/Ingredient/create.html.twig")
     */
    public function createAction(Request $request)
    {
        return parent::createAction($request);
    }

    /**
     * @Route
     * (
     *      path="/duplicate/{id}",
     *      name="fhm_admin_product_ingredient_duplicate",
     *      requirements={"id"="[a-z0-9]*"}
     * )
     * @Template("::FhmProduct/Admin/Ingredient/create.html.twig")
     */
    public function duplicateAction(Request $request, $id)
    {
        return parent::duplicateAction($request, $id);
    }

    /**
     * @Route
     * (
     *      path="/update/{id}",
     *      name="fhm_admin_product_ingredient_update",
     *      requirements={"id"="[a-z0-9]*"}
     * )
     * @Template("::FhmProduct/Admin/Ingredient/update.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        return parent::updateAction($request, $id);
    }

    /**
     * @Route
     * (
     *      path="/detail/{id}",
     *      name="fhm_admin_product_ingredient_detail",
     *      requirements={"id"="[a-z0-9]*"}
     * )
     * @Template("::FhmProduct/Admin/Ingredient/detail.html.twig")
     */
    public function detailAction($id)
    {
        return parent::detailAction($id);
    }

    /**
     * @Route
     * (
     *      path="/delete/{id}",
     *      name="fhm_admin_product_ingredient_delete",
     *      requirements={"id"="[a-z0-9]*"}
     * )
     */
    public function deleteAction($id)
    {
        $response = parent::deleteAction($id);
        $document = $this->get('fhm_tools')->dmRepository(self::$repository)->find($id);
        $this->_ingredientDelete($id, $document ? false : true);

        return $response;
    }

    /**
     * @Route
     * (
     *      path="/undelete/{id}",
     *      name="fhm_admin_product_ingredient_undelete",
     *      requirements={"id"="[a-z0-9]*"}
     * )
     */
    public function undeleteAction($id)
    {
        $this->_ingredientUndelete($id);

        return parent::undeleteAction($id);
    }

    /**
     * @Route
     * (
     *      path="/activate/{id}",
     *      name="fhm_admin_product_ingredient_activate",
     *      requirements={"id"="[a-z0-9]*"}
     * )
     */
    public function activateAction($id)
    {
        $this->_ingredientActive($id, true);

        return parent::activateAction($id);
    }

    /**
     * @Route
     * (
     *      path="/deactivate/{id}",
     *      name="fhm_admin_product_ingredient_deactivate",
     *      requirements={"id"="[a-z0-9]*"}
     * )
     */
    public function deactivateAction($id)
    {
        $this->_ingredientActive($id, false);

        return parent::deactivateAction($id);
    }

    /**
     * @Route
     * (
     *      path="/import",
     *      name="fhm_admin_product_ingredient_import"
     * )
     * @Template("::FhmProduct/Admin/Ingredient/import.html.twig")
     */
    public function importAction(Request $request)
    {
        return parent::importAction($request);
    }

    /**
     * @Route
     * (
     *      path="/export",
     *      name="fhm_admin_product_ingredient_export"
     * )
     * @Template("::FhmProduct/Admin/Ingredient/export.html.twig")
     */
    public function exportAction(Request $request)
    {
        return parent::exportAction($request);
    }

    /**
     * @Route
     * (
     *      path="/grouping",
     *      name="fhm_admin_product_ingredient_grouping"
     * )
     */
    public function groupingAction(Request $request)
    {
        return parent::groupingAction($request);
    }

    /**
     * Ingredient delete
     *
     * @param String  $id
     * @param Boolean $delete
     *
     * @return self
     */
    private function _ingredientDelete($id, $delete)
    {
        $documents = $this->get('fhm_tools')->dmRepository(self::$repository)->getSons($id);
        foreach($documents as $document)
        {
            $this->_ingredientDelete($document->getId(), $delete);
            if($delete)
            {
                $products = $this->get('fhm_tools')->dmRepository("ProjectProductBundle:Product")->getByIngredient($document->getId());
                foreach($products as $product)
                {
                    $product->removeIngredient($document);
                    $this->get('fhm_tools')->dmPersist($product);
                }
                $this->get('fhm_tools')->dmRemove($document);
            }
            else
            {
                $document->setDelete(true);
                $this->get('fhm_tools')->dmPersist($document);
            }
        }

        return $this;
    }

    /**
     * Ingredient undelete
     *
     * @param String $id
     *
     * @return self
     */
    private function _ingredientUndelete($id)
    {
        $documents = $this->get('fhm_tools')->dmRepository(self::$repository)->getSons($id);
        foreach($documents as $document)
        {
            $this->_ingredientUndelete($document->getId());
            $document->setDelete(false);
            $this->get('fhm_tools')->dmPersist($document);
        }

        return $this;
    }

    /**
     * Ingredient active
     *
     * @param String  $id
     * @param Boolean $active
     *
     * @return self
     */
    private function _ingredientActive($id, $active)
    {
        $documents = $this->get('fhm_tools')->dmRepository(self::$repository)->getSons($id);
        foreach($documents as $document)
        {
            $this->_ingredientActive($document->getId(), $active);
            $document->setActive($active);
            $this->get('fhm_tools')->dmPersist($document);
        }

        return $this;
    }
}