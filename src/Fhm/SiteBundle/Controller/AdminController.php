<?php
namespace Fhm\SiteBundle\Controller;

use Fhm\FhmBundle\Controller\RefAdminController as FhmController;
use Fhm\FhmBundle\Services\Tools;
use Fhm\SiteBundle\Document\Site;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * @Route("/admin/site", service="fhm_site_controller_admin")
 */
class AdminController extends FhmController
{
    /**
     * AdminController constructor.
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
     *      name="fhm_admin_site"
     * )
     * @Template("::FhmSite/Admin/index.html.twig")
     */
    public function indexAction()
    {
        return parent::indexAction();
    }

    /**
     * @Route
     * (
     *      path="/create",
     *      name="fhm_admin_site_create"
     * )
     * @Template("::FhmSite/Admin/create.html.twig")
     */
    public function createAction(Request $request)
    {
        $response  = parent::createAction($request);
        $documents = $this->fhm_tools->dmRepository()->findAll();
        if (count($documents) == 1) {
            $document = $documents[0];
            $document->setDefault(true);
            $document->setActive(true);
            $this->fhm_tools->dmPersist($document);
        }

        return $response;
    }

    /**
     * @Route
     * (
     *      path="/detail/{id}",
     *      name="fhm_admin_site_detail",
     *      requirements={"id"="[a-z0-9]*"}
     * )
     * @Template("::FhmSite/Admin/detail.html.twig")
     */
    public function detailAction($id)
    {
        return parent::detailAction($id);
    }

    /**
     * @Route
     * (
     *      path="/update/default",
     *      name="fhm_admin_site_update_default"
     * )
     */
    public function updateDefaultAction(Request $request)
    {
        $document = $this->fhm_tools->dmRepository()->getDefault();
        if($document)
        {
            return $this->redirect($this->fhm_tools->getUrl(array('id' => $document->getId()), 'fhm_admin_site_update'));
        }
        else
        {
            return $this->redirect($this->fhm_tools->getUrl(array(), 'fhm_admin_site'));
        }
    }

    /**
     * @Route
     * (
     *      path="/update/{id}",
     *      name="fhm_admin_site_update",
     *      requirements={"id"="[a-z0-9]*"}
     * )
     * @Template("::FhmSite/Admin/update.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        return parent::updateAction($request, $id);
    }

    /**
     * @Route
     * (
     *      path="/duplicate/{id}",
     *      name="fhm_admin_site_duplicate",
     *      requirements={"id"="[a-z0-9]*"}
     * )
     * @Template("::FhmSite/Admin/create.html.twig")
     */
    public function duplicateAction(Request $request, $id)
    {
        return parent::duplicateAction($request, $id);
    }

    /**
     * @Route
     * (
     *      path="/delete/{id}",
     *      name="fhm_admin_site_delete",
     *      requirements={"id"="[a-z0-9]*"}
     * )
     */
    public function deleteAction($id)
    {
        $document = $this->fhm_tools->dmRepository()->find($id);
        if($document && $document->getDefault())
        {
            throw new HttpException(403, $this->fhm_tools->trans('.error.forbidden'));
        }

        return parent::deleteAction($id);
    }

    /**
     * @Route
     * (
     *      path="/undelete/{id}",
     *      name="fhm_admin_site_undelete",
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
     *      name="fhm_admin_site_activate",
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
     *      name="fhm_admin_site_deactivate",
     *      requirements={"id"="[a-z0-9]*"}
     * )
     */
    public function deactivateAction($id)
    {
        $document = $this->fhm_tools->dmRepository()->find($id);
        if($document && $document->getDefault())
        {
            throw new HttpException(403, $this->fhm_tools->trans('.error.forbidden'));
        }

        return parent::deactivateAction($id);
    }

    /**
     * @Route
     * (
     *      path="/import",
     *      name="fhm_admin_site_import"
     * )
     * @Template("::FhmSite/Admin/import.html.twig")
     */
    public function importAction(Request $request)
    {
        return parent::importAction($request);
    }

    /**
     * @Route
     * (
     *      path="/export",
     *      name="fhm_admin_site_export"
     * )
     * @Template("::FhmSite/Admin/export.html.twig")
     */
    public function exportAction(Request $request)
    {
        return parent::exportAction($request);
    }

    /**
     * @Route
     * (
     *      path="/grouping",
     *      name="fhm_admin_site_grouping"
     * )
     */
    public function groupingAction(Request $request)
    {
        return parent::groupingAction($request);
    }

    /**
     * @Route
     * (
     *      path="/default/{id}",
     *      name="fhm_admin_site_default",
     *      requirements={"id"="[a-z0-9]*"}
     * )
     */
    public function defaultAction($id)
    {
        // ERROR - Unknown route
        if(!$this->fhm_tools->routeExists('fhm_admin_' . $this->route))
        {
            throw $this->createNotFoundException($this->fhm_tools->trans('fhm.error.route', array(), 'FhmFhmBundle'));
        }
        $document = $this->fhm_tools->dmRepository()->find($id);
        $instance = $this->fhm_tools->instanceData($document);
        // ERROR - Unknown
        if($document == "")
        {
            throw $this->createNotFoundException($this->fhm_tools->trans('.error.unknown'));
        }
        if(!$instance->user->admin)
        {
            throw new HttpException(403, $this->fhm_tools->trans('.error.forbidden'));
        }
        // Deactivate
        $this->fhm_tools->dmRepository()->setLanguage($instance->language->visible ? $document->getLanguages() : false)->resetDefault();
        $document->setDefault(true);
        $document->setActive(true);
        $this->fhm_tools->dmPersist($document);
        // Message
        $this->get('session')->getFlashBag()->add('notice', $this->fhm_tools->trans('.admin.default.flash.ok'));

        return $this->redirect($this->fhm_tools->getUrl(array(), 'fhm_admin_' . $this->route));
    }
}