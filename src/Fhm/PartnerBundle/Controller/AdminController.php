<?php
namespace Fhm\PartnerBundle\Controller;

use Fhm\FhmBundle\Controller\RefAdminController as FhmController;
use Fhm\PartnerBundle\Document\Partner;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * @Route("/admin/partner", service="fhm_partner_controller_admin")
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
        parent::__construct('Fhm', 'Partner', 'partner');
    }

    /**
     * @Route
     * (
     *      path="/",
     *      name="fhm_admin_partner"
     * )
     * @Template("::FhmPartner/Admin/index.html.twig")
     */
    public function indexAction()
    {
        return parent::indexAction();
    }

    /**
     * @Route
     * (
     *      path="/create",
     *      name="fhm_admin_partner_create"
     * )
     * @Template("::FhmPartner/Admin/create.html.twig")
     */
    public function createAction(Request $request)
    {
        return parent::createAction($request);
    }

    /**
     * @Route
     * (
     *      path="/duplicate/{id}",
     *      name="fhm_admin_partner_duplicate",
     *      requirements={"id"="[a-z0-9]*"}
     * )
     * @Template("::FhmPartner/Admin/create.html.twig")
     */
    public function duplicateAction(Request $request, $id)
    {
        return parent::duplicateAction($request, $id);
    }

    /**
     * @Route
     * (
     *      path="/update/{id}",
     *      name="fhm_admin_partner_update",
     *      requirements={"id"="[a-z0-9]*"}
     * )
     * @Template("::FhmPartner/Admin/update.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        return parent::updateAction($request, $id);
    }

    /**
     * @Route
     * (
     *      path="/detail/{id}",
     *      name="fhm_admin_partner_detail",
     *      requirements={"id"="[a-z0-9]*"}
     * )
     * @Template("::FhmPartner/Admin/detail.html.twig")
     */
    public function detailAction($id)
    {
        $document = $this->fhm_tools->dmRepository()->find($id);
        $instance = $this->fhm_tools->instanceData($document);

        return array_merge(
            array(
                'partnergroups1' => $this->fhm_tools->dmRepository('FhmPartnerBundle:PartnerGroup')->getListEnable($instance->grouping->current),
                'partnergroups2' => $this->getList($document->getPartnergroups())
            ),
            parent::detailAction($id)
        );
    }

    /**
     * @Route
     * (
     *      path="/preview/{id}",
     *      name="fhm_admin_partner_preview",
     *      requirements={"id"="[a-z0-9]*"}
     * )
     * @Template("::FhmPartner/Front/detail.html.twig")
     */
    public function previewAction($id)
    {
        return parent::detailAction($id);
    }

    /**
     * @Route
     * (
     *      path="/delete/{id}",
     *      name="fhm_admin_partner_delete",
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
     *      name="fhm_admin_partner_undelete",
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
     *      name="fhm_admin_partner_activate",
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
     *      name="fhm_admin_partner_deactivate",
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
     *      name="fhm_admin_partner_import"
     * )
     * @Template("::FhmPartner/Admin/import.html.twig")
     */
    public function importAction(Request $request)
    {
        return parent::importAction($request);
    }

    /**
     * @Route
     * (
     *      path="/export",
     *      name="fhm_admin_partner_export"
     * )
     * @Template("::FhmPartner/Admin/export.html.twig")
     */
    public function exportAction(Request $request)
    {
        return parent::exportAction($request);
    }

    /**
     * @Route
     * (
     *      path="/grouping",
     *      name="fhm_admin_partner_grouping"
     * )
     */
    public function groupingAction(Request $request)
    {
        return parent::groupingAction($request);
    }

    /**
     * @Route
     * (
     *      path="/partnergroup",
     *      name="fhm_admin_partner_partnergroup",
     *      requirements={"id"="[a-z0-9]*"}
     * )
     */
    public function partnergroupAction(Request $request)
    {
        $partnergroups = json_decode($request->get('list'));
        $document   = $this->fhm_tools->dmRepository()->find($request->get('id'));
        foreach($document->getPartnergroups() as $partnergroup)
        {
            $document->removePartnergroup($partnergroup);
        }
        foreach($partnergroups as $key => $data)
        {
            $partnergroup = $this->fhm_tools->dmRepository('FhmPartnerBundle:PartnerGroup')->find($data->id);
            $document->addPartnergroup($partnergroup);
        }
        $this->fhm_tools->dmPersist($document);

        return new Response();
    }
}