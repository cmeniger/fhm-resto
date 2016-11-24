<?php
namespace Fhm\PartnerBundle\Controller\Group;

use Fhm\FhmBundle\Controller\RefAdminController as FhmController;
use Fhm\PartnerBundle\Document\PartnerGroup;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * @Route("/admin/partnergroup", service="fhm_partner_controller_group_admin")
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
        parent::__construct('Fhm', 'Partner', 'partner_group', 'PartnerGroup');
        $this->form->type->create = 'Fhm\\PartnerBundle\\Form\\Type\\Admin\\Group\\CreateType';
        $this->form->type->update = 'Fhm\\PartnerBundle\\Form\\Type\\Admin\\Group\\UpdateType';
        $this->translation        = array('FhmPartnerBundle', 'partner.group');
    }

    /**
     * @Route
     * (
     *      path="/",
     *      name="fhm_admin_partner_group"
     * )
     * @Template("::FhmPartner/Admin/Group/index.html.twig")
     */
    public function indexAction()
    {
        return parent::indexAction();
    }

    /**
     * @Route
     * (
     *      path="/create",
     *      name="fhm_admin_partner_group_create"
     * )
     * @Template("::FhmPartner/Admin/Group/create.html.twig")
     */
    public function createAction(Request $request)
    {
        return parent::createAction($request);
    }

    /**
     * @Route
     * (
     *      path="/duplicate/{id}",
     *      name="fhm_admin_partner_group_duplicate",
     *      requirements={"id"="[a-z0-9]*"}
     * )
     * @Template("::FhmPartner/Admin/Group/create.html.twig")
     */
    public function duplicateAction(Request $request, $id)
    {
        return parent::duplicateAction($request, $id);
    }

    /**
     * @Route
     * (
     *      path="/update/{id}",
     *      name="fhm_admin_partner_group_update",
     *      requirements={"id"="[a-z0-9]*"}
     * )
     * @Template("::FhmPartner/Admin/Group/update.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        return parent::updateAction($request, $id);
    }

    /**
     * @Route
     * (
     *      path="/detail/{id}",
     *      name="fhm_admin_partner_group_detail",
     *      requirements={"id"="[a-z0-9]*"}
     * )
     * @Template("::FhmPartner/Admin/Group/detail.html.twig")
     */
    public function detailAction($id)
    {
        $document = $this->fhm_tools->dmRepository()->find($id);
        $instance = $this->fhm_tools->instanceData($document);

        return array_merge(
            array(
                'partner1' => $this->fhm_tools->dmRepository('FhmPartnerBundle:Partner')->getAllEnable($instance->grouping->current),
                'partner2' => $this->getList($document->getPartner())
            ),
            parent::detailAction($id)
        );
    }

    /**
     * @Route
     * (
     *      path="/preview/{id}",
     *      name="fhm_admin_partner_group_preview",
     *      requirements={"id"="[a-z0-9]*"}
     * )
     * @Template("::FhmPartner/Front/Group/detail.html.twig")
     */
    public function previewAction($id)
    {
        $response  = parent::detailAction($id);
        $document  = $response['document'];
        $instance  = $response['instance'];
        $classType = $this->form->type->search;
        $form      = $this->createForm(new $classType($instance), null);
        $form->setData($this->get('request')->get($form->getName()));
        $dataSearch        = $form->getData();
        $dataPagination    = $this->get('request')->get('FhmPagination');
        $this->translation = array('FhmPartnerBundle', 'partner');
        // Ajax pagination request
        if(isset($dataPagination['pagination']))
        {
            $documents = $this->fhm_tools->dmRepository("FhmPartnerBundle:Partner")->getPartnerByGroupIndex($document, $dataSearch['search'], $dataPagination['pagination'], $this->fhm_tools->getParameters(array('pagination', 'front', 'page'), 'fhm_fhm'));

            return array_merge(
                $response,
                array(
                    'documents'  => $documents,
                    'pagination' => $this->fhm_tools->getPagination($dataPagination['pagination'], count($documents), $this->fhm_tools->dmRepository("FhmPartnerBundle:Partner")->getPartnerByGroupCount($document, $dataSearch['search']), 'pagination', $this->fhm_tools->formRename($form->getName(), $dataSearch), $this->fhm_tools->getUrl('fhm_partner_group_lite', array('id' => $id))),
                ));
        }
        // Router request
        else
        {
            $documents = $this->fhm_tools->dmRepository("FhmPartnerBundle:Partner")->getPartnerByGroupIndex($document, $dataSearch['search'], 1, $this->fhm_tools->getParameters(array('pagination', 'front', 'page'), 'fhm_fhm'));

            return array_merge(
                $response,
                array(
                    'documents'  => $documents,
                    'pagination' => $this->fhm_tools->getPagination(1, count($documents), $this->fhm_tools->dmRepository("FhmPartnerBundle:Partner")->getPartnerByGroupCount($document, $dataSearch['search']), 'pagination', $this->fhm_tools->formRename($form->getName(), $dataSearch), $this->fhm_tools->getUrl('fhm_partner_group_lite', array('id' => $id))),
                    'form'       => $form->createView(),
                ));
        }
    }

    /**
     * @Route
     * (
     *      path="/delete/{id}",
     *      name="fhm_admin_partner_group_delete",
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
     *      name="fhm_admin_partner_group_undelete",
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
     *      name="fhm_admin_partner_group_activate",
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
     *      name="fhm_admin_partner_group_deactivate",
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
     *      name="fhm_admin_partner_group_import"
     * )
     * @Template("::FhmPartner/Admin/Group/import.html.twig")
     */
    public function importAction(Request $request)
    {
        return parent::importAction($request);
    }

    /**
     * @Route
     * (
     *      path="/export",
     *      name="fhm_admin_partner_group_export"
     * )
     * @Template("::FhmPartner/Admin/Group/export.html.twig")
     */
    public function exportAction(Request $request)
    {
        return parent::exportAction($request);
    }

    /**
     * @Route
     * (
     *      path="/grouping",
     *      name="fhm_admin_partner_group_grouping"
     * )
     */
    public function groupingAction(Request $request)
    {
        return parent::groupingAction($request);
    }

    /**
     * @Route
     * (
     *      path="/partner",
     *      name="fhm_admin_partner_group_partner",
     *      requirements={"id"="[a-z0-9]*"}
     * )
     */
    public function partnerAction(Request $request)
    {
        $partner  = json_decode($request->get('list'));
        $document = $this->fhm_tools->dmRepository()->find($request->get('id'));
        foreach($document->getPartner() as $new)
        {
            $document->removePartner($new);
        }
        foreach($partner as $key => $data)
        {
            $new = $this->fhm_tools->dmRepository('FhmPartnerBundle:Partner')->find($data->id);
            $document->addPartner($new);
        }
        $this->fhm_tools->dmPersist($document);

        return new Response();
    }
}