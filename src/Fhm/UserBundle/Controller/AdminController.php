<?php

namespace Fhm\UserBundle\Controller;

use Fhm\FhmBundle\Controller\RefAdminController as FhmController;
use Fhm\FhmBundle\Services\Tools;
use Fhm\UserBundle\Document\User;
use Fhm\UserBundle\Form\Type\Admin\PasswordType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * @Route("/admin/user", service ="fhm_user_controller_admin")
 */
class AdminController extends FhmController
{
    /**
     * Constructor
     */
    public function __construct(Tools $tools)
    {
        $this->setFhmTools($tools);
        parent::__construct('Fhm', 'User', 'user');
    }

    /**
     * @Route
     * (
     *      path="/",
     *      name="fhm_admin_user"
     * )
     * @Template("::FhmUser/Admin/index.html.twig")
     */
    public function indexAction()
    {
        return parent::indexAction();
    }

    /**
     * @Route
     * (
     *      path="/create",
     *      name="fhm_admin_user_create"
     * )
     * @Template("::FhmUser/Admin/create.html.twig")
     */
    public function createAction(Request $request)
    {
        return parent::createAction($request);
    }

    /**
     * @Route
     * (
     *      path="/duplicate/{id}",
     *      name="fhm_admin_user_duplicate",
     *      requirements={"id"="[a-z0-9]*"}
     * )
     * @Template("::FhmUser/Admin/create.html.twig")
     */
    public function duplicateAction(Request $request, $id)
    {
        return parent::duplicateAction($request, $id);
    }

    /**
     * @Route
     * (
     *      path="/update/{id}",
     *      name="fhm_admin_user_update",
     *      requirements={"id"="[a-z0-9]*"}
     * )
     * @Template("::FhmUser/Admin/update.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        return parent::updateAction($request, $id);
    }

    /**
     * @Route
     * (
     *      path="/detail/{id}",
     *      name="fhm_admin_user_detail",
     *      requirements={"id"="[a-z0-9]*"}
     * )
     * @Template("::FhmUser/Admin/detail.html.twig")
     */
    public function detailAction($id)
    {
        $document = $this->fhm_tools->dmRepository()->find($id);
        $form = $this->createForm(PasswordType::class, $document);

        return array_merge(array('formPassword' => $form->createView()), parent::detailAction($id));
    }

    /**
     * @Route
     * (
     *      path="/delete/{id}",
     *      name="fhm_admin_user_delete",
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
     *      name="fhm_admin_user_undelete",
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
     *      name="fhm_admin_user_activate",
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
     *      name="fhm_admin_user_deactivate",
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
     *      name="fhm_admin_user_import"
     * )
     * @Template("::FhmUser/Admin/import.html.twig")
     */
    public function importAction(Request $request)
    {
        return parent::importAction($request);
    }

    /**
     * @Route
     * (
     *      path="/export",
     *      name="fhm_admin_user_export"
     * )
     * @Template("::FhmUser/Admin/export.html.twig")
     */
    public function exportAction(Request $request)
    {
        return parent::exportAction($request);
    }

    /**
     * @Route
     * (
     *      path="/grouping",
     *      name="fhm_admin_user_grouping"
     * )
     */
    public function groupingAction(Request $request)
    {
        return parent::groupingAction($request);
    }

    /**
     * @Route
     * (
     *      path="/roles",
     *      name="fhm_admin_user_roles"
     * )
     */
    public function rolesAction(Request $request)
    {
        $roles = json_decode($request->get('list'));
        $document = $this->fhm_tools->dmRepository()->find($request->get('id'));
        $document->setRoles(array());
        foreach ($roles as $key => $role) {
            $document->addRole($role->id);
        }
        $this->fhm_tools->dmPersist($document);

        return new Response();
    }

    /**
     * @Route
     * (
     *      path="/password/{id}",
     *      name="fhm_admin_user_password",
     *      requirements={"id"="[a-z0-9]*"}
     * )
     * @Template("::FhmUser/Admin/detail.html.twig")
     */
    public function passwordAction(Request $request, $id)
    {
        $document = $this->fhm_tools->dmRepository()->find($id);
        $classHandler = 'Fhm\\UserBundle\\Form\\Handler\\Admin\\PasswordHandler';
        $form = $this->createForm(PasswordType::class, $document);
        $handler = new $classHandler($form, $request);
        $process = $handler->process();
        if ($process) {
            // Persist
            $userManager = $this->get('fos_user.user_manager');
            $userManager->updateUser($document);
            // Message
            $this->get('session')->getFlashBag()->add(
                'notice',
                $this->get('translator')->trans(
                    $this->translation[1].'.admin.detail.password.flash.ok',
                    array(),
                    $this->translation[0]
                )
            );

            return $this->redirect($this->generateUrl('fhm_admin_'.$this->route.'_detail', array('id' => $id)));
        }

        return array_merge(array('formPassword' => $form->createView()), parent::detailAction($id));
    }
}