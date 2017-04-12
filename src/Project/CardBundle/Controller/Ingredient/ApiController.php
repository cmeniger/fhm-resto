<?php
namespace Fhm\CardBundle\Controller\Ingredient;

use Fhm\FhmBundle\Controller\RefApiController as FhmController;
use Fhm\CardBundle\Document\CardIngredient;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * @Route("/api/cardingredient")
 */
class ApiController extends FhmController
{
    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct('Fhm', 'Card', 'card_ingredient', 'CardIngredient');
        $this->form->type->create = 'Fhm\\CardBundle\\Form\\Type\\Api\\Ingredient\\CreateType';
        $this->form->type->update = 'Fhm\\CardBundle\\Form\\Type\\Api\\Ingredient\\UpdateType';
        $this->translation        = array('FhmCardBundle', 'card.ingredient');
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
     *      path="/historic",
     *      name="fhm_api_card_ingredient_historic"
     * )
     * @Template("::FhmCard/Api/Ingredient/historic.html.twig")
     */
    public function historicAction(Request $request)
    {
        return parent::historicAction($request);
    }

    /**
     * @Route
     * (
     *      path="/historic/copy/{id}",
     *      name="fhm_api_card_ingredient_historic_copy",
     *      requirements={"id"="[a-z0-9]*"}
     * )
     */
    public function historicCopyAction(Request $request, $id)
    {
        return parent::historicCopyAction($request, $id);
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
        $card     = $this->dmRepository('FhmCardBundle:Card')->find($idCard);
        $product  = $this->dmRepository('FhmCardBundle:CardProduct')->find($idProduct);
        $instance = $this->instanceData();
        // ERROR - unknown
        if($card == "")
        {
            throw $this->createNotFoundException($this->get('translator')->trans('card.error.unknown', array(), $this->translation[0]));
        }
        if($product == "")
        {
            throw $this->createNotFoundException($this->get('translator')->trans('card.product.error.unknown', array(), $this->translation[0]));
        }
        $documents = $this->dmRepository()->getByProduct($card, $product, $instance->grouping->filtered);
        $inline    = array();
        foreach($documents as $ingredient)
        {
            $inline[] = $ingredient->getName();
        }

        return new Response(
            $this->renderView(
                "::FhmCard/Template/Ingredient/" . $template . ".html.twig",
                array(
                    "card"        => $card,
                    "product"     => $product,
                    "ingredients" => $documents,
                    "inline"      => implode(', ', $inline),
                    "instance"    => $instance,
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
        $card     = $this->dmRepository('FhmCardBundle:Card')->find($id);
        $instance = $this->instanceData($card);
        $this->_authorized($card);

        return new Response(
            $this->renderView(
                "::FhmCard/Template/Editor/ingredient.html.twig",
                array(
                    "card"     => $card,
                    "tree"     => $this->dmRepository()->getByCardAll($card, $instance->grouping->filtered),
                    "instance" => $instance,
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
        $card     = $this->dmRepository('FhmCardBundle:Card')->find($idCard);
        $instance = $this->instanceData();
        $this->_authorized($card);
        $list  = json_decode($request->get('list'));
        $order = 1;
        foreach($list as $obj)
        {
            $document = $this->dmRepository()->find($obj->id);
            $document->setOrder($order);
            $this->dmPersist($document);
            $order++;
        }

        return $this->_refresh($card, $instance);
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
        $card     = $this->dmRepository('FhmCardBundle:Card')->find($idCard);
        $instance = $this->instanceData();
        $this->_authorized($card);
        $document     = $this->document;
        $classType    = $this->form->type->create;
        $classHandler = $this->form->handler->create;
        $form         = $this->createForm(new $classType($instance, $document, $card), $document);
        $handler      = new $classHandler($form, $request);
        $process      = $handler->process();
        if($process)
        {
            $data = $request->get($form->getName());
            // Persist
            $document->setUserCreate($this->getUser());
            $document->setAlias($this->getAlias($document->getId(), $document->getName()));
            $document->setCard($card);
            $this->dmPersist($document);

            return $this->_refresh($card, $instance);
        }

        return array(
            'card'     => $card,
            'form'     => $form->createView(),
            'instance' => $instance
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
        $card     = $this->dmRepository('FhmCardBundle:Card')->find($idCard);
        $document = $this->dmRepository()->find($idIngredient);
        $instance = $this->instanceData();
        $this->_authorized($card);
        if($document == "")
        {
            throw $this->createNotFoundException($this->get('translator')->trans($this->translation[1] . '.error.unknown', array(), $this->translation[0]));
        }
        $classType    = $this->form->type->update;
        $classHandler = $this->form->handler->update;
        $form         = $this->createForm(new $classType($instance, $document, $card), $document);
        $handler      = new $classHandler($form, $request);
        $process      = $handler->process();
        if($process)
        {
            $data = $request->get($form->getName());
            // Persist
            $document->setUserUpdate($this->getUser());
            $document->setAlias($this->getAlias($document->getId(), $document->getName()));
            $this->dmPersist($document);

            return $this->_refresh($card, $instance);
        }

        return array(
            'card'     => $card,
            'document' => $document,
            'form'     => $form->createView(),
            'instance' => $instance
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
        $card     = $this->dmRepository('FhmCardBundle:Card')->find($idCard);
        $document = $this->dmRepository()->find($idIngredient);
        $instance = $this->instanceData();
        $this->_authorized($card);
        if($document == "")
        {
            throw $this->createNotFoundException($this->get('translator')->trans($this->translation[1] . '.error.unknown', array(), $this->translation[0]));
        }
        $document->setUserUpdate($this->getUser());
        $document->setActive(true);
        $this->dmPersist($document);

        return $this->_refresh($card, $instance);
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
        $card     = $this->dmRepository('FhmCardBundle:Card')->find($idCard);
        $document = $this->dmRepository()->find($idIngredient);
        $instance = $this->instanceData();
        $this->_authorized($card);
        if($document == "")
        {
            throw $this->createNotFoundException($this->get('translator')->trans($this->translation[1] . '.error.unknown', array(), $this->translation[0]));
        }
        $document->setUserUpdate($this->getUser());
        $document->setActive(false);
        $this->dmPersist($document);

        return $this->_refresh($card, $instance);
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
        $card     = $this->dmRepository('FhmCardBundle:Card')->find($idCard);
        $document = $this->dmRepository()->find($idIngredient);
        $instance = $this->instanceData();
        $this->_authorized($card);
        if($document == "")
        {
            throw $this->createNotFoundException($this->get('translator')->trans($this->translation[1] . '.error.unknown', array(), $this->translation[0]));
        }
        // Delete
        if($document->getDelete())
        {
            $this->dmRemove($document);
        }
        else
        {
            $document->setUserUpdate($this->getUser());
            $document->setDelete(true);
            $this->dmPersist($document);
        }

        return $this->_refresh($card, $instance);
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
        $card     = $this->dmRepository('FhmCardBundle:Card')->find($idCard);
        $document = $this->dmRepository()->find($idIngredient);
        $instance = $this->instanceData();
        $this->_authorized($card);
        if($document == "")
        {
            throw $this->createNotFoundException($this->get('translator')->trans($this->translation[1] . '.error.unknown', array(), $this->translation[0]));
        }
        // Undelete
        $document->setUserUpdate($this->getUser());
        $document->setDelete(false);
        $this->dmPersist($document);

        return $this->_refresh($card, $instance);
    }

    /**
     * @param $card
     * @param $instance
     *
     * @return JsonResponse
     * @throws \Exception
     */
    private function _refresh($card, $instance)
    {
        $response = new JsonResponse();
        $response->setData(array(
            'status' => 200,
            'html'   => $this->renderView(
                "::FhmCard/Template/Editor/ingredient.html.twig",
                array(
                    "card"     => $card,
                    "tree"     => $this->dmRepository()->getByCardAll($card, $instance->grouping->filtered),
                    "instance" => $instance,
                ))
        ));

        return $response;
    }

    /**
     * User is authorized
     *
     * @param $card
     *
     * @return $this
     */
    protected function _authorized($card)
    {
        if($card == "")
        {
            throw $this->createNotFoundException($this->get('translator')->trans('card.error.unknown', array(), $this->translation[0]));
        }
        if($card->getParent() && method_exists($card->getParent(), 'hasModerator'))
        {
            if(!$this->getUser()->isSuperAdmin() && !$this->getUser()->hasRole('ROLE_ADMIN') && !$card->getParent()->hasModerator($this->getUser()))
            {
                throw new HttpException(403, $this->get('translator')->trans($this->translation[1] . '.error.forbidden', array(), $this->translation[0]));
            }
        }

        return $this;
    }
}