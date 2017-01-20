<?php
namespace Fhm\FhmBundle\Controller\Site;

use Fhm\FhmBundle\Controller\RefAdminController as FhmController;
use Fhm\FhmBundle\Form\Handler\Admin\CreateHandler;
use Fhm\FhmBundle\Form\Handler\Admin\UpdateHandler;
use Fhm\FhmBundle\Form\Type\Admin\Site\CreateType;
use Fhm\FhmBundle\Form\Type\Admin\Site\UpdateType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * @Route("/admin/site")
 * ---------------------------------------
 * Class AdminController
 * @package Fhm\FhmBundle\Controller\Site
 */
class AdminController extends FhmController
{
    /**
     * AdminController constructor.
     */
    public function __construct()
    {
        self::$repository = "FhmFhmBundle:Site";
        self::$source = "fhm";
        self::$domain = "FhmFhmSite";
        self::$translation = "site";
        self::$route = "site";
        self::$form = new \stdClass();
        self::$form->createType = CreateType::class;
        self::$form->createHandler = CreateHandler::class;
        self::$form->updateType = UpdateType::class;
        self::$form->updateHandler = UpdateHandler::class;
    }

    /**
     * @Route
     * (
     *      path="/",
     *      name="fhm_admin_site"
     * )
     * @Template("::FhmFhm/Site/Admin/index.html.twig")
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
     * @Template("::FhmFhm/Site/Admin/create.html.twig")
     */
    public function createAction(Request $request)
    {
        $response = parent::createAction($request);
        $objects = $this->get('fhm_tools')->dmRepository(self::$repository)->findAll();
        if (count($objects) == 1) {
            $object = $objects[0];
            $object->setDefault(true);
            $object->setActive(true);
            $this->get('fhm_tools')->dmPersist($object);
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
     * @Template("::FhmFhm/Site/Admin/detail.html.twig")
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
        $object = $this->get('fhm_tools')->dmRepository(self::$repository)->getDefault();
        if ($object) {
            return $this->redirect(
                $this->getUrl(array('id' => $object->getId()), 'fhm_admin_site_update')
            );
        } else {
            return $this->redirect($this->getUrl(array(), 'fhm_admin_site'));
        }
    }

    /**
     * @Route
     * (
     *      path="/update/{id}",
     *      name="fhm_admin_site_update",
     *      requirements={"id"="[a-z0-9]*"}
     * )
     * @Template("::FhmFhm/Site/Admin/update.html.twig")
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
     * @Template("::FhmFhm/Site/Admin/create.html.twig")
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
        $object = $this->get('fhm_tools')->dmRepository(self::$repository)->find($id);
        if ($object && $object->getDefault()) {
            throw new HttpException(403, $this->trans(self::$source.'.error.forbidden'));
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
        $object = $this->get('fhm_tools')->dmRepository(self::$repository)->find($id);
        if ($object && $object->getDefault()) {
            throw new HttpException(403, $this->trans(self::$source.'.error.forbidden'));
        }

        return parent::deactivateAction($id);
    }

    /**
     * @Route
     * (
     *      path="/import",
     *      name="fhm_admin_site_import"
     * )
     * @Template("::FhmFhm/Site/Admin/import.html.twig")
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
     * @Template("::FhmFhm/Site/Admin/export.html.twig")
     */
    public function exportAction(Request $request)
    {
        return parent::exportAction($request);
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
        $object = $this->get('fhm_tools')->dmRepository(self::$repository)->find($id);
        // ERROR - Unknown
        if ($object == "") {
            throw $this->createNotFoundException($this->trans(self::$translation.'.error.unknown'));
        }
        if (!$this->get('security.authorization_checker')->isGranted('ROLE_SUPER_ADMIN')) {
            throw new HttpException(403, $this->trans(self::$translation.'.error.forbidden'));
        }
        // Deactivate
        $this->get('fhm_tools')->dmRepository(self::$repository)->resetDefault();
        $object->setDefault(true);
        $object->setActive(true);
        $this->get('fhm_tools')->dmPersist($object);
        // Message
        $this->get('session')->getFlashBag()->add('notice', $this->trans(self::$translation.'.admin.default.flash.ok'));

        return $this->redirect($this->getUrl(array(), 'fhm_admin_'.self::$route));
    }
}