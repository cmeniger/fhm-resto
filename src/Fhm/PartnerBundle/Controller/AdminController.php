<?php
namespace Fhm\PartnerBundle\Controller;

use Fhm\FhmBundle\Controller\RefAdminController as FhmController;
use Fhm\FhmBundle\Form\Handler\Admin\CreateHandler;
use Fhm\FhmBundle\Form\Handler\Admin\UpdateHandler;
use Fhm\PartnerBundle\Document\Partner;
use Fhm\PartnerBundle\Form\Type\Admin\Group\CreateType;
use Fhm\PartnerBundle\Form\Type\Admin\Group\UpdateType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * @Route("/admin/partner")
 * ------------------------------------
 * Class AdminController
 * @package Fhm\PartnerBundle\Controller
 */
class AdminController extends FhmController
{
    /**
     * AdminController constructor.
     */
    public function __construct()
    {
        self::$repository = "FhmPartnerBundle:Partner";
        self::$source = "fhm";
        self::$domain = "FhmPartnerBundle";
        self::$translation = "partner";
        self::$class = Partner::class;
        self::$route = "partner";
        self::$form = new \stdClass();
        self::$form->createType = CreateType::class;
        self::$form->createHandler = CreateHandler::class;
        self::$form->updateType = UpdateType::class;
        self::$form->updateHandler = UpdateHandler::class;
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
        $document = $this->get('fhm_tools')->dmRepository(self::$repository)->find($id);

        return array_merge(
            array(
                'partnergroups1' => $this->get('fhm_tools')->dmRepository(
                    'FhmPartnerBundle:PartnerGroup'
                )->getListEnable(),
                'partnergroups2' => $this->getList($document->getPartnergroups()),
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
     *      path="/partnergroup",
     *      name="fhm_admin_partner_partnergroup",
     *      requirements={"id"="[a-z0-9]*"}
     * )
     */
    public function partnergroupAction(Request $request)
    {
        $partnergroups = json_decode($request->get('list'));
        $document = $this->get('fhm_tools')->dmRepository(self::$repository)->find($request->get('id'));
        foreach ($document->getPartnergroups() as $partnergroup) {
            $document->removePartnergroup($partnergroup);
        }
        foreach ($partnergroups as $key => $data) {
            $partnergroup = $this->get('fhm_tools')->dmRepository('FhmPartnerBundle:PartnerGroup')->find($data->id);
            $document->addPartnergroup($partnergroup);
        }
        $this->get('fhm_tools')->dmPersist($document);

        return new Response();
    }
}