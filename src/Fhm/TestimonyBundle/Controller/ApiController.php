<?php
namespace Fhm\TestimonyBundle\Controller;

use Fhm\FhmBundle\Controller\RefApiController as FhmController;
use Fhm\TestimonyBundle\Document\Testimony;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/api/testimony", service="fhm_testimony_controller_api")
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
        parent::__construct('Fhm', 'Testimony', 'testimony');
    }

    /**
     * @Route
     * (
     *      path="/",
     *      name="fhm_api_testimony"
     * )
     * @Template("::FhmTestimony/Api/index.html.twig")
     */
    public function indexAction()
    {
        return parent::indexAction();
    }

    /**
     * @Route
     * (
     *      path="/detail/{template}/{rows}/{pagination}",
     *      name="fhm_api_testimony_detail",
     *      requirements={"rows"="\d*", "pagination"="[0-1]"},
     *      defaults={"rows"=null, "pagination"=1}
     * )
     */
    public function detailAction($template, $rows, $pagination)
    {
        $document  = "";
        $instance  = $this->fhm_tools->instanceData();
        $classType = '\Fhm\FhmBundle\Form\Type\Front\SearchType';
        $form      = $this->createForm(new $classType($instance), null);
        $form->setData($this->get('request')->get($form->getName()));
        $dataSearch     = $form->getData();
        $dataPagination = $this->get('request')->get('FhmPagination');
        $this->fhm_tools->setPagination($rows);
        // Ajax pagination request
        if($pagination && isset($dataPagination['pagination']))
        {
            $documents  = $this->fhm_tools->dmRepository()->getFrontIndex($dataSearch['search'], $dataPagination['pagination'], $this->pagination->page, $instance->grouping->current);
            $pagination = $this->fhm_tools->getPagination($dataPagination['pagination'], count($documents), $this->fhm_tools->dmRepository()->getFrontCount($dataSearch['search'], $instance->grouping->current), 'pagination', $this->fhm_tools->formRename($form->getName(), $dataSearch), $this->fhm_tools->getUrl('fhm_api_testimony_detail', array('template' => $template, 'rows' => $rows, 'pagination' => $pagination)));
        }
        // Router request
        else
        {
            $documents = $this->fhm_tools->dmRepository()->getFrontIndex($dataSearch['search'], 1, $this->pagination->page, $instance->grouping->current);
            if($pagination)
            {
                $pagination = $this->fhm_tools->getPagination(1, count($documents), $this->fhm_tools->dmRepository()->getFrontCount($dataSearch['search'], $instance->grouping->current), 'pagination', $this->fhm_tools->formRename($form->getName(), $dataSearch), $this->fhm_tools->getUrl('fhm_api_testimony_detail', array('template' => $template, 'rows' => $rows, 'pagination' => $pagination)));
            }
        }

        return new Response(
            $this->renderView(
                "::FhmTestimony/Template/" . $template . ".html.twig",
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