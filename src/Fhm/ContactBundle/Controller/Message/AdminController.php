<?php
namespace Fhm\ContactBundle\Controller\Message;

use Fhm\ContactBundle\Form\Type\Admin\CreateType;
use Fhm\ContactBundle\Form\Type\Admin\UpdateType;
use Fhm\FhmBundle\Controller\RefAdminController as FhmController;
use Fhm\FhmBundle\Form\Handler\Admin\CreateHandler;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * @Route("/admin/contactmessage")
 * ---------------------------------------------
 * Class AdminController
 * @package Fhm\ContactBundle\Controller\Message
 */
class AdminController extends FhmController
{
    /**
     * AdminController constructor.
     */
    public function __construct()
    {
        self::$repository = "FhmContactBundle:ContactMessage";
        self::$source = "fhm";
        self::$domain = "FhmContactBundle";
        self::$translation = "contact.message";
        self::$route = "contact_message";
        self::$form = new \stdClass();
        self::$form->createType    = CreateType::class;
        self::$form->createHandler = CreateHandler::class;
        self::$form->updateType    = UpdateType::class;
    }
    /**
     * @Route
     * (
     *      path="/",
     *      name="fhm_admin_contact_message"
     * )
     * @Template("::FhmContact/Admin/Message/index.html.twig")
     */
    public function indexAction()
    {
        return parent::indexAction();
    }

    /**
     * @Route
     * (
     *      path="/detail/{id}",
     *      name="fhm_admin_contact_message_detail",
     *      requirements={"id"="[a-z0-9]*"}
     * )
     * @Template("::FhmContact/Admin/Message/detail.html.twig")
     */
    public function detailAction($id)
    {
        return parent::detailAction($id);
    }

    /**
     * @Route
     * (
     *      path="/delete/{id}",
     *      name="fhm_admin_contact_message_delete",
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
     *      name="fhm_admin_contact_message_undelete",
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
     *      path="/import",
     *      name="fhm_admin_contact_message_import"
     * )
     * @Template("::FhmContact/Admin/Message/import.html.twig")
     */
    public function importAction(Request $request)
    {
        return parent::importAction($request);
    }

    /**
     * @Route
     * (
     *      path="/export",
     *      name="fhm_admin_contact_message_export"
     * )
     * @Template("::FhmContact/Admin/Message/export.html.twig")
     */
    public function exportAction(Request $request)
    {
        return parent::exportAction($request);
    }
}