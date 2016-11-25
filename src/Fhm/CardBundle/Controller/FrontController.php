<?php
namespace Fhm\CardBundle\Controller;

use Fhm\FhmBundle\Controller\RefFrontController as FhmController;
use Fhm\CardBundle\Document\Card;
use Fhm\FhmBundle\Services\Tools;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * @Route("/card", service="fhm_card_controller_front")
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
        parent::__construct('Fhm', 'Card', 'card');
    }

    /**
     * @Route
     * (
     *      path="/",
     *      name="fhm_card"
     * )
     * @Template("::FhmCard/Front/index.html.twig")
     */
    public function indexAction()
    {
        return parent::indexAction();
    }

    /**
     * @Route
     * (
     *      path="/create",
     *      name="fhm_card_create"
     * )
     * @Template("::FhmCard/Front/create.html.twig")
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
     *      name="fhm_card_duplicate",
     *      requirements={"id"="[a-z0-9]*"}
     * )
     * @Template("::FhmCard/Front/create.html.twig")
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
     *      name="fhm_card_update",
     *      requirements={"id"="[a-z0-9]*"}
     * )
     * @Template("::FhmCard/Front/update.html.twig")
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
     *      name="fhm_card_detail",
     *      requirements={"id"=".+"}
     * )
     * @Template("::FhmCard/Front/detail.html.twig")
     */
    public function detailAction($id)
    {
        return parent::detailAction($id);
    }

    /**
     * @Route
     * (
     *      path="/delete/{id}",
     *      name="fhm_card_delete",
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
     *      name="fhm_card_lite",
     *      requirements={"id"=".+"}
     * )
     * @Template("::FhmCard/Front/detail.html.twig")
     */
    public function liteAction($id)
    {
        return $this->detailAction($id);
    }
}