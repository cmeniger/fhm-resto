<?php
namespace Fhm\PartnerBundle\Controller\Group;

use Fhm\FhmBundle\Controller\RefAdminController as FhmController;
use Fhm\FhmBundle\Form\Type\Admin\SearchType;
use Fhm\PartnerBundle\Document\PartnerGroup;
use Fhm\PartnerBundle\Form\Type\Admin\Group\CreateType;
use Fhm\PartnerBundle\Form\Type\Admin\Group\UpdateType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * @Route("/admin/partnergroup")
 * -------------------------------------------
 * Class AdminController
 * @package Fhm\PartnerBundle\Controller\Group
 */
class AdminController extends FhmController
{
    /**
     * AdminController constructor.
     * @param string $repository
     * @param string $source
     * @param string $domain
     * @param string $translation
     * @param string $document
     * @param string $route
     */
    public function __construct(
        $repository = "FhmPartnerBundle:PartnerGroup",
        $source = "fhm",
        $domain = "FhmPartnerBundle",
        $translation = "partner.group",
        $document = PartnerGroup::class,
        $route = 'partner_group'
    ) {
        self::$repository = $repository;
        self::$source = $source;
        self::$domain = $domain;
        self::$translation = $translation;
        self::$document = new $document();
        self::$class = get_class(self::$document);
        self::$route = $route;
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
        $document = $this->get('fhm_tools')->dmRepository(self::$repository)->find($id);

        return array_merge(
            array(
                'partner1' => $this->get('fhm_tools')->dmRepository('FhmPartnerBundle:Partner')->getAllEnable(),
                'partner2' => $this->getList($document->getPartner()),
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
        $response = parent::detailAction($id);
        $document = $response['document'];
        $form = $this->createForm(SearchType::class);
        $form->setData($this->get('request_stack')->getCurrentRequest()->get($form->getName()));
        $dataSearch = $form->getData();
        $documents = $this->get('fhm_tools')->dmRepository("FhmPartnerBundle:Partner")->getPartnerByGroupIndex(
            $document,
            $dataSearch['search']
        );

        return array_merge(
            $response,
            array(
                'documents' => $documents,
                'form' => $form->createView(),
            )
        );
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
     *      path="/partner",
     *      name="fhm_admin_partner_group_partner",
     *      requirements={"id"="[a-z0-9]*"}
     * )
     */
    public function partnerAction(Request $request)
    {
        $partner = json_decode($request->get('list'));
        $document = $this->get('fhm_tools')->dmRepository(self::$repository)->find($request->get('id'));
        foreach ($document->getPartner() as $new) {
            $document->removePartner($new);
        }
        foreach ($partner as $key => $data) {
            $new = $this->get('fhm_tools')->dmRepository('FhmPartnerBundle:Partner')->find($data->id);
            $document->addPartner($new);
        }
        $this->get('fhm_tools')->dmPersist($document);

        return new Response();
    }
}