<?php
namespace Fhm\CardBundle\Controller\Ingredient;

use Fhm\CardBundle\Form\Type\Api\Ingredient\CreateType;
use Fhm\CardBundle\Form\Type\Api\Ingredient\UpdateType;
use Fhm\FhmBundle\Controller\RefApiController as FhmController;
use Fhm\CardBundle\Document\CardIngredient;
use Fhm\FhmBundle\Form\Handler\Admin\CreateHandler;
use Fhm\FhmBundle\Form\Handler\Admin\UpdateHandler;
use Fhm\FhmBundle\Services\Tools;
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
        self::$class = CardIngredient::class;
        self::$route = "card_ingredient";
        self::$form = new \stdClass();
        self::$form->createType    = CreateType::class;
        self::$form->createHandler = CreateHandler::class;
        self::$form->updateType    = UpdateType::class;
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
     *      path="/embed/{template}/{idCard}/{idProduct}",
     *      name="fhm_api_card_ingredient_embed",
     *      requirements={"idCard"="[a-z0-9]*", "idProduct"="[a-z0-9]*"},
     *      defaults={"template"="inline"}
     * )
     */
    public function embedAction(Request $request, $idCard, $idProduct, $template)
    {
        $card = $this->get('fhm_tools')->dmRepository('FhmCardBundle:Card')->find($idCard);
        $product = $this->get('fhm_tools')->dmRepository('FhmCardBundle:CardProduct')->find($idProduct);
        // ERROR - unknown
        if ($card == "") {
            throw $this->createNotFoundException(
                $this->get('translator')->trans('card.error.unknown', array(), self::$domain)
            );
        }
        if ($product == "") {
            throw $this->createNotFoundException(
                $this->get('translator')->trans('card.product.error.unknown', array(), self::$domain)
            );
        }
        $documents = $this->get('fhm_tools')->dmRepository(self::$repository)->getByProduct($card, $product);
        $inline = array();
        foreach ($documents as $ingredient) {
            $inline[] = $ingredient->getName();
        }

        return new Response(
            $this->renderView(
                "::FhmCard/Template/Ingredient/".$template.".html.twig",
                array(
                    "card" => $card,
                    "product" => $product,
                    "ingredients" => $documents,
                    "inline" => implode(', ', $inline),
                )
            )
        );
    }

    /**
     * @Route
     * (
     *      path="/editor/{id}",
     *      name="fhm_api_card_ingredient_editor",
     *      requirements={"id"="[a-z0-9]*"}
     * )
     */
    public function editorAction(Request $request, $id)
    {
        $card = $this->get('fhm_tools')->dmRepository('FhmCardBundle:Card')->find($id);
        $this->authorized($card);

        return new Response(
            $this->renderView(
                "::FhmCard/Template/Editor/ingredient.html.twig",
                array(
                    "card" => $card,
                    "tree" => $this->get('fhm_tools')->dmRepository(self::$repository)->getByCardAll($card),
                )
            )
        );
    }

    /**
     * @Route
     * (
     *      path="/sort/{idCard}",
     *      name="fhm_api_card_ingredient_sort",
     *      requirements={"idCard"="[a-z0-9]*"},
     * )
     */
    public function sortAction(Request $request, $idCard)
    {
        $card = $this->get('fhm_tools')->dmRepository('FhmCardBundle:Card')->find($idCard);
        $this->authorized($card);
        $list = json_decode($request->get('list'));
        $order = 1;
        foreach ($list as $obj) {
            $document = $this->get('fhm_tools')->dmRepository(self::$repository)->find($obj->id);
            $document->setOrder($order);
            $this->get('fhm_tools')->dmPersist($document);
            $order++;
        }

        return $this->__refresh($card);
    }

    /**
     * @Route
     * (
     *      path="/create/{idCard}",
     *      name="fhm_api_card_ingredient_create",
     *      requirements={"idCard"="[a-z0-9]*"}
     * )
     * @Template("::FhmCard/Template/Ingredient/create.html.twig")
     */
    public function createAction(Request $request, $idCard)
    {
        $card = $this->get('fhm_tools')->dmRepository('FhmCardBundle:Card')->find($idCard);
        $this->authorized($card);
        $document = new self::$class;
        $form = $this->createForm(self::$form->createType, $document);
        $handler = new self::$form->createHandler($form, $request);
        $process = $handler->process();
        if ($process) {
            // Persist
            $document->setUserCreate($this->getUser());
            $document->setAlias($this->getAlias($document->getId(), $document->getName()));
            $document->setCard($card);
            $this->get('fhm_tools')->dmPersist($document);

            return $this->__refresh($card);
        }

        return array(
            'card' => $card,
            'form' => $form->createView(),
        );
    }

    /**
     * @Route
     * (
     *      path="/update/{idCard}/{idIngredient}",
     *      name="fhm_api_card_ingredient_update",
     *      requirements={"idCard"="[a-z0-9]*", "idIngredient"="[a-z0-9]*"}
     * )
     * @Template("::FhmCard/Template/Ingredient/update.html.twig")
     */
    public function updateAction(Request $request, $idCard, $idIngredient)
    {
        $card = $this->get('fhm_tools')->dmRepository('FhmCardBundle:Card')->find($idCard);
        $document = $this->get('fhm_tools')->dmRepository(self::$repository)->find($idIngredient);
        $this->authorized($card);
        if ($document == "") {
            throw $this->createNotFoundException(
                $this->get('translator')->trans(self::$translation.'.error.unknown', array(), self::$domain)
            );
        }
        $form = $this->createForm(self::$form->updateType, $document);
        $handler = new self::$form->updateHandler($form, $request);
        $process = $handler->process();
        if ($process) {
            // Persist
            $document->setUserUpdate($this->getUser());
            $document->setAlias($this->getAlias($document->getId(), $document->getName()));
            $this->get('fhm_tools')->dmPersist($document);

            return $this->__refresh($card);
        }

        return array(
            'card' => $card,
            'document' => $document,
            'form' => $form->createView(),
        );
    }

    /**
     * @Route
     * (
     *      path="/activate/{idCard}/{idIngredient}",
     *      name="fhm_api_card_ingredient_activate",
     *      requirements={"idCard"="[a-z0-9]*", "idIngredient"="[a-z0-9]*"}
     * )
     */
    public function activateAction(Request $request, $idCard, $idIngredient)
    {
        $card = $this->get('fhm_tools')->dmRepository('FhmCardBundle:Card')->find($idCard);
        $document = $this->get('fhm_tools')->dmRepository(self::$repository)->find($idIngredient);
        $this->authorized($card);
        if ($document == "") {
            throw $this->createNotFoundException(
                $this->get('translator')->trans(self::$translation.'.error.unknown', array(), self::$domain)
            );
        }
        $document->setUserUpdate($this->getUser());
        $document->setActive(true);
        $this->get('fhm_tools')->dmPersist($document);

        return $this->__refresh($card);
    }

    /**
     * @Route
     * (
     *      path="/deactivate/{idCard}/{idIngredient}",
     *      name="fhm_api_card_ingredient_deactivate",
     *      requirements={"idCard"="[a-z0-9]*", "idIngredient"="[a-z0-9]*"}
     * )
     */
    public function deactivateAction(Request $request, $idCard, $idIngredient)
    {
        $card = $this->get('fhm_tools')->dmRepository('FhmCardBundle:Card')->find($idCard);
        $document = $this->get('fhm_tools')->dmRepository(self::$repository)->find($idIngredient);
        $this->authorized($card);
        if ($document == "") {
            throw $this->createNotFoundException(
                $this->get('translator')->trans(self::$translation.'.error.unknown', array(), self::$domain)
            );
        }
        $document->setUserUpdate($this->getUser());
        $document->setActive(false);
        $this->get('fhm_tools')->dmPersist($document);

        return $this->__refresh($card);
    }

    /**
     * @Route
     * (
     *      path="/delete/{idCard}/{idIngredient}",
     *      name="fhm_api_card_ingredient_delete",
     *      requirements={"idCard"="[a-z0-9]*", "idIngredient"="[a-z0-9]*"}
     * )
     */
    public function deleteAction(Request $request, $idCard, $idIngredient)
    {
        $card = $this->get('fhm_tools')->dmRepository('FhmCardBundle:Card')->find($idCard);
        $document = $this->get('fhm_tools')->dmRepository(self::$repository)->find($idIngredient);
        $this->authorized($card);
        if ($document == "") {
            throw $this->createNotFoundException(
                $this->get('translator')->trans(self::$translation.'.error.unknown', array(), self::$domain)
            );
        }
        if ($document->getDelete()) {
            $this->get('fhm_tools')->dmRemove($document);
        } else {
            $document->setUserUpdate($this->getUser());
            $document->setDelete(true);
            $this->get('fhm_tools')->dmPersist($document);
        }

        return $this->__refresh($card);
    }

    /**
     * @Route
     * (
     *      path="/undelete/{idCard}/{idIngredient}",
     *      name="fhm_api_card_ingredient_undelete",
     *      requirements={"idCard"="[a-z0-9]*", "idIngredient"="[a-z0-9]*"}
     * )
     */
    public function undeleteAction(Request $request, $idCard, $idIngredient)
    {
        $card = $this->get('fhm_tools')->dmRepository('FhmCardBundle:Card')->find($idCard);
        $document = $this->get('fhm_tools')->dmRepository(self::$repository)->find($idIngredient);
        $this->authorized($card);
        if ($document == "") {
            throw $this->createNotFoundException(
                $this->get('translator')->trans(self::$translation.'.error.unknown', array(), self::$domain)
            );
        }
        // Undelete
        $document->setUserUpdate($this->getUser());
        $document->setDelete(false);
        $this->get('fhm_tools')->dmPersist($document);

        return $this->__refresh($card);
    }

    /**
     * @param $card
     *
     * @return JsonResponse
     * @throws \Exception
     */
    private function __refresh($card)
    {
        $response = new JsonResponse();
        $response->setData(
            array(
                'status' => 200,
                'html' => $this->renderView(
                    "::FhmCard/Template/Editor/ingredient.html.twig",
                    array(
                        "card" => $card,
                        "tree" => $this->get('fhm_tools')->dmRepository(self::$repository)->getByCardAll($card),
                    )
                ),
            )
        );

        return $response;
    }

    /**
     * User is authorized
     *
     * @param $card
     *
     * @return $this
     */
    protected function authorized($card)
    {
        if ($card == "") {
            throw $this->createNotFoundException(
                $this->get('translator')->trans('card.error.unknown', array(), self::$domain)
            );
        }
        if ($card->getParent() && method_exists($card->getParent(), 'hasModerator')) {
            if (!$this->getUser()->isSuperAdmin() && !$this->getUser()->hasRole('ROLE_ADMIN') && !$card->getParent(
                )->hasModerator($this->getUser())
            ) {
                throw new HttpException(
                    403, $this->get('translator')->trans(
                    self::$translation.'.error.forbidden',
                    array(),
                    self::$domain
                )
                );
            }
        }

        return $this;
    }
}