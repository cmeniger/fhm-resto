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
 * @Route("/api/partner", service="fhm_partner_controller_api")
 */
class ApiController extends FhmController
{
    /**
     * ApiController constructor.
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
        $instance = $this->fhm_tools->instanceData();
        // Partner
        if($id && $template == 'full')
        {
            $document  = $this->fhm_tools->dmRepository()->getById($id);
            $document  = ($document) ? $document : $this->fhm_tools->dmRepository()->getByAlias($id);
            $document  = ($document) ? $document : $this->fhm_tools->dmRepository()->getByName($id);
            $instance  = $this->fhm_tools->instanceData($document);
            $documents = '';
            $form      = '';
            // ERROR - unknown
            if($document == "")
            {
                throw $this->createNotFoundException($this->fhm_tools->trans('partner.group.error.unknown', array(), 'FhmPartnerBundle'));
            }
            // ERROR - Forbidden
            elseif(!$instance->user->admin && ($document->getDelete() || !$document->getActive()))
            {
                throw new HttpException(403, $this->fhm_tools->trans('partner.group.error.forbidden', array(), 'FhmPartnerBundle'));
            }
            // Change grouping
            if($instance->grouping->different && $document->getGrouping())
            {
                $this->get($this->fhm_tools->getParameter("grouping", "fhm_fhm"))->setGrouping($document->getFirstGrouping());
            }
        }
        else
        {
            // Group
            if($id)
            {
                $document = $this->fhm_tools->dmRepository("FhmPartnerBundle:PartnerGroup")->getById($id);
                $document = ($document) ? $document : $this->fhm_tools->dmRepository("FhmPartnerBundle:PartnerGroup")->getByAlias($id);
                $document = ($document) ? $document : $this->fhm_tools->dmRepository("FhmPartnerBundle:PartnerGroup")->getByName($id);
                $instance = $this->fhm_tools->instanceData($document);
                // ERROR - unknown
                if($document == "")
                {
                    throw $this->createNotFoundException($this->fhm_tools->trans('partner.group.error.unknown', array(), 'FhmPartnerBundle'));
                }
                // ERROR - Forbidden
                elseif(!$instance->user->admin && ($document->getDelete() || !$document->getActive()))
                {
                    throw new HttpException(403, $this->fhm_tools->trans('partner.group.error.forbidden', array(), 'FhmPartnerBundle'));
                }
                // Change grouping
                if($instance->grouping->different && $document->getGrouping())
                {
                    $this->get($this->fhm_tools->getParameters("grouping", "fhm_fhm"))->setGrouping($document->getFirstGrouping());
                }
            }
            // Partner
            $classType = '\Fhm\FhmBundle\Form\Type\Front\SearchType';
            $form      = $this->createForm(new $classType($instance), null);
            $form->setData($this->get('request')->get($form->getName()));
            $dataSearch     = $form->getData();
            $dataPagination = $this->get('request')->get('FhmPagination');
            $this->fhm_tools->setPagination($rows);
            // Ajax pagination request
            if($pagination && isset($dataPagination['pagination']))
            {
                $documents  = $document ?
                    $this->fhm_tools->dmRepository()->getPartnerByGroupIndex($document, $dataSearch['search'], $dataPagination['pagination'], $this->pagination->page) :
                    $this->fhm_tools->dmRepository()->getFrontIndex($dataSearch['search'], $dataPagination['pagination'], $this->pagination->page);
                $pagination = $document ?
                    $this->fhm_tools->getPagination($dataPagination['pagination'], count($documents), $this->fhm_tools->dmRepository("FhmPartnerBundle:Partner")->getPartnerByGroupCount($document, $dataSearch['search']), 'pagination', $this->fhm_tools->formRename($form->getName(), $dataSearch), $this->fhm_tools->getUrl('fhm_api_partner_detail', array('template' => $template, 'group' => $id, 'rows' => $rows, 'pagination' => $pagination))) :
                    $this->fhm_tools->getPagination($dataPagination['pagination'], count($documents), $this->fhm_tools->dmRepository("FhmPartnerBundle:Partner")->getFrontCount($dataSearch['search']), 'pagination', $this->fhm_tools->formRename($form->getName(), $dataSearch), $this->fhm_tools->getUrl('fhm_api_partner_detail', array('template' => $template, 'group' => $id, 'rows' => $rows, 'pagination' => $pagination)));
            }
            // Router request
            else
            {
                $documents = $document ?
                    $this->fhm_tools->dmRepository()->getPartnerByGroupIndex($document, $dataSearch['search'], 1, $this->pagination->page) :
                    $this->fhm_tools->dmRepository()->getFrontIndex($dataSearch['search'], 1, $this->pagination->page);
                if($pagination)
                {
                    $pagination = $document ?
                        $this->fhm_tools->getPagination(1, count($documents), $this->fhm_tools->dmRepository("FhmPartnerBundle:Partner")->getPartnerByGroupCount($document, $dataSearch['search']), 'pagination', $this->fhm_tools->formRename($form->getName(), $dataSearch), $this->fhm_tools->getUrl('fhm_api_partner_detail', array('template' => $template, 'group' => $id, 'rows' => $rows, 'pagination' => $pagination))) :
                        $this->fhm_tools->getPagination(1, count($documents), $this->fhm_tools->dmRepository("FhmPartnerBundle:Partner")->getFrontCount($dataSearch['search']), 'pagination', $this->fhm_tools->formRename($form->getName(), $dataSearch), $this->fhm_tools->getUrl('fhm_api_partner_detail', array('template' => $template, 'group' => $id, 'rows' => $rows, 'pagination' => $pagination)));
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