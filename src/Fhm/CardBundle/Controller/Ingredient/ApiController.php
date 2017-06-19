<?php
namespace Fhm\CardBundle\Controller\Ingredient;

use Fhm\CardBundle\Form\Type\Api\Ingredient\CreateType;
use Fhm\CardBundle\Form\Type\Api\Ingredient\UpdateType;
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
 * @Route("/api/cardingredient")
 * --------------------------------------------
 * Class ApiController
 * @package Fhm\CardBundle\Controller\Ingredient
 */
class ApiController extends FhmController
{
    /**
     * ApiController constructor.
     */
    public function __construct()
    {
        self::$repository = "FhmCardBundle:CardIngredient";
        self::$source = "fhm";
        self::$domain = "FhmCardBundle";
        self::$translation = "card.ingredient";
        self::$route = "card_ingredient";
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
     *      name="fhm_api_card_ingredient"
     * )
     * @Template("::FhmCard/Api/Ingredient/index.html.twig")
     */
    public function indexAction()
    {
        return parent::indexAction();
    }

    /**
     * @Route
     * (
     *      path="/autocomplete",
     *      name="fhm_api_card_ingredient_autocomplete"
     * )
     * @Template("::FhmCard/Api/Ingredient/autocomplete.html.twig")
     */
    public function autocompleteAction(Request $request)
    {
        return parent::autocompleteAction($request);
    }

    /**
     * @Route
     * (
     *      path="/embed/{folder}/{template}/{idCard}/{idProduct}",
     *      name="fhm_api_card_ingredient_embed",
     *      requirements={"idCard"="[a-z0-9]*", "idProduct"="[a-z0-9]*"},
     *      defaults={"folder"="default", "template"="inline"}
     * )
     */
    public function embedAction(Request $request, $idCard, $idProduct, $folder, $template)
    {
        $card     = $this->get('fhm_tools')->dmRepository('FhmCardBundle:Card')->find($idCard);
        $product  = $this->get('fhm_tools')->dmRepository('FhmCardBundle:CardProduct')->find($idProduct);
        // ERROR - unknown
        if($card == "")
        {
            throw $this->createNotFoundException($this->get('translator')->trans('card.error.unknown', array(), self::$domain));
        }
        if($product == "")
        {
            throw $this->createNotFoundException($this->get('translator')->trans('card.product.error.unknown', array(), self::$domain));
        }
        $documents = $this->get('fhm_tools')->dmRepository(self::$repository)->getByProduct($card, $product);
        $inline    = array();
        foreach($documents as $ingredient)
        {
            $inline[] = $ingredient->getName();
        }

        return new Response(
            $this->renderView(
                "::FhmCard/Template/Embed/" . ucfirst(strtolower($folder)) . "/Ingredient/" . $template . ".html.twig",
                array(
                    "card"        => $card,
                    "product"     => $product,
                    "ingredients" => $documents,
                    "inline"      => implode(', ', $inline)
                )
            )
        );
    }

    /**
     * @Route
     * (
     *      path="/editor/{template}/{idCard}",
     *      name="fhm_api_card_ingredient_editor",
     *      requirements={"idCard"="[a-z0-9]*"},
     *      defaults={"template"="default"}
     * )
     */
    public function editorAction($idCard, $template)
    {
        return $this->_model($template)->ingredientIndex($idCard);
    }

    /**
     * @Route
     * (
     *      path="/sort/{template}/{idCard}",
     *      name="fhm_api_card_ingredient_sort",
     *      requirements={"idCard"="[a-z0-9]*"},
     *      defaults={"template"="default"}
     * )
     */
    public function sortAction(Request $request, $idCard, $idCategory, $template)
    {
        return $this->_model($template)->ingredientSort($request, $idCard);
    }

    /**
     * @Route
     * (
     *      path="/create/{template}/{idCard}",
     *      name="fhm_api_card_ingredient_create",
     *      requirements={"idCard"="[a-z0-9]*"},
     *      defaults={"template"="default"}
     * )
     */
    public function createAction(Request $request, $idCard, $template)
    {
        return $this->_model($template)->ingredientCreate($request, $idCard);
    }

    /**
     * @Route
     * (
     *      path="/update/{template}/{idCard}/{idIngredient}",
     *      name="fhm_api_card_ingredient_update",
     *      requirements={"idCard"="[a-z0-9]*", "idIngredient"="[a-z0-9]*"},
     *      defaults={"template"="default", "idIngredient"="0"}
     * )
     */
    public function updateAction(Request $request, $idCard, $idIngredient, $template)
    {
        return $this->_model($template)->ingredientUpdate($request, $idCard, $idIngredient);
    }

    /**
     * @Route
     * (
     *      path="/activate/{template}/{idCard}/{idIngredient}",
     *      name="fhm_api_card_ingredient_activate",
     *      requirements={"idCard"="[a-z0-9]*", "idIngredient"="[a-z0-9]*"},
     *      defaults={"template"="default"}
     * )
     */
    public function activateAction($idCard, $idIngredient, $template)
    {
        return $this->_model($template)->ingredientActivate($idCard, $idIngredient);
    }

    /**
     * @Route
     * (
     *      path="/deactivate/{template}/{idCard}/{idIngredient}",
     *      name="fhm_api_card_ingredient_deactivate",
     *      requirements={"idCard"="[a-z0-9]*", "idIngredient"="[a-z0-9]*"},
     *      defaults={"template"="default"}
     * )
     */
    public function deactivateAction($idCard, $idIngredient, $template)
    {
        return $this->_model($template)->ingredientDeactivate($idCard, $idIngredient);
    }

    /**
     * @Route
     * (
     *      path="/delete/{template}/{idCard}/{idIngredient}",
     *      name="fhm_api_card_ingredient_delete",
     *      requirements={"idCard"="[a-z0-9]*", "idIngredient"="[a-z0-9]*"},
     *      defaults={"template"="default"}
     * )
     */
    public function deleteAction($idCard, $idIngredient, $template)
    {
        return $this->_model($template)->ingredientDelete($idCard, $idIngredient);
    }

    /**
     * @Route
     * (
     *      path="/undelete/{template}/{idCard}/{idIngredient}",
     *      name="fhm_api_card_ingredient_undelete",
     *      requirements={"idCard"="[a-z0-9]*", "idIngredient"="[a-z0-9]*"},
     *      defaults={"template"="default"}
     * )
     */
    public function ingredientUndelete($idCard, $idIngredient, $template)
    {
        return $this->_model($template)->ingredientUndelete($idCard, $idIngredient);
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