<?php
namespace Fhm\SliderBundle\Controller;

use Fhm\FhmBundle\Controller\RefAdminController as FhmController;
use Fhm\SliderBundle\Document\Slider;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * @Route("/admin/slider", service="fhm_slider_controller_admin")
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
        parent::__construct('Fhm', 'Slider', 'slider');
    }

    /**
     * @Route
     * (
     *      path="/",
     *      name="fhm_admin_slider"
     * )
     * @Template("::FhmSlider/Admin/index.html.twig")
     */
    public function indexAction()
    {
        return parent::indexAction();
    }

    /**
     * @Route
     * (
     *      path="/create",
     *      name="fhm_admin_slider_create"
     * )
     * @Template("::FhmSlider/Admin/create.html.twig")
     */
    public function createAction(Request $request)
    {
        return parent::createAction($request);
    }

    /**
     * @Route
     * (
     *      path="/duplicate/{id}",
     *      name="fhm_admin_slider_duplicate",
     *      requirements={"id"="[a-z0-9]*"}
     * )
     * @Template("::FhmSlider/Admin/create.html.twig")
     */
    public function duplicateAction(Request $request, $id)
    {
        return parent::duplicateAction($request, $id);
    }

    /**
     * @Route
     * (
     *      path="/update/{id}",
     *      name="fhm_admin_slider_update",
     *      requirements={"id"="[a-z0-9]*"}
     * )
     * @Template("::FhmSlider/Admin/update.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        return parent::updateAction($request, $id);
    }

    /**
     * @Route
     * (
     *      path="/detail/{id}",
     *      name="fhm_admin_slider_detail",
     *      requirements={"id"="[a-z0-9]*"}
     * )
     * @Template("::FhmSlider/Admin/detail.html.twig")
     */
    public function detailAction($id)
    {
        $document = $this->fhm_tools->dmRepository()->find($id);
        $instance = $this->fhm_tools->instanceData($document);

        return array_merge(
            array(
                'item1'  => $this->fhm_tools->dmRepository('FhmSliderBundle:SliderItem')->getAllEnable($instance->grouping->current),
                'item2'  => $this->getList($document->getItems()),
            ),
            parent::detailAction($id)
        );
    }

    /**
     * @Route
     * (
     *      path="/delete/{id}",
     *      name="fhm_admin_slider_delete",
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
     *      name="fhm_admin_slider_undelete",
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
     *      name="fhm_admin_slider_activate",
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
     *      name="fhm_admin_slider_deactivate",
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
     *      name="fhm_admin_slider_import"
     * )
     * @Template("::FhmSlider/Admin/import.html.twig")
     */
    public function importAction(Request $request)
    {
        return parent::importAction($request);
    }

    /**
     * @Route
     * (
     *      path="/export",
     *      name="fhm_admin_slider_export"
     * )
     * @Template("::FhmSlider/Admin/export.html.twig")
     */
    public function exportAction(Request $request)
    {
        return parent::exportAction($request);
    }

    /**
     * @Route
     * (
     *      path="/grouping",
     *      name="fhm_admin_slider_grouping"
     * )
     */
    public function groupingAction(Request $request)
    {
        return parent::groupingAction($request);
    }

    /**
     * @Route
     * (
     *      path="/list/item",
     *      name="fhm_admin_slider_list_item",
     *      requirements={"id"="[a-z0-9]*"}
     * )
     */
    public function listItemAction(Request $request)
    {
        $datas    = json_decode($request->get('list'));
        $document = $this->fhm_tools->dmRepository()->find($request->get('id'));
        foreach($document->getItems() as $item)
        {
            $document->removeItem($item);
        }
        foreach($datas as $key => $data)
        {
            $document->addItem($this->fhm_tools->dmRepository('FhmSliderBundle:SliderItem')->find($data->id));
        }
        $this->fhm_tools->dmPersist($document);

        return new Response();
    }
}