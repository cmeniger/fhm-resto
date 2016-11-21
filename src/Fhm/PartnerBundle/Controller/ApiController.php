<?php
namespace Fhm\PartnerBundle\Controller;

use Fhm\FhmBundle\Controller\RefApiController as FhmController;
use Fhm\PartnerBundle\Document\Partner;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * @Route("/api/partner")
 */
class ApiController extends FhmController
{
    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct('Fhm', 'Partner', 'partner');
    }

    /**
     * @Route
     * (
     *      path="/",
     *      name="fhm_api_partner"
     * )
     * @Template("::FhmPartner/Api/index.html.twig")
     */
    public function indexAction()
    {
        return parent::indexAction();
    }

    /**
     * @Route
     * (
     *      path="/autocomplete/",
     *      name="fhm_api_partner_autocomplete"
     * )
     * @Template("::FhmPartner/Api/autocomplete.html.twig")
     */
    public function autocompleteAction(Request $request)
    {
        return parent::autocompleteAction($request);
    }

    /**
     * @Route
     * (
     *      path="/detail/{template}/{rows}/{pagination}/{id}",
     *      name="fhm_api_partner_detail",
     *      requirements={"id"=".+", "rows"="\d*", "pagination"="[0-1]"},
     *      defaults={"id"=null, "rows"=null, "pagination"=1}
     * )
     * @Template("::FhmPartner/Api/detail.html.twig")
     */
    public function detailAction($template, $id, $rows, $pagination)
    {
        $document = "";
        $instance = $this->instanceData();
        // Partner
        if($id && $template == 'full')
        {
            $document  = $this->dmRepository()->getById($id);
            $document  = ($document) ? $document : $this->dmRepository()->getByAlias($id);
            $document  = ($document) ? $document : $this->dmRepository()->getByName($id);
            $instance  = $this->instanceData($document);
            $documents = '';
            $form      = '';
            // ERROR - unknown
            if($document == "")
            {
                throw $this->createNotFoundException($this->get('translator')->trans('partner.group.error.unknown', array(), 'FhmPartnerBundle'));
            }
            // ERROR - Forbidden
            elseif(!$instance->user->admin && ($document->getDelete() || !$document->getActive()))
            {
                throw new HttpException(403, $this->get('translator')->trans('partner.group.error.forbidden', array(), 'FhmPartnerBundle'));
            }
            // Change grouping
            if($instance->grouping->different && $document->getGrouping())
            {
                $this->get($this->getParameters("grouping", "fhm_fhm"))->setGrouping($document->getFirstGrouping());
            }
        }
        else
        {
            // Group
            if($id)
            {
                $document = $this->dmRepository("FhmPartnerBundle:PartnerGroup")->getById($id);
                $document = ($document) ? $document : $this->dmRepository("FhmPartnerBundle:PartnerGroup")->getByAlias($id);
                $document = ($document) ? $document : $this->dmRepository("FhmPartnerBundle:PartnerGroup")->getByName($id);
                $instance = $this->instanceData($document);
                // ERROR - unknown
                if($document == "")
                {
                    throw $this->createNotFoundException($this->get('translator')->trans('partner.group.error.unknown', array(), 'FhmPartnerBundle'));
                }
                // ERROR - Forbidden
                elseif(!$instance->user->admin && ($document->getDelete() || !$document->getActive()))
                {
                    throw new HttpException(403, $this->get('translator')->trans('partner.group.error.forbidden', array(), 'FhmPartnerBundle'));
                }
                // Change grouping
                if($instance->grouping->different && $document->getGrouping())
                {
                    $this->get($this->getParameters("grouping", "fhm_fhm"))->setGrouping($document->getFirstGrouping());
                }
            }
            // Partner
            $classType = '\Fhm\FhmBundle\Form\Type\Front\SearchType';
            $form      = $this->createForm(new $classType($instance), null);
            $form->setData($this->get('request')->get($form->getName()));
            $dataSearch     = $form->getData();
            $dataPagination = $this->get('request')->get('FhmPagination');
            $this->setPagination($rows);
            // Ajax pagination request
            if($pagination && isset($dataPagination['pagination']))
            {
                $documents  = $document ?
                    $this->dmRepository()->getPartnerByGroupIndex($document, $dataSearch['search'], $dataPagination['pagination'], $this->pagination->page) :
                    $this->dmRepository()->getFrontIndex($dataSearch['search'], $dataPagination['pagination'], $this->pagination->page);
                $pagination = $document ?
                    $this->getPagination($dataPagination['pagination'], count($documents), $this->dmRepository("FhmPartnerBundle:Partner")->getPartnerByGroupCount($document, $dataSearch['search']), 'pagination', $this->formRename($form->getName(), $dataSearch), $this->generateUrl('fhm_api_partner_detail', array('template' => $template, 'group' => $id, 'rows' => $rows, 'pagination' => $pagination))) :
                    $this->getPagination($dataPagination['pagination'], count($documents), $this->dmRepository("FhmPartnerBundle:Partner")->getFrontCount($dataSearch['search']), 'pagination', $this->formRename($form->getName(), $dataSearch), $this->generateUrl('fhm_api_partner_detail', array('template' => $template, 'group' => $id, 'rows' => $rows, 'pagination' => $pagination)));
            }
            // Router request
            else
            {
                $documents = $document ?
                    $this->dmRepository()->getPartnerByGroupIndex($document, $dataSearch['search'], 1, $this->pagination->page) :
                    $this->dmRepository()->getFrontIndex($dataSearch['search'], 1, $this->pagination->page);
                if($pagination)
                {
                    $pagination = $document ?
                        $this->getPagination(1, count($documents), $this->dmRepository("FhmPartnerBundle:Partner")->getPartnerByGroupCount($document, $dataSearch['search']), 'pagination', $this->formRename($form->getName(), $dataSearch), $this->generateUrl('fhm_api_partner_detail', array('template' => $template, 'group' => $id, 'rows' => $rows, 'pagination' => $pagination))) :
                        $this->getPagination(1, count($documents), $this->dmRepository("FhmPartnerBundle:Partner")->getFrontCount($dataSearch['search']), 'pagination', $this->formRename($form->getName(), $dataSearch), $this->generateUrl('fhm_api_partner_detail', array('template' => $template, 'group' => $id, 'rows' => $rows, 'pagination' => $pagination)));
                }
            }
        }

        return new Response(
            $this->renderView(
                "::FhmPartner/Template/" . $template . ".html.twig",
                array(
                    'document'   => $document,
                    'documents'  => $documents,
                    'pagination' => $pagination ? $pagination : array(),
                    'instance'   => $instance,
                    'form'       => $form ? $form->createView() : $form,
                )
            )
        );
    }
}