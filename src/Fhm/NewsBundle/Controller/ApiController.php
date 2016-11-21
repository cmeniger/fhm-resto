<?php
namespace Fhm\NewsBundle\Controller;

use Fhm\FhmBundle\Controller\RefApiController as FhmController;
use Fhm\NewsBundle\Document\News;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * @Route("/api/news")
 */
class ApiController extends FhmController
{
    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct('Fhm', 'News', 'news');
    }

    /**
     * @Route
     * (
     *      path="/",
     *      name="fhm_api_news"
     * )
     * @Template("::FhmNews/Api/index.html.twig")
     */
    public function indexAction()
    {
        return parent::indexAction();
    }

    /**
     * @Route
     * (
     *      path="/autocomplete/",
     *      name="fhm_api_news_autocomplete"
     * )
     * @Template("::FhmNews/Api/autocomplete.html.twig")
     */
    public function autocompleteAction(Request $request)
    {
        return parent::autocompleteAction($request);
    }

    /**
     * @Route
     * (
     *      path="/historic/",
     *      name="fhm_api_news_historic"
     * )
     * @Template("::FhmNews/Api/historic.html.twig")
     */
    public function historicAction(Request $request)
    {
        return parent::historicAction($request);
    }

    /**
     * @Route
     * (
     *      path="/historic/copy/{id}",
     *      name="fhm_api_news_historic_copy",
     *      requirements={"id"="[a-z0-9]*"}
     * )
     */
    public function historicCopyAction(Request $request, $id)
    {
        return parent::historicCopyAction($request, $id);
    }

    /**
     * @Route
     * (
     *      path="/detail/{template}/{rows}/{pagination}/{id}",
     *      name="fhm_api_news_detail",
     *      requirements={"id"=".+", "rows"="\d*", "pagination"="[0-1]"},
     *      defaults={"id"=null, "rows"=null, "pagination"=1}
     * )
     * @Template("::FhmNews/Api/detail.html.twig")
     */
    public function detailAction($template, $id, $rows, $pagination)
    {
        $document = "";
        $instance = $this->instanceData();
        // News
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
                throw $this->createNotFoundException($this->get('translator')->trans('news.group.error.unknown', array(), 'FhmNewsBundle'));
            }
            // ERROR - Forbidden
            elseif(!$instance->user->admin && ($document->getDelete() || !$document->getActive()))
            {
                throw new HttpException(403, $this->get('translator')->trans('news.group.error.forbidden', array(), 'FhmNewsBundle'));
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
                $document = $this->dmRepository("FhmNewsBundle:NewsGroup")->getById($id);
                $document = ($document) ? $document : $this->dmRepository("FhmNewsBundle:NewsGroup")->getByAlias($id);
                $document = ($document) ? $document : $this->dmRepository("FhmNewsBundle:NewsGroup")->getByName($id);
                $instance = $this->instanceData($document);
                // ERROR - unknown
                if($document == "")
                {
                    throw $this->createNotFoundException($this->get('translator')->trans('news.group.error.unknown', array(), 'FhmNewsBundle'));
                }
                // ERROR - Forbidden
                elseif(!$instance->user->admin && ($document->getDelete() || !$document->getActive()))
                {
                    throw new HttpException(403, $this->get('translator')->trans('news.group.error.forbidden', array(), 'FhmNewsBundle'));
                }
                // Change grouping
                if($instance->grouping->different && $document->getGrouping())
                {
                    $this->get($this->getParameters("grouping", "fhm_fhm"))->setGrouping($document->getFirstGrouping());
                }
            }
            // News
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
                    $this->dmRepository()->getNewsByGroupIndex($document, $dataSearch['search'], $dataPagination['pagination'], $this->pagination->page) :
                    $this->dmRepository()->getFrontIndex($dataSearch['search'], $dataPagination['pagination'], $this->pagination->page, $instance->grouping->current);
                $pagination = $document ?
                    $this->getPagination($dataPagination['pagination'], count($documents), $this->dmRepository("FhmNewsBundle:News")->getNewsByGroupCount($document, $dataSearch['search']), 'pagination', $this->formRename($form->getName(), $dataSearch), $this->generateUrl('fhm_api_news_detail', array('template' => $template, 'id' => $id, 'rows' => $rows, 'pagination' => $pagination))) :
                    $this->getPagination($dataPagination['pagination'], count($documents), $this->dmRepository("FhmNewsBundle:News")->getFrontCount($dataSearch['search'], $instance->grouping->current), 'pagination', $this->formRename($form->getName(), $dataSearch), $this->generateUrl('fhm_api_news_detail', array('template' => $template, 'id' => $id, 'rows' => $rows, 'pagination' => $pagination)));
            }
            // Router request
            else
            {
                $documents = $document ?
                    $this->dmRepository()->getNewsByGroupIndex($document, $dataSearch['search'], 1, $this->pagination->page) :
                    $this->dmRepository()->getFrontIndex($dataSearch['search'], 1, $this->pagination->page, $instance->grouping->current);
                if($pagination)
                {
                    $pagination = $document ?
                        $this->getPagination(1, count($documents), $this->dmRepository("FhmNewsBundle:News")->getNewsByGroupCount($document, $dataSearch['search']), 'pagination', $this->formRename($form->getName(), $dataSearch), $this->generateUrl('fhm_api_news_detail', array('template' => $template, 'id' => $id, 'rows' => $rows, 'pagination' => $pagination))) :
                        $this->getPagination(1, count($documents), $this->dmRepository("FhmNewsBundle:News")->getFrontCount($dataSearch['search'], $instance->grouping->current), 'pagination', $this->formRename($form->getName(), $dataSearch), $this->generateUrl('fhm_api_news_detail', array('template' => $template, 'id' => $id, 'rows' => $rows, 'pagination' => $pagination)));
                }
            }
        }

        return new Response(
            $this->renderView(
                "::FhmNews/Template/" . $template . ".html.twig",
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