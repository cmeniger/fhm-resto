<?php
namespace Fhm\MailBundle\Controller;

use Fhm\FhmBundle\Controller\RefAdminController as FhmController;
use Fhm\MailBundle\Document\Mail;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * @Route("/admin/mail", service="fhm_mail_controller_admin")
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
        parent::__construct('Fhm', 'Mail', 'mail');
        $this->sort = array('date_create', 'desc');
    }

    /**
     * @Route
     * (
     *      path="/",
     *      name="fhm_admin_mail"
     * )
     * @Template("::FhmMail/Admin/index.html.twig")
     */
    public function indexAction()
    {
        return parent::indexAction();
    }

    /**
     * @Route
     * (
     *      path="/create",
     *      name="fhm_admin_mail_create"
     * )
     * @Template("::FhmMail/Admin/create.html.twig")
     */
    public function createAction(Request $request)
    {
        // ERROR - Unknown route
        if (!$this->fhm_tools->routeExists($this->source.'_admin_'.$this->route) || !$this->fhm_tools->routeExists(
                $this->source.'_admin_'.$this->route.'_create'
            )
        ) {
            throw $this->createNotFoundException($this->fhm_tools->trans('fhm.error.route', array(), 'FhmFhmBundle'));
        }
        $document = $this->document;
        $instance = $this->fhm_tools->instanceData();
        $classType = $this->form->type->create;
        $classHandler = $this->form->handler->create;
        $form = $this->createForm($classType, $document);
        $handler = new $classHandler($form, $request);
        $process = $handler->process();
        if ($process) {
            $data = $request->get($form->getName());
            // Envoi email
            $this->container->get('fhm_mail')->adminMessage
            (
                array
                (
                    'to' => $data['to'],
                    'object' => $data['object'],
                    'body' => $data['body'],
                )
            );
            // Message
            $this->get('session')->getFlashBag()->add('notice', $this->fhm_tools->trans('.admin.create.flash.ok'));

            return $this->redirect($this->fhm_tools->getUrl($this->source.'_admin_'.$this->route));
        }

        return array(
            'form' => $form->createView(),
            'instance' => $instance,
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
                    'link' => $this->fhm_tools->getUrl($this->source.'_admin_'.$this->route),
                    'text' => $this->fhm_tools->trans('.admin.index.breadcrumb'),
                ),
                array(
                    'link' => $this->fhm_tools->getUrl($this->source.'_admin_'.$this->route.'_create'),
                    'text' => $this->fhm_tools->trans('.admin.create.breadcrumb'),
                    'current' => true,
                ),
            ),
        );
    }

    /**
     * @Route
     * (
     *      path="/duplicate/{id}",
     *      name="fhm_admin_mail_duplicate",
     *      requirements={"id"="[a-z0-9]*"}
     * )
     * @Template("::FhmMail/Admin/create.html.twig")
     */
    public function duplicateAction(Request $request, $id)
    {
        // For activate this route, delete next line
        throw $this->createNotFoundException($this->fhm_tools->trans('fhm.error.route', array(), 'FhmFhmBundle'));
    }

    /**
     * @Route
     * (
     *      path="/update/{id}",
     *      name="fhm_admin_mail_update",
     *      requirements={"id"="[a-z0-9]*"}
     * )
     * @Template("::FhmMail/Admin/update.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        // For activate this route, delete next line
        throw $this->createNotFoundException($this->fhm_tools->trans('fhm.error.route', array(), 'FhmFhmBundle'));
    }

    /**
     * @Route
     * (
     *      path="/detail/{id}",
     *      name="fhm_admin_mail_detail",
     *      requirements={"id"="[a-z0-9]*"}
     * )
     * @Template("::FhmMail/Admin/detail.html.twig")
     */
    public function detailAction($id)
    {
        return parent::detailAction($id);
    }

    /**
     * @Route
     * (
     *      path="/delete/{id}",
     *      name="fhm_admin_mail_delete",
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
     *      name="fhm_admin_mail_undelete",
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
     *      name="fhm_admin_mail_activate",
     *      requirements={"id"="[a-z0-9]*"}
     * )
     */
    public function activateAction($id)
    {
        // For activate this route, delete next line
        throw $this->createNotFoundException($this->fhm_tools->trans('fhm.error.route', array(), 'FhmFhmBundle'));

        return parent::activateAction($id);
    }

    /**
     * @Route
     * (
     *      path="/deactivate/{id}",
     *      name="fhm_admin_mail_deactivate",
     *      requirements={"id"="[a-z0-9]*"}
     * )
     */
    public function deactivateAction($id)
    {
        // For activate this route, delete next line
        throw $this->createNotFoundException($this->fhm_tools->trans('fhm.error.route', array(), 'FhmFhmBundle'));
    }

    /**
     * @Route
     * (
     *      path="/import",
     *      name="fhm_admin_mail_import"
     * )
     * @Template("::FhmMail/Admin/import.html.twig")
     */
    public function importAction(Request $request)
    {
        // For activate this route, delete next line
        throw $this->createNotFoundException($this->fhm_tools->trans('fhm.error.route', array(), 'FhmFhmBundle'));
    }

    /**
     * @Route
     * (
     *      path="/export",
     *      name="fhm_admin_mail_export"
     * )
     * @Template("::FhmMail/Admin/export.html.twig")
     */
    public function exportAction(Request $request)
    {
        // For activate this route, delete next line
        throw $this->createNotFoundException($this->fhm_tools->trans('fhm.error.route', array(), 'FhmFhmBundle'));
    }

    /**
     * @Route
     * (
     *      path="/grouping",
     *      name="fhm_admin_mail_grouping"
     * )
     */
    public function groupingAction(Request $request)
    {
        // For activate this route, delete next line
        throw $this->createNotFoundException($this->fhm_tools->trans('fhm.error.route', array(), 'FhmFhmBundle'));
    }

    /**
     * @Route
     * (
     *      path="/model",
     *      name="fhm_admin_mail_model"
     * )
     * @Template("::FhmMail/Admin/model.html.twig")
     */
    public function modelAction(Request $request)
    {
        return array(
            'instance' => $this->fhm_tools->instanceData(),
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
                    'link' => $this->fhm_tools->getUrl($this->source.'_admin_'.$this->route),
                    'text' => $this->fhm_tools->trans('.admin.index.breadcrumb'),
                ),
                array(
                    'link' => $this->fhm_tools->getUrl($this->source.'_admin_'.$this->route.'_model'),
                    'text' => $this->fhm_tools->trans('.admin.model.breadcrumb'),
                    'current' => true,
                ),
            ),
        );
    }
}