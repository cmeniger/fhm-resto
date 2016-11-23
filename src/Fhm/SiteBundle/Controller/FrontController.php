<?php
namespace Fhm\SiteBundle\Controller;

use Fhm\FhmBundle\Controller\RefFrontController as FhmController;
use Fhm\SiteBundle\Document\Site;
use Fhm\FhmBundle\Services\Tools;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * @Route("/site", service="fhm_site_controller_front")
 */
class FrontController extends FhmController
{
    /**
     * FrontController constructor.
     *
     * @param \Fhm\FhmBundle\Services\Tools $tools
     */
    public function __construct(Tools $tools)
    {
        $this->setFhmTools($tools);
        parent::__construct('Fhm', 'Site', 'site');
    }

    /**
     * @Route
     * (
     *      path="/",
     *      name="fhm_site"
     * )
     * @Template("::FhmSite/Front/index.html.twig")
     */
    public function indexAction()
    {
        /** For activate this route, delete next line **/
        throw $this->createNotFoundException($this->fhm_tools->trans('fhm.error.route', array(), 'FhmFhmBundle'));

        /** to uncommet to activate the action */
        //return parent::indexAction();
    }

    /**
     * @Route
     * (
     *      path="/detail/{id}",
     *      name="fhm_site_detail",
     *      requirements={"id"=".+"}
     * )
     * @Template("::FhmSite/Front/detail.html.twig")
     */
    public function detailAction($id)
    {
        parent::detailAction($id);
        $this->get($this->fhm_tools->getParameter("grouping", "fhm_fhm"))->setGrouping($id);

        return $this->redirect($this->fhm_tools->getUrl('project_home'));
    }

    /**
     * @Route
     * (
     *      path="/create",
     *      name="fhm_site_create"
     * )
     * @Template("::FhmSite/Front/create.html.twig")
     */
    public function createAction(Request $request)
    {
        /** For activate this route, delete next line **/
        throw $this->createNotFoundException($this->fhm_tools->trans('fhm.error.route', array(), 'FhmFhmBundle'));

        /** to uncomment to activate the action */
        //return parent::createAction($request);
    }

    /**
     * @Route
     * (
     *      path="/duplicate/{id}",
     *      name="fhm_site_duplicate",
     *      requirements={"id"="[a-z0-9]*"}
     * )
     * @Template("::FhmSite/Front/create.html.twig")
     */
    public function duplicateAction(Request $request, $id)
    {
        /** For activate this route, delete next line **/
        throw $this->createNotFoundException($this->fhm_tools->trans('fhm.error.route', array(), 'FhmFhmBundle'));

        /** to uncomment to activate the action */
        //return parent::duplicateAction($request, $id);
    }

    /**
     * @Route
     * (
     *      path="/update/{id}",
     *      name="fhm_site_update",
     *      requirements={"id"="[a-z0-9]*"}
     * )
     * @Template("::FhmSite/Front/update.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        // For activate this route, delete next line
        throw $this->createNotFoundException($this->fhm_tools->trans('fhm.error.route', array(), 'FhmFhmBundle'));

        /** to uncomment to activate the action */
        //return parent::updateAction($request, $id);
    }

    /**
     * @Route
     * (
     *      path="/delete/{id}",
     *      name="fhm_site_delete",
     *      requirements={"id"="[a-z0-9]*"}
     * )
     */
    public function deleteAction($id)
    {
        // For activate this route, delete next line
        throw $this->createNotFoundException($this->fhm_tools->trans('fhm.error.route', array(), 'FhmFhmBundle'));

        /** to uncomment to activate the action */
        //return parent::deleteAction($id);
    }

    /**
     * @Route
     * (
     *      path="/{id}",
     *      name="fhm_site_lite",
     *      requirements={"id"=".+"}
     * )
     * @Template("::FhmSite/Front/detail.html.twig")
     */
    public function liteAction($id)
    {
        return $this->detailAction($id);
    }
}