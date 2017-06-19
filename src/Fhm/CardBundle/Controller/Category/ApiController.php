<?php

namespace Fhm\CardBundle\Controller\Category;

use Fhm\CardBundle\Form\Type\Api\Category\CreateType;
use Fhm\CardBundle\Form\Type\Api\Category\UpdateType;
use Fhm\FhmBundle\Controller\RefApiController as FhmController;
use Fhm\FhmBundle\Form\Handler\Admin\CreateHandler;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @Route("/api/cardcategory")
 * ------------------------------------------
 * Class ApiController
 *
 * @package Fhm\CardBundle\Controller\Category
 */
class ApiController extends FhmController
{
    /**
     * ApiController constructor.
     */
    public function __construct()
    {
        self::$repository          = "FhmCardBundle:CardCategory";
        self::$source              = "fhm";
        self::$domain              = "FhmCardBundle";
        self::$translation         = "card.category";
        self::$route               = "card_category";
        self::$form                = new \stdClass();
        self::$form->createType    = CreateType::class;
        self::$form->createHandler = CreateHandler::class;
        self::$form->updateType    = UpdateType::class;
    }

    /**
     * @Route
     * (
     *      path="/",
     *      name="fhm_api_card_category"
     * )
     * @Template("::FhmCard/Api/Category/index.html.twig")
     */
    public function indexAction()
    {
        return parent::indexAction();
    }

    /**
     * @Route
     * (
     *      path="/autocomplete",
     *      name="fhm_api_card_category_autocomplete"
     * )
     * @Template("::FhmCard/Api/Category/autocomplete.html.twig")
     */
    public function autocompleteAction(Request $request)
    {
        return parent::autocompleteAction($request);
    }

    /**
     * @Route
     * (
     *      path="/embed/{folder}/{template}/{idCard}/{idCategory}",
     *      name="fhm_api_card_category_embed",
     *      requirements={"idCard"="[a-z0-9]*", "idCategory"="[a-z0-9]*"},
     *      defaults={"folder"="default", "template"="inline"}
     * )
     */
    public function embedAction(Request $request, $idCard, $idCategory, $folder, $template)
    {
        $card     = $this->get('fhm_tools')->dmRepository('FhmCardBundle:Card')->find($idCard);
        $document = $this->get('fhm_tools')->dmRepository(self::$repository)->find($idCategory);
        // ERROR - unknown
        if($card == "")
        {
            throw $this->createNotFoundException($this->get('translator')->trans('card.error.unknown', array(), self::$domain));
        }
        if($document == "")
        {
            throw $this->createNotFoundException($this->get('translator')->trans(self::$translation . '.error.unknown', array(), self::$domain));
        }

        return new Response(
            $this->renderView(
                "::FhmCard/Template/Embed/" . ucfirst(strtolower($folder)) . "/Category/" . $template . ".html.twig",
                array(
                    "card"      => $card,
                    "document"  => $document,
                    "documents" => $this->get('fhm_tools')->dmRepository(self::$repository)->getSons($card, $document),
                    "folder"    => ucfirst(strtolower($folder))
                )
            )
        );
    }

    /**
     * @Route
     * (
     *      path="/editor/{template}/{idCard}/{idCategory}",
     *      name="fhm_api_card_category_editor",
     *      requirements={"idCard"="[a-z0-9]*"},
     *      defaults={"template"="default", "idCategory"=null}
     * )
     */
    public function editorAction($idCard, $idCategory, $template)
    {
        return $this->_model($template)->categoryIndex($idCard, $idCategory);
    }

    /**
     * @Route
     * (
     *      path="/sort/{template}/{idCard}/{idCategory}",
     *      name="fhm_api_card_category_sort",
     *      requirements={"idCard"="[a-z0-9]*"},
     *      defaults={"template"="default", "idCategory"=null}
     * )
     */
    public function sortAction(Request $request, $idCard, $idCategory, $template)
    {
        return $this->_model($template)->categorySort($request, $idCard, $idCategory);
    }

    /**
     * @Route
     * (
     *      path="/create/{template}/{idCard}/{idMaster}/{idCategory}",
     *      name="fhm_api_card_category_create",
     *      requirements={"idCard"="[a-z0-9]*", "idCategory"="[a-z0-9]*"},
     *      defaults={"template"="default", "idMaster"="0", "idCategory"="0"}
     * )
     */
    public function createAction(Request $request, $idCard, $idMaster, $idCategory, $template)
    {
        return $this->_model($template)->categoryCreate($request, $idCard, $idCategory, $idMaster);
    }

    /**
     * @Route
     * (
     *      path="/update/{template}/{idCard}/{idMaster}/{idCategory}",
     *      name="fhm_api_card_category_update",
     *      requirements={"idCard"="[a-z0-9]*", "idCategory"="[a-z0-9]*"},
     *      defaults={"template"="default", "idMaster"="0"}
     * )
     */
    public function updateAction(Request $request, $idCard, $idMaster, $idCategory, $template)
    {
        return $this->_model($template)->categoryUpdate($request, $idCard, $idCategory, $idMaster);
    }

    /**
     * @Route
     * (
     *      path="/activate/{template}/{idCard}/{idMaster}/{idCategory}",
     *      name="fhm_api_card_category_activate",
     *      requirements={"idCard"="[a-z0-9]*", "idCategory"="[a-z0-9]*"},
     *      defaults={"template"="default", "idMaster"="0"}
     * )
     */
    public function activateAction(Request $request, $idCard, $idMaster, $idCategory, $template)
    {
        return $this->_model($template)->categoryActivate($idCard, $idCategory, $idMaster);
    }

    /**
     * @Route
     * (
     *      path="/deactivate/{template}/{idCard}/{idMaster}/{idCategory}",
     *      name="fhm_api_card_category_deactivate",
     *      requirements={"idCard"="[a-z0-9]*", "idCategory"="[a-z0-9]*"},
     *      defaults={"template"="default", "idMaster"="0"}
     * )
     */
    public function deactivateAction(Request $request, $idCard, $idMaster, $idCategory, $template)
    {
        return $this->_model($template)->categoryDeactivate($idCard, $idCategory, $idMaster);
    }

    /**
     * @Route
     * (
     *      path="/delete/{template}/{idCard}/{idMaster}/{idCategory}",
     *      name="fhm_api_card_category_delete",
     *      requirements={"idCard"="[a-z0-9]*", "idCategory"="[a-z0-9]*"},
     *      defaults={"template"="default", "idMaster"="0"}
     * )
     */
    public function deleteAction(Request $request, $idCard, $idMaster, $idCategory, $template)
    {
        return $this->_model($template)->categoryDelete($idCard, $idCategory, $idMaster);
    }

    /**
     * @Route
     * (
     *      path="/undelete/{template}/{idCard}/{idMaster}/{idCategory}",
     *      name="fhm_api_card_category_undelete",
     *      requirements={"idCard"="[a-z0-9]*", "idCategory"="[a-z0-9]*"},
     *      defaults={"template"="default", "idMaster"="0"}
     * )
     */
    public function undeleteAction(Request $request, $idCard, $idMaster, $idCategory, $template)
    {
        return $this->_model($template)->categoryUndelete($idCard, $idCategory, $idMaster);
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