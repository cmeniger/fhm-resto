<?php
namespace Fhm\NewsBundle\Controller\Group;

use Fhm\FhmBundle\Controller\RefFrontController as FhmController;
use Fhm\NewsBundle\Document\News;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * @Route("/newsgroup")
 */
class FrontController extends FhmController
{
    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct('Fhm', 'News', 'news_group', 'NewsGroup');
        $this->form->type->create = 'Fhm\\NewsBundle\\Form\\Type\\Front\\Group\\CreateType';
        $this->form->type->update = 'Fhm\\NewsBundle\\Form\\Type\\Front\\Group\\UpdateType';
        $this->translation        = array('FhmNewsBundle', 'news.group');
    }

    /**
     * @Route
     * (
     *      path="/",
     *      name="fhm_news_group"
     * )
     * @Template("::FhmNews/Front/Group/index.html.twig")
     */
    public function indexAction()
    {
        $response = parent::indexAction();
        foreach($response['documents'] as $key => $document)
        {
            $response['documents'][$key]->allnews = $this->dmRepository("FhmNewsBundle:News")->getNewsByGroupAll($document);
        }

        return $response;
    }

    /**
     * @Route
     * (
     *      path="/create",
     *      name="fhm_news_group_create"
     * )
     * @Template("::FhmNews/Front/Group/create.html.twig")
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
     *      name="fhm_news_group_duplicate",
     *      requirements={"id"="[a-z0-9]*"}
     * )
     * @Template("::FhmNews/Front/Group/create.html.twig")
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
     *      name="fhm_news_group_update",
     *      requirements={"id"="[a-z0-9]*"}
     * )
     * @Template("::FhmNews/Front/Group/update.html.twig")
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
     *      name="fhm_news_group_detail",
     *      requirements={"id"=".+"}
     * )
     * @Template("::FhmNews/Front/Group/detail.html.twig")
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
        $this->translation = array('FhmNewsBundle', 'news');
        // Ajax pagination request
        if(isset($dataPagination['pagination']))
        {
            $documents = $this->dmRepository("FhmNewsBundle:News")->getNewsByGroupIndex($document, $dataSearch['search'], $dataPagination['pagination'], $this->getParameter(array('pagination', 'front', 'page'), 'fhm_fhm'));

            return array_merge(
                $response,
                array(
                    'documents'  => $documents,
                    'pagination' => $this->getPagination($dataPagination['pagination'], count($documents), $this->dmRepository("FhmNewsBundle:News")->getNewsByGroupCount($document, $dataSearch['search']), 'pagination', $this->formRename($form->getName(), $dataSearch), $this->generateUrl('fhm_news_group_lite', array('id' => $id))),
                ));
        }
        // Router request
        else
        {
            $documents = $this->dmRepository("FhmNewsBundle:News")->getNewsByGroupIndex($document, $dataSearch['search'], 1, $this->getParameter(array('pagination', 'front', 'page'), 'fhm_fhm'));

            return array_merge(
                $response,
                array(
                    'documents'  => $documents,
                    'pagination' => $this->getPagination(1, count($documents), $this->dmRepository("FhmNewsBundle:News")->getNewsByGroupCount($document, $dataSearch['search']), 'pagination', $this->formRename($form->getName(), $dataSearch), $this->generateUrl('fhm_news_group_lite', array('id' => $id))),
                    'form'       => $form->createView(),
                ));
        }
    }

    /**
     * @Route
     * (
     *      path="/delete/{id}",
     *      name="fhm_news_group_delete",
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
     *      path="/{id}",
     *      name="fhm_news_group_lite",
     *      requirements={"id"=".+"}
     * )
     * @Template("::FhmNews/Front/Group/detail.html.twig")
     */
    public function liteAction($id)
    {
        return $this->detailAction($id);
    }
}