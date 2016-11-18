<?php
namespace Fhm\TestimonyBundle\Controller;

use Fhm\FhmBundle\Controller\RefApiController as FhmController;
use Fhm\TestimonyBundle\Document\Testimony;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/api/testimony")
 */
class ApiController extends FhmController
{
    /**
     * Constructor
     */
    public function __construct()
    {
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
        $instance  = $this->instanceData();
        $classType = '\Fhm\FhmBundle\Form\Type\Front\SearchType';
        $form      = $this->createForm(new $classType($instance), null);
        $form->setData($this->get('request')->get($form->getName()));
        $dataSearch     = $form->getData();
        $dataPagination = $this->get('request')->get('FhmPagination');
        $this->setPagination($rows);
        // Ajax pagination request
        if($pagination && isset($dataPagination['pagination']))
        {
            $documents  = $this->dmRepository()->getFrontIndex($dataSearch['search'], $dataPagination['pagination'], $this->pagination->page, $instance->grouping->current);
            $pagination = $this->getPagination($dataPagination['pagination'], count($documents), $this->dmRepository()->getFrontCount($dataSearch['search'], $instance->grouping->current), 'pagination', $this->formRename($form->getName(), $dataSearch), $this->generateUrl('fhm_api_testimony_detail', array('template' => $template, 'rows' => $rows, 'pagination' => $pagination)));
        }
        // Router request
        else
        {
            $documents = $this->dmRepository()->getFrontIndex($dataSearch['search'], 1, $this->pagination->page, $instance->grouping->current);
            if($pagination)
            {
                $pagination = $this->getPagination(1, count($documents), $this->dmRepository()->getFrontCount($dataSearch['search'], $instance->grouping->current), 'pagination', $this->formRename($form->getName(), $dataSearch), $this->generateUrl('fhm_api_testimony_detail', array('template' => $template, 'rows' => $rows, 'pagination' => $pagination)));
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