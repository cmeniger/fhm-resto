<?php
namespace Fhm\MapPickerBundle\Controller;

use Fhm\FhmBundle\Controller\RefAdminController as FhmController;
use Fhm\MapPickerBundle\Document\MapPicker;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * @Route("/admin/mappicker", service="fhm_mappicker_controller_admin")
 */
class AdminController extends FhmController
{
    /**
     * AdminController constructor.
     *
     * @param \Fhm\FhmBundle\Services\Tools $tools
     */
    public function __construct(\Fhm\FhmBundle\Services\Tools $tools)
    {
        $this->setFhmTools($tools);
        parent::__construct('Fhm', 'MapPicker', 'mappicker');
    }

    /**
     * @Route
     * (
     *      path="/",
     *      name="fhm_admin_mappicker"
     * )
     * @Template("::FhmMapPicker/Admin/index.html.twig")
     */
    public function indexAction()
    {
        return parent::indexAction();
    }

    /**
     * @Route
     * (
     *      path="/create",
     *      name="fhm_admin_mappicker_create"
     * )
     * @Template("::FhmMapPicker/Admin/create.html.twig")
     */
    public function createAction(Request $request)
    {
        // ERROR - Unknown route
        if(!$this->fhm_tools->routeExists('fhm_admin_' . $this->route) || !$this->fhm_tools->routeExists('fhm_admin_' . $this->route . '_create'))
        {
            throw $this->createNotFoundException($this->fhm_tools->trans('fhm.error.route', array(), 'FhmFhmBundle'));
        }
        $document     = $this->document;
        $instance     = $this->fhm_tools->instanceData();
        $classType    = $this->form->type->create;
        $classHandler = $this->form->handler->create;
        $form         = $this->createForm(new $classType($instance, $document, $this->fhm_tools->getParameter('maps', 'fhm_mappicker')), $document);
        $handler      = new $classHandler($form, $request);
        $process      = $handler->process();
        if($process)
        {
            $data = $request->get($form->getName());
            // Persist
            $document->setUserCreate($this->getUser());
            $document->setAlias($this->fhm_tools->getAlias($document->getId(), $document->getName()));
            $this->fhm_tools->dmPersist($document);
            // Message
            $this->get('session')->getFlashBag()->add('notice', $this->fhm_tools->trans('.admin.create.flash.ok'));
            // Redirect
            $redirect = $this->redirect($this->fhm_tools->getUrl('fhm_admin_' . $this->route));
            $redirect = isset($data['submitSave']) ? $this->redirect($this->fhm_tools->getUrl('fhm_admin_' . $this->route . '_update', array('id' => $document->getId()))) : $redirect;
            $redirect = isset($data['submitDuplicate']) ? $this->redirect($this->fhm_tools->getUrl('fhm_admin_' . $this->route . '_duplicate', array('id' => $document->getId()))) : $redirect;
            $redirect = isset($data['submitNew']) ? $this->redirect($this->fhm_tools->getUrl('fhm_admin_' . $this->route . '_create')) : $redirect;
            $redirect = isset($data['submitConfig']) ? $this->redirect($this->fhm_tools->getUrl('fhm_admin_' . $this->route . '_detail', array('id' => $document->getId()))) : $redirect;

            return $redirect;
        }

        return array(
            'form'        => $form->createView(),
            'instance'    => $instance,
            'breadcrumbs' => array(
                array(
                    'link' => $this->fhm_tools->getUrl('project_home'),
                    'text' => $this->fhm_tools->trans('project.home.breadcrumb', array(), 'ProjectDefaultBundle'),
                ),
                array(
                    'link' => $this->fhm_tools->getUrl('fhm_admin'),
                    'text' => $this->fhm_tools->trans('fhm.admin.breadcrumb', array(), 'FhmFhmBundle'),
                ),
                array(
                    'link' => $this->fhm_tools->getUrl('fhm_admin_' . $this->route),
                    'text' => $this->fhm_tools->trans('.admin.index.breadcrumb'),
                ),
                array(
                    'link'    => $this->fhm_tools->getUrl('fhm_admin_' . $this->route . '_create'),
                    'text'    => $this->fhm_tools->trans('.admin.create.breadcrumb'),
                    'current' => true
                )
            )
        );
    }

    /**
     * @Route
     * (
     *      path="/update/{id}",
     *      name="fhm_admin_mappicker_update",
     *      requirements={"id"="[a-z0-9]*"}
     * )
     * @Template("::FhmMapPicker/Admin/update.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        // ERROR - Unknown route
        if(!$this->fhm_tools->routeExists('fhm_admin_' . $this->route) || !$this->fhm_tools->routeExists('fhm_admin_' . $this->route . '_update'))
        {
            throw $this->createNotFoundException($this->fhm_tools->trans('fhm.error.route', array(), 'FhmFhmBundle'));
        }
        $document     = $this->fhm_tools->dmRepository()->find($id);
        $instance     = $this->fhm_tools->instanceData($document);
        $classType    = $this->form->type->update;
        $classHandler = $this->form->handler->update;
        // ERROR - unknown
        if($document == "")
        {
            throw $this->createNotFoundException($this->fhm_tools->trans('.error.unknown'));
        }
        $form    = $this->createForm(new $classType($instance, $document, $this->fhm_tools->getParameter('maps', 'fhm_mappicker')), $document);
        $handler = new $classHandler($form, $request);
        $process = $handler->process($document, $this->fhm_tools->dm(), $this->bundle);
        if($process)
        {
            $data = $request->get($form->getName());
            // Persist
            $document->setUserUpdate($this->getUser());
            $document->setAlias($this->fhm_tools->getAlias($document->getId(), $document->getName()));
            $this->fhm_tools->dmPersist($document);
            // Message
            $this->get('session')->getFlashBag()->add('notice', $this->fhm_tools->trans('.admin.update.flash.ok'));
            // Redirect
            $redirect = $this->redirect($this->fhm_tools->getUrl('fhm_admin_' . $this->route));
            $redirect = isset($data['submitSave']) ? $this->redirect($this->fhm_tools->getUrl('fhm_admin_' . $this->route . '_update', array('id' => $document->getId()))) : $redirect;
            $redirect = isset($data['submitDuplicate']) ? $this->redirect($this->fhm_tools->getUrl('fhm_admin_' . $this->route . '_duplicate', array('id' => $document->getId()))) : $redirect;
            $redirect = isset($data['submitNew']) ? $this->redirect($this->fhm_tools->getUrl('fhm_admin_' . $this->route . '_create')) : $redirect;
            $redirect = isset($data['submitConfig']) ? $this->redirect($this->fhm_tools->getUrl('fhm_admin_' . $this->route . '_detail', array('id' => $document->getId()))) : $redirect;

            return $redirect;
        }

        return array(
            'document'    => $document,
            'form'        => $form->createView(),
            'instance'    => $instance,
            'breadcrumbs' => array(
                array(
                    'link' => $this->fhm_tools->getUrl('project_home'),
                    'text' => $this->fhm_tools->trans('project.home.breadcrumb', array(), 'ProjectDefaultBundle'),
                ),
                array(
                    'link' => $this->fhm_tools->getUrl('fhm_admin'),
                    'text' => $this->fhm_tools->trans('fhm.admin.breadcrumb', array(), 'FhmFhmBundle'),
                ),
                array(
                    'link' => $this->fhm_tools->getUrl('fhm_admin_' . $this->route),
                    'text' => $this->fhm_tools->trans('.admin.index.breadcrumb'),
                ),
                array(
                    'link' => $this->fhm_tools->getUrl('fhm_admin_' . $this->route . '_detail', array('id' => $id)),
                    'text' => $this->fhm_tools->trans('.admin.detail.breadcrumb', array('%name%' => $document->getName())),
                ),
                array(
                    'link'    => $this->fhm_tools->getUrl('fhm_admin_' . $this->route . '_update', array('id' => $id)),
                    'text'    => $this->fhm_tools->trans('.admin.update.breadcrumb'),
                    'current' => true
                )
            )
        );
    }

    /**
     * @Route
     * (
     *      path="/detail/{id}",
     *      name="fhm_admin_mappicker_detail",
     *      requirements={"id"="[a-z0-9]*"}
     * )
     * @Template("::FhmMapPicker/Admin/detail.html.twig")
     */
    public function detailAction($id)
    {
        $response            = parent::detailAction($id);
        $document            = $response['document'];
        $document->mappicker = $this->container->get('mappicker.' . $response['document']->getMap())->setDocument($response['document']);
        $document->sites     = $this->fhm_tools->dm()->getRepository('FhmSiteBundle:Site')->getFrontIndex('', 0, 0);
        foreach((array)$document->getZone() as $zone)
        {
            $document->addZone($zone['code'], $this->fhm_tools->dm()->getRepository('FhmSiteBundle:Site')->find($zone['site']));
        }
        $response['document'] = $document;

        return $response;
    }

    /**
     * @Route
     * (
     *      path="/duplicate/{id}",
     *      name="fhm_admin_mappicker_duplicate",
     *      requirements={"id"="[a-z0-9]*"}
     * )
     * @Template("::FhmMapPicker/Admin/create.html.twig")
     */
    public function duplicateAction(Request $request, $id)
    {
        return parent::duplicateAction($request, $id);
    }

    /**
     * @Route
     * (
     *      path="/delete/{id}",
     *      name="fhm_admin_mappicker_delete",
     *      requirements={"id"="[a-z0-9]*"}
     * )
     */
    public function deleteAction($id)
    {
        return parent::deleteAction($id);
    }

    /**
     * @Route
     * (
     *      path="/undelete/{id}",
     *      name="fhm_admin_mappicker_undelete",
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
     *      name="fhm_admin_mappicker_activate",
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
     *      name="fhm_admin_mappicker_deactivate",
     *      requirements={"id"="[a-z0-9]*"}
     * )
     */
    public function deactivateAction($id)
    {
        return parent::deactivateAction($id);
    }

    /**
     * @Route
     * (
     *      path="/import",
     *      name="fhm_admin_mappicker_import"
     * )
     * @Template("::FhmMapPicker/Admin/import.html.twig")
     */
    public function importAction(Request $request)
    {
        return parent::importAction($request);
    }

    /**
     * @Route
     * (
     *      path="/export",
     *      name="fhm_admin_mappicker_export"
     * )
     * @Template("::FhmMapPicker/Admin/export.html.twig")
     */
    public function exportAction(Request $request)
    {
        return parent::exportAction($request);
    }

    /**
     * @Route
     * (
     *      path="/grouping",
     *      name="fhm_admin_mappicker_grouping"
     * )
     */
    public function groupingAction(Request $request)
    {
        return parent::groupingAction($request);
    }

    /**
     * @Route
     * (
     *      path="/add",
     *      name="fhm_admin_mappicker_add"
     * )
     */
    public function addAction(Request $request)
    {
        $datas    = $request->get('FhmAdd');
        $document = $this->fhm_tools->dmRepository()->find($datas['id']);
        $document->addZone($datas['code'], $datas['site']);
        $this->fhm_tools->dmPersist($document);

        return $this->redirect($this->fhm_tools->getLastRoute());
    }

    /**
     * @Route
     * (
     *      path="/remove/{id}/{code}",
     *      name="fhm_admin_mappicker_remove",
     *      requirements={"id"="[a-z0-9]*", "code"="[0-9]*"}
     * )
     */
    public function removeAction($id, $code)
    {
        $document = $this->fhm_tools->dmRepository()->find($id);
        $document->removeZone($code);
        $this->fhm_tools->dmPersist($document);

        return $this->redirect($this->fhm_tools->getLastRoute());
    }
}