<?php
namespace Fhm\SliderBundle\Controller\Item;

use Fhm\FhmBundle\Controller\RefFrontController as FhmController;
use Fhm\SliderBundle\Document\Slider;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * @Route("/slideritem", service="fhm_slider_controller_item_front")
 */
class FrontController extends FhmController
{
    /**
     * FrontController constructor.
     *
     * @param \Fhm\FhmBundle\Services\Tools $tools
     */
    public function __construct(\Fhm\FhmBundle\Services\Tools $tools)
    {
        $this->setFhmTools($tools);
        parent::__construct('Fhm', 'Slider', 'slider_item', 'SliderItem');
        $this->form->type->create = 'Fhm\\SliderBundle\\Form\\Type\\Front\\Item\\CreateType';
        $this->form->type->update = 'Fhm\\SliderBundle\\Form\\Type\\Front\\Item\\UpdateType';
        $this->translation        = array('FhmSliderBundle', 'slider.item');
    }

    /**
     * @Route
     * (
     *      path="/",
     *      name="fhm_Slider_item"
     * )
     * @Template("::FhmSlider/Front/Item/index.html.twig")
     */
    public function indexAction()
    {
        // For activate this route, delete next line
        throw $this->createNotFoundException($this->fhm_tools->trans('fhm.error.route', array(), 'FhmFhmBundle'));

        return parent::indexAction();
    }

    /**
     * @Route
     * (
     *      path="/create",
     *      name="fhm_slider_item_create"
     * )
     * @Template("::FhmSlider/Front/Item/create.html.twig")
     */
    public function createAction(Request $request)
    {
        // For activate this route, delete next line
        throw $this->createNotFoundException($this->fhm_tools->trans('fhm.error.route', array(), 'FhmFhmBundle'));

        return parent::createAction($request);
    }

    /**
     * @Route
     * (
     *      path="/duplicate/{id}",
     *      name="fhm_slider_item_duplicate",
     *      requirements={"id"="[a-z0-9]*"}
     * )
     * @Template("::FhmSlider/Front/Item/create.html.twig")
     */
    public function duplicateAction(Request $request, $id)
    {
        // For activate this route, delete next line
        throw $this->createNotFoundException($this->fhm_tools->trans('fhm.error.route', array(), 'FhmFhmBundle'));

        return parent::duplicateAction($request, $id);
    }

    /**
     * @Route
     * (
     *      path="/update/{id}",
     *      name="fhm_slider_item_update",
     *      requirements={"id"="[a-z0-9]*"}
     * )
     * @Template("::FhmSlider/Front/Item/update.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        // For activate this route, delete next line
        throw $this->createNotFoundException($this->fhm_tools->trans('fhm.error.route', array(), 'FhmFhmBundle'));

        return parent::updateAction($request, $id);
    }

    /**
     * @Route
     * (
     *      path="/detail/{id}",
     *      name="fhm_slider_item_detail",
     *      requirements={"id"=".+"}
     * )
     * @Template("::FhmSlider/Front/Item/detail.html.twig")
     */
    public function detailAction($id)
    {
        return parent::detailAction($id);
    }

    /**
     * @Route
     * (
     *      path="/delete/{id}",
     *      name="fhm_slider_item_delete",
     *      requirements={"id"="[a-z0-9]*"}
     * )
     */
    public function deleteAction($id)
    {
        // For activate this route, delete next line
        throw $this->createNotFoundException($this->fhm_tools->trans('fhm.error.route', array(), 'FhmFhmBundle'));

        return parent::deleteAction($id);
    }

    /**
     * @Route
     * (
     *      path="/{id}",
     *      name="fhm_slider_item_lite",
     *      requirements={"id"=".+"}
     * )
     * @Template("::FhmSlider/Front/Item/detail.html.twig")
     */
    public function liteAction($id)
    {
        return $this->detailAction($id);
    }
}