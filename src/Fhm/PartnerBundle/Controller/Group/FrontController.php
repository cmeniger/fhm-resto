<?php
namespace Fhm\PartnerBundle\Controller\Group;

use Fhm\FhmBundle\Controller\RefFrontController as FhmController;
use Fhm\PartnerBundle\Document\Partner;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * @Route("/partnergroup")
 */
class FrontController extends FhmController
{
    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct('Fhm', 'Partner', 'partner_group', 'PartnerGroup');
        $this->form->type->create = 'Fhm\\PartnerBundle\\Form\\Type\\Front\\Group\\CreateType';
        $this->form->type->update = 'Fhm\\PartnerBundle\\Form\\Type\\Front\\Group\\UpdateType';
        $this->translation        = array('FhmPartnerBundle', 'partner.group');
    }

    /**
     * @Route
     * (
     *      path="/",
     *      name="fhm_partner_group"
     * )
     * @Template("::FhmPartner/Front/Group/index.html.twig")
     */
    public function indexAction()
    {
        $response = parent::indexAction();
        foreach($response['documents'] as $key => $document)
        {
            $response['documents'][$key]->documents = $this->dmRepository("FhmPartnerBundle:Partner")->getPartnerByGroupAll($document);
        }

        return $response;
    }

    /**
     * @Route
     * (
     *      path="/create",
     *      name="fhm_partner_group_create"
     * )
     * @Template("::FhmPartner/Front/Group/create.html.twig")
     */
    public function createAction(Request $request)
    {
        // For activate this route, delete next line
        throw $this->createNotFoundException($this->get('translator')->trans('fhm.error.route', array(), 'FhmFhmBundle'));

        return parent::createAction($request);
    }

    /**
     * @Route
     * (
     *      path="/duplicate/{id}",
     *      name="fhm_partner_group_duplicate",
     *      requirements={"id"="[a-z0-9]*"}
     * )
     * @Template("::FhmPartner/Front/Group/create.html.twig")
     */
    public function duplicateAction(Request $request, $id)
    {
        // For activate this route, delete next line
        throw $this->createNotFoundException($this->get('translator')->trans('fhm.error.route', array(), 'FhmFhmBundle'));

        return parent::duplicateAction($request, $id);
    }

    /**
     * @Route
     * (
     *      path="/update/{id}",
     *      name="fhm_partner_group_update",
     *      requirements={"id"="[a-z0-9]*"}
     * )
     * @Template("::FhmPartner/Front/Group/update.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        // For activate this route, delete next line
        throw $this->createNotFoundException($this->get('translator')->trans('fhm.error.route', array(), 'FhmFhmBundle'));

        return parent::updateAction($request, $id);
    }

    /**
     * @Route
     * (
     *      path="/detail/{id}",
     *      name="fhm_partner_group_detail",
     *      requirements={"id"=".+"}
     * )
     * @Template("::FhmPartner/Front/Group/detail.html.twig")
     */
    public function detailAction($id)
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
            $documents = $this->dmRepository("FhmPartnerBundle:Partner")->getPartnerByGroupIndex($document, $dataSearch['search'], $dataPagination['pagination'], $this->getParameter(array('pagination', 'front', 'page'), 'fhm_fhm'));

            return array_merge(
                $response,
                array(
                    'documents'  => $documents,
                    'pagination' => $this->getPagination($dataPagination['pagination'], count($documents), $this->dmRepository("FhmPartnerBundle:Partner")->getPartnerByGroupCount($document, $dataSearch['search']), 'pagination', $this->formRename($form->getName(), $dataSearch), $this->generateUrl('fhm_partner_group_lite', array('id' => $id))),
                ));
        }
        // Router request
        else
        {
            $documents = $this->dmRepository("FhmPartnerBundle:Partner")->getPartnerByGroupIndex($document, $dataSearch['search'], 1, $this->getParameter(array('pagination', 'front', 'page'), 'fhm_fhm'));

            return array_merge(
                $response,
                array(
                    'documents'  => $documents,
                    'pagination' => $this->getPagination(1, count($documents), $this->dmRepository("FhmPartnerBundle:Partner")->getPartnerByGroupCount($document, $dataSearch['search']), 'pagination', $this->formRename($form->getName(), $dataSearch), $this->generateUrl('fhm_partner_group_lite', array('id' => $id))),
                    'form'       => $form->createView(),
                ));
        }
    }

    /**
     * @Route
     * (
     *      path="/delete/{id}",
     *      name="fhm_partner_group_delete",
     *      requirements={"id"="[a-z0-9]*"}
     * )
     */
    public function deleteAction($id)
    {
        // For activate this route, delete next line
        throw $this->createNotFoundException($this->get('translator')->trans('fhm.error.route', array(), 'FhmFhmBundle'));

        return parent::deleteAction($id);
    }

    /**
     * @Route
     * (
     *      path="/template/brief/{id}",
     *      name="fhm_partner_group_template_brief",
     *      requirements={"id"=".+"}
     * )
     * @Template("::FhmPartner/Template/brief.html.twig")
     */
    public function templateBriefAction($id)
    {
        $response          = parent::detailAction($id);
        $document          = $response['document'];
        $dataPagination    = $this->get('request')->get('FhmPagination');
        $this->translation = array('FhmPartnerBundle', 'partner');
        // Ajax pagination request
        if(isset($dataPagination['pagination']))
        {
            $documents = $this->dmRepository("FhmPartnerBundle:Partner")->getPartnerByGroupIndex($document, '', $dataPagination['pagination'], $this->getParameter(array('pagination', 'front', 'page'), 'fhm_fhm'));

            return array_merge(
                $response,
                array(
                    'documents'  => $documents,
                    'pagination' => $this->getPagination($dataPagination['pagination'], count($documents), $this->dmRepository("FhmPartnerBundle:Partner")->getPartnerByGroupCount($document, ''), 'pagination', array(), $this->generateUrl('fhm_partner_group_template_brief', array('id' => $id))),
                ));
        }
        // Router request
        else
        {
            $documents = $this->dmRepository("FhmPartnerBundle:Partner")->getPartnerByGroupIndex($document, '', 1, $this->getParameter(array('pagination', 'front', 'page'), 'fhm_fhm'));

            return array_merge(
                $response,
                array(
                    'documents'  => $documents,
                    'pagination' => $this->getPagination(1, count($documents), $this->dmRepository("FhmPartnerBundle:Partner")->getPartnerByGroupCount($document, ''), 'pagination', array(), $this->generateUrl('fhm_partner_group_template_brief', array('id' => $id))),
                ));
        }
    }

    /**
     * @Route
     * (
     *      path="/{id}",
     *      name="fhm_partner_group_lite",
     *      requirements={"id"=".+"}
     * )
     * @Template("::FhmPartner/Front/Group/detail.html.twig")
     */
    public function liteAction($id)
    {
        return $this->detailAction($id);
    }
}