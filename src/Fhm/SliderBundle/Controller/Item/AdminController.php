<?php
namespace Fhm\SliderBundle\Controller\Item;

use Fhm\FhmBundle\Controller\RefAdminController as FhmController;
use Fhm\SliderBundle\Document\SliderItem;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * @Route("/admin/slideritem", service="fhm_slider_controller_item_admin")
 */
class AdminController extends FhmController
{
    /**
     * AdminController constructor.
     *
     * @param \Fhm\FhmBundle\Services\Tools $tools
     */
    public function __construct(\Fhm\FhmBundle\Services\Tools $tools)
    {
        $this->setFhmTools($tools);
        parent::__construct('Fhm', 'Slider', 'slider_item', 'SliderItem');
        $this->form->type->create = 'Fhm\\SliderBundle\\Form\\Type\\Admin\\Item\\CreateType';
        $this->form->type->update = 'Fhm\\SliderBundle\\Form\\Type\\Admin\\Item\\UpdateType';
        $this->translation        = array('FhmSliderBundle', 'slider.item');
    }

    /**
     * @Route
     * (
     *      path="/",
     *      name="fhm_admin_slider_item"
     * )
     * @Template("::FhmSlider/Admin/Item/index.html.twig")
     */
    public function indexAction()
    {
        return parent::indexAction();
    }

    /**
     * @Route
     * (
     *      path="/create",
     *      name="fhm_admin_slider_item_create"
     * )
     * @Template("::FhmSlider/Admin/Item/create.html.twig")
     */
    public function createAction(Request $request)
    {
        return parent::createAction($request);
    }

    /**
     * @Route
     * (
     *      path="/duplicate/{id}",
     *      name="fhm_admin_slider_item_duplicate",
     *      requirements={"id"="[a-z0-9]*"}
     * )
     * @Template("::FhmSlider/Admin/Item/create.html.twig")
     */
    public function duplicateAction(Request $request, $id)
    {
        return parent::duplicateAction($request, $id);
    }

    /**
     * @Route
     * (
     *      path="/update/{id}",
     *      name="fhm_admin_slider_item_update",
     *      requirements={"id"="[a-z0-9]*"}
     * )
     * @Template("::FhmSlider/Admin/Item/update.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        return parent::updateAction($request, $id);
    }

    /**
     * @Route
     * (
     *      path="/detail/{id}",
     *      name="fhm_admin_slider_item_detail",
     *      requirements={"id"="[a-z0-9]*"}
     * )
     * @Template("::FhmSlider/Admin/Item/detail.html.twig")
     */
    public function detailAction($id)
    {
        $document = $this->fhm_tools->dmRepository()->find($id);
        $instance = $this->fhm_tools->instanceData($document);

        return array_merge(
            array(
                'slider1' => $this->fhm_tools->dmRepository('FhmSliderBundle:Slider')->getAllEnable($instance->grouping->current),
                'slider2' => $this->getList($document->getSliders())
            ),
            parent::detailAction($id)
        );
    }

    /**
     * @Route
     * (
     *      path="/delete/{id}",
     *      name="fhm_admin_slider_item_delete",
     *      requirements={"id"="[a-z0-9]*"}
     * )
     */
    public function deleteAction($id)
    {
        return parent::deleteAction($id);
    }

    /**
     * @Route
     * (
     *      path="/undelete/{id}",
     *      name="fhm_admin_slider_item_undelete",
     *      requirements={"id"="[a-z0-9]*"}
     * )
     */
    public function undeleteAction($id)
    {
        return parent::undeleteAction($id);
    }

    /**
     * @Route
     * (
     *      path="/activate/{id}",
     *      name="fhm_admin_slider_item_activate",
     *      requirements={"id"="[a-z0-9]*"}
     * )
     */
    public function activateAction($id)
    {
        return parent::activateAction($id);
    }

    /**
     * @Route
     * (
     *      path="/deactivate/{id}",
     *      name="fhm_admin_slider_item_deactivate",
     *      requirements={"id"="[a-z0-9]*"}
     * )
     */
    public function deactivateAction($id)
    {
        return parent::deactivateAction($id);
    }

    /**
     * @Route
     * (
     *      path="/import",
     *      name="fhm_admin_slider_item_import"
     * )
     * @Template("::FhmSlider/Admin/Item/import.html.twig")
     */
    public function importAction(Request $request)
    {
        return parent::importAction($request);
    }

    /**
     * @Route
     * (
     *      path="/export",
     *      name="fhm_admin_slider_item_export"
     * )
     * @Template("::FhmSlider/Admin/Item/export.html.twig")
     */
    public function exportAction(Request $request)
    {
        return parent::exportAction($request);
    }

    /**
     * @Route
     * (
     *      path="/grouping",
     *      name="fhm_admin_slider_item_grouping"
     * )
     */
    public function groupingAction(Request $request)
    {
        return parent::groupingAction($request);
    }

    /**
     * @Route
     * (
     *      path="/list/slider",
     *      name="fhm_admin_slider_item_slider",
     *      requirements={"id"="[a-z0-9]*"}
     * )
     */
    public function listSliderAction(Request $request)
    {
        $datas    = json_decode($request->get('list'));
        $document = $this->fhm_tools->dmRepository()->find($request->get('id'));
        foreach($document->getSliders() as $slider)
        {
            $document->removeSlider($slider);
        }
        foreach($datas as $key => $data)
        {
            $document->addSlider($this->fhm_tools->dmRepository('FhmSliderBundle:Slider')->find($data->id));
        }
        $this->fhm_tools->dmPersist($document);

        return new Response();
    }
}