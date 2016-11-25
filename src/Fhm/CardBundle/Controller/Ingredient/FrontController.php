<?php
namespace Fhm\CardBundle\Controller\Ingredient;

use Fhm\CardBundle\Form\Type\Front\Ingredient\CreateType;
use Fhm\CardBundle\Form\Type\Front\Ingredient\UpdateType;
use Fhm\FhmBundle\Controller\RefFrontController as FhmController;
use Fhm\CardBundle\Document\CardIngredient;
use Fhm\FhmBundle\Services\Tools;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * @Route("/cardingredient" , service="fhm_card_controller_ingredient_front")
 */
class FrontController extends FhmController
{
    /**
     * FrontController constructor.
     * @param Tools $tools
     */
    public function __construct(Tools $tools)
    {
        $this->setFhmTools($tools);
        parent::__construct('Fhm', 'Card', 'card_ingredient', 'CardIngredient');
        $this->form->type->create = CreateType::class;
        $this->form->type->update = UpdateType::class;
        $this->translation = array('FhmCardBundle', 'card.ingredient');
    }

    /**
     * @Route
     * (
     *      path="/",
     *      name="fhm_card_ingredient"
     * )
     * @Template("::FhmCard/Front/Ingredient/index.html.twig")
     */
    public function indexAction()
    {
        return parent::indexAction();
    }

    /**
     * @Route
     * (
     *      path="/create",
     *      name="fhm_card_ingredient_create"
     * )
     * @Template("::FhmCard/Front/Ingredient/create.html.twig")
     */
    public function createAction(Request $request)
    {
        // For activate this route, delete next line
        throw $this->createNotFoundException(
            $this->get('translator')->trans('fhm.error.route', array(), 'FhmFhmBundle')
        );
    }

    /**
     * @Route
     * (
     *      path="/duplicate/{id}",
     *      name="fhm_card_ingredient_duplicate",
     *      requirements={"id"="[a-z0-9]*"}
     * )
     * @Template("::FhmCard/Front/Ingredient/create.html.twig")
     */
    public function duplicateAction(Request $request, $id)
    {
        // For activate this route, delete next line
        throw $this->createNotFoundException(
            $this->get('translator')->trans('fhm.error.route', array(), 'FhmFhmBundle')
        );
    }

    /**
     * @Route
     * (
     *      path="/update/{id}",
     *      name="fhm_card_ingredient_update",
     *      requirements={"id"="[a-z0-9]*"}
     * )
     * @Template("::FhmCard/Front/Ingredient/update.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        // For activate this route, delete next line
        throw $this->createNotFoundException(
            $this->get('translator')->trans('fhm.error.route', array(), 'FhmFhmBundle')
        );
    }

    /**
     * @Route
     * (
     *      path="/detail/{id}",
     *      name="fhm_card_ingredient_detail",
     *      requirements={"id"=".+"}
     * )
     * @Template("::FhmCard/Front/Ingredient/detail.html.twig")
     */
    public function detailAction($id)
    {
        return parent::detailAction($id);
    }

    /**
     * @Route
     * (
     *      path="/delete/{id}",
     *      name="fhm_card_ingredient_delete",
     *      requirements={"id"="[a-z0-9]*"}
     * )
     */
    public function deleteAction($id)
    {
        // For activate this route, delete next line
        throw $this->createNotFoundException(
            $this->get('translator')->trans('fhm.error.route', array(), 'FhmFhmBundle')
        );
    }

    /**
     * @Route
     * (
     *      path="/{id}",
     *      name="fhm_card_ingredient_lite",
     *      requirements={"id"=".+"}
     * )
     * @Template("::FhmCard/Front/Ingredient/detail.html.twig")
     */
    public function liteAction($id)
    {
        return $this->detailAction($id);
    }
}