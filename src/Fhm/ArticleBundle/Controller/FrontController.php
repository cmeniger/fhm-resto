<?php
namespace Fhm\ArticleBundle\Controller;

use Fhm\FhmBundle\Controller\RefFrontController as FhmController;
use Fhm\ArticleBundle\Document\Article;
use Fhm\FhmBundle\Services\Tools;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * @Route("/article", service="fhm_article_controller_front")
 */
class FrontController extends FhmController
{
    /**
     * FrontController constructor.
     * @param Tools $tools
     */
    public function __construct(Tools $tools)
    {
        $this->setFhmTools($tools);
        parent::__construct('Fhm', 'Article', 'article');
    }

    /**
     * @Route
     * (
     *      path="/",
     *      name="fhm_article"
     * )
     * @Template("::FhmArticle/Front/index.html.twig")
     */
    public function indexAction()
    {
        return parent::indexAction();
    }

    /**
     * @Route
     * (
     *      path="/create",
     *      name="fhm_article_create"
     * )
     * @Template("::FhmArticle/Front/create.html.twig")
     */
    public function createAction(Request $request)
    {
        // For activate this route, delete next line
        throw $this->createNotFoundException(
            $this->get('translator')->trans('fhm.error.route', array(), 'FhmFhmBundle')
        );

        /** to uncomment */
        //return parent::createAction($request);
    }

    /**
    * @Route
    * (
    *      path="/duplicate/{id}",
    *      name="fhm_article_duplicate",
    *      requirements={"id"="[a-z0-9]*"}
    * )
    * @Template("::FhmArticle/Front/create.html.twig")
    */
    public function duplicateAction(Request $request, $id)
    {
        // For activate this route, delete next line
        throw $this->createNotFoundException(
            $this->get('translator')->trans('fhm.error.route', array(), 'FhmFhmBundle')
        );

        /** to uncomment */
        //return parent::duplicateAction($request, $id);
    }

    /**
    * @Route
    * (
    *      path="/update/{id}",
    *      name="fhm_article_update",
    *      requirements={"id"="[a-z0-9]*"}
    * )
    * @Template("::FhmArticle/Front/update.html.twig")
    */
    public function updateAction(Request $request, $id)
    {
        // For activate this route, delete next line
        throw $this->createNotFoundException(
            $this->get('translator')->trans('fhm.error.route', array(), 'FhmFhmBundle')
        );

        /**to uncomment**/
        //return parent::updateAction($request, $id);
    }

    /**
    * @Route
    * (
    *      path="/detail/{id}",
    *      name="fhm_article_detail",
    *      requirements={"id"=".+"}
    * )
    * @Template("::FhmArticle/Front/detail.html.twig")
    */
    public function detailAction($id)
    {
        return parent::detailAction($id);
    }

    /**
    * @Route
    * (
    *      path="/delete/{id}",
    *      name="fhm_article_delete",
    *      requirements={"id"="[a-z0-9]*"}
    * )
    */
    public function deleteAction($id)
    {
        // For activate this route, delete next line
        throw $this->createNotFoundException(
            $this->get('translator')->trans('fhm.error.route', array(), 'FhmFhmBundle')
        );

        /** to uncomment */
        //return parent::deleteAction($id);
    }

    /**
    * @Route
    * (
    *      path="/{id}",
    *      name="fhm_article_lite",
    *      requirements={"id"=".+"}
    * )
    * @Template("::FhmArticle/Front/detail.html.twig")
    */
    public function liteAction($id)
    {
        return $this->detailAction($id);
    }
}