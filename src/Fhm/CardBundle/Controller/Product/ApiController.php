<?php

namespace Fhm\CardBundle\Controller\Product;

use Doctrine\Common\Collections\ArrayCollection;
use Fhm\CardBundle\Form\Type\Api\Product\UpdateType;
use Fhm\CardBundle\Form\Type\Api\Product\CreateType;
use Fhm\FhmBundle\Controller\RefApiController as FhmController;
use Fhm\FhmBundle\Form\Handler\Admin\CreateHandler;
use Fhm\FhmBundle\Form\Handler\Admin\UpdateHandler;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * @Route("/api/cardproduct")
 * -----------------------------------------
 * Class ApiController
 *
 * @package Fhm\CardBundle\Controller\Product
 */
class ApiController extends FhmController
{
    /**
     * ApiController constructor.
     */
    public function __construct()
    {
        self::$repository          = "FhmCardBundle:CardProduct";
        self::$source              = "fhm";
        self::$domain              = "FhmCardBundle";
        self::$translation         = "card.product";
        self::$route               = "card_product";
        self::$form                = new \stdClass();
        self::$form->createType    = CreateType::class;
        self::$form->createHandler = CreateHandler::class;
        self::$form->updateType    = UpdateType::class;
        self::$form->updateHandler = UpdateHandler::class;
    }

    /**
     * @Route
     * (
     *      path="/",
     *      name="fhm_api_card_product"
     * )
     * @Template("::FhmCard/Api/Product/index.html.twig")
     */
    public function indexAction()
    {
        return parent::indexAction();
    }

    /**
     * @Route
     * (
     *      path="/autocomplete",
     *      name="fhm_api_card_product_autocomplete"
     * )
     * @Template("::FhmCard/Api/Product/autocomplete.html.twig")
     */
    public function autocompleteAction(Request $request)
    {
        return parent::autocompleteAction($request);
    }

    /**
     * @Route
     * (
     *      path="/embed/{folder}/{template}/{idCard}/{idCategory}",
     *      name="fhm_api_card_product_embed",
     *      requirements={"idCard"="[a-z0-9]*", "idCategory"="[a-z0-9]*"},
     *      defaults={"folder"="default", "template"="inline"}
     * )
     */
    public function embedAction(Request $request, $idCard, $idCategory, $folder, $template)
    {
        $card     = $this->get('fhm_tools')->dmRepository('FhmCardBundle:Card')->find($idCard);
        $category = $this->get('fhm_tools')->dmRepository('FhmCardBundle:CardCategory')->find($idCategory);
        // ERROR - unknown
        if($card == "")
        {
            throw $this->createNotFoundException($this->get('translator')->trans('card.error.unknown', array(), self::$domain));
        }
        if($category == "")
        {
            throw $this->createNotFoundException($this->get('translator')->trans(self::$translation . '.error.unknown', array(), self::$domain));
        }

        return new Response(
            $this->renderView(
                "::FhmCard/Template/Embed/" . ucfirst(strtolower($folder)) . "/Product/" . $template . ".html.twig",
                array(
                    "card"     => $card,
                    "category" => $category,
                    "products" => $this->get('fhm_tools')->dmRepository(self::$repository)->getByCategory($card, $category),
                    "folder"   => ucfirst(strtolower($folder))
                )
            )
        );
    }

    /**
     * @Route
     * (
     *      path="/editor/{template}/{idCard}/{idCategory}",
     *      name="fhm_api_card_product_editor",
     *      requirements={"idCard"="[a-z0-9]*"},
     *      defaults={"template"="default", "idCategory"=0}
     * )
     */
    public function editorAction($idCard, $idCategory, $template)
    {
        return $this->_model($template)->productIndex($idCard, $idCategory);
    }

    /**
     * @Route
     * (
     *      path="/search/{template}/{idCard}",
     *      name="fhm_api_card_product_search",
     *      requirements={"idCard"="[a-z0-9]*"},
     *      defaults={"template"="default"}
     * )
     */
    public function searchAction(Request $request, $idCard, $template)
    {
        return $this->_model($template)->productSearch($request, $idCard);
    }

    /**
     * @Route
     * (
     *      path="/sort/{template}/{idCard}/{idMaster}",
     *      name="fhm_api_card_product_sort",
     *      requirements={"idCard"="[a-z0-9]*"},
     *      defaults={"template"="default", "idMaster"=0}
     * )
     */
    public function sortAction(Request $request, $idCard, $idMaster, $template)
    {
        return $this->_model($template)->productSort($request, $idCard, $idMaster);
    }

    /**
     * @Route
     * (
     *      path="/create/{template}/{idCard}/{idMaster}/{idCategory}",
     *      name="fhm_api_card_product_create",
     *      requirements={"idCard"="[a-z0-9]*", "idCategory"="[a-z0-9]*"},
     *      defaults={"template"="default", "idMaster"="0", "idCategory"=0}
     * )
     */
    public function createAction(Request $request, $idCard, $idMaster, $idCategory, $template)
    {
        return $this->_model($template)->productCreate($request, $idCard, $idCategory, $idMaster);
    }

    /**
     * @Route
     * (
     *      path="/update/{template}/{idCard}/{idMaster}/{idCategory}/{idProduct}",
     *      name="fhm_api_card_product_update",
     *      requirements={"idCard"="[a-z0-9]*", "idProduct"="[a-z0-9]*"},
     *      defaults={"template"="default", "idMaster"=0, "idCategory"=0}
     * )
     */
    public function updateAction(Request $request, $idCard, $idMaster, $idCategory, $idProduct, $template)
    {
        return $this->_model($template)->productUpdate($request, $idCard, $idCategory, $idProduct, $idMaster);
    }

    /**
     * @Route
     * (
     *      path="/activate/{template}/{idCard}/{idMaster}/{idCategory}/{idProduct}",
     *      name="fhm_api_card_product_activate",
     *      requirements={"idCard"="[a-z0-9]*", "idProduct"="[a-z0-9]*"},
     *      defaults={"template"="default", "idMaster"="0", "idCategory"=0}
     * )
     */
    public function activateAction($idCard, $idMaster, $idCategory, $idProduct, $template)
    {
        return $this->_model($template)->productActivate($idCard, $idCategory, $idProduct, $idMaster);
    }

    /**
     * @Route
     * (
     *      path="/deactivate/{template}/{idCard}/{idMaster}/{idCategory}/{idProduct}",
     *      name="fhm_api_card_product_deactivate",
     *      requirements={"idCard"="[a-z0-9]*", "idProduct"="[a-z0-9]*"},
     *      defaults={"template"="default", "idMaster"=0, "idCategory"=0}
     * )
     */
    public function deactivateAction($idCard, $idCategory, $idMaster, $idProduct, $template)
    {
        return $this->_model($template)->productDeactivate($idCard, $idCategory, $idProduct, $idMaster);
    }

    /**
     * @Route
     * (
     *      path="/delete/{template}/{idCard}/{idMaster}/{idCategory}/{idProduct}",
     *      name="fhm_api_card_product_delete",
     *      requirements={"idCard"="[a-z0-9]*", "idProduct"="[a-z0-9]*"},
     *      defaults={"template"="default", "idMaster"=0, "idCategory"=0}
     * )
     */
    public function deleteAction($idCard, $idCategory, $idMaster, $idProduct, $template)
    {
        return $this->_model($template)->productDelete($idCard, $idCategory, $idProduct, $idMaster);
    }

    /**
     * @Route
     * (
     *      path="/undelete/{template}/{idCard}/{idMaster}/{idCategory}/{idProduct}",
     *      name="fhm_api_card_product_undelete",
     *      requirements={"idCard"="[a-z0-9]*", "idProduct"="[a-z0-9]*"},
     *      defaults={"template"="default", "idMaster"=0, "idCategory"=0}
     * )
     */
    public function undeleteAction($idCard, $idCategory, $idMaster, $idProduct, $template)
    {
        return $this->_model($template)->productUndelete($idCard, $idCategory, $idProduct, $idMaster);
    }

    /**
     * @param $template
     *
     * @return string
     */
    private function _model($template)
    {
        $model = $this->get('fhm_card_model_default');
        if($this->has('fhm_card_model_' . $template))
        {
            $model = $this->get('fhm_card_model_' . $template);
        }

        return $model;
    }
}