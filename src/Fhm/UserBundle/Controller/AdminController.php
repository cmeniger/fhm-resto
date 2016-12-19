<?php
namespace Fhm\UserBundle\Controller;

use Fhm\FhmBundle\Controller\RefAdminController as FhmController;
use Fhm\FhmBundle\Form\Handler\Admin\CreateHandler;
use Fhm\FhmBundle\Form\Handler\Admin\UpdateHandler;
use Fhm\UserBundle\Document\User;
use Fhm\UserBundle\Form\Handler\Admin\PasswordHandler;
use Fhm\UserBundle\Form\Type\Admin\CreateType;
use Fhm\UserBundle\Form\Type\Admin\PasswordType;
use Fhm\UserBundle\Form\Type\Admin\UpdateType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * @Route("/admin/user")
 */
class AdminController extends FhmController
{
    /**
     * AdminController constructor.
     */
    public function __construct()
    {
        self::$repository = "FhmUserBundle:User";
        self::$source = "fhm";
        self::$domain = "FhmUserBundle";
        self::$translation = "user";
        self::$document = new User();
        self::$class = get_class(self::$document);
        self::$route = 'user';
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
        self::$form = new \stdClass();
        self::$form->type = CreateType::class;
        self::$form->handler = CreateHandler::class;

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
        self::$form = new \stdClass();
        self::$form->type = CreateType::class;
        self::$form->handler = CreateHandler::class;

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
        self::$form = new \stdClass();
        self::$form->type = UpdateType::class;
        self::$form->handler = UpdateHandler::class;

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
        $document = $this->get('fhm_tools')->dmRepository(self::$repository)->find($id);
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
     *      path="/roles",
     *      name="fhm_admin_user_roles"
     * )
     */
    public function rolesAction(Request $request)
    {
        $document = $this->get('fhm_tools')->dmRepository(self::$repository)->find($request->get('id'));
        $roles = json_decode($request->get('list'));
        $document->setRoles(array());
        foreach ($roles as $role) {
            $document->addRole($role->id);
        }
        $this->get('fhm_tools')->dmPersist($document);

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
        $document = $this->get('fhm_tools')->dmRepository(self::$repository)->find($id);
        $classHandler = PasswordHandler::class;
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
                    self::$translation.'.admin.detail.password.flash.ok',
                    array(),
                    self::$domain
                )
            );

            return $this->redirect($this->generateUrl('fhm_admin_'.self::$route.'_detail', array('id' => $id)));
        }

        return array_merge(array('formPassword' => $form->createView()), parent::detailAction($id));
    }
}