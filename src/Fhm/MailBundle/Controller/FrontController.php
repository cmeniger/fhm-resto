<?php
namespace Fhm\MailBundle\Controller;

use Fhm\FhmBundle\Controller\RefFrontController as FhmController;
use Fhm\MailBundle\Document\Mail;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * @Route("/mail")
 */
class FrontController extends FhmController
{
    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct('Fhm', 'Mail', 'mail');
    }

    /**
     * @Route
     * (
     *      path="/",
     *      name="fhm_mail"
     * )
     * @Template("::FhmMail/Front/index.html.twig")
     */
    public function indexAction()
    {
        // For activate this route, delete next line
        throw $this->createNotFoundException($this->get('translator')->trans('fhm.error.route', array(), 'FhmFhmBundle'));

        return parent::indexAction();
    }

    /**
     * @Route
     * (
     *      path="/create",
     *      name="fhm_mail_create"
     * )
     * @Template("::FhmMail/Front/create.html.twig")
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
    *      name="fhm_mail_duplicate",
    *      requirements={"id"="[a-z0-9]*"}
    * )
    * @Template("::FhmMail/Front/create.html.twig")
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
    *      name="fhm_mail_update",
    *      requirements={"id"="[a-z0-9]*"}
    * )
    * @Template("::FhmMail/Front/update.html.twig")
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
    *      name="fhm_mail_detail",
    *      requirements={"id"=".+"}
    * )
    * @Template("::FhmMail/Front/detail.html.twig")
    */
    public function detailAction($id)
    {
        // For activate this route, delete next line
        throw $this->createNotFoundException($this->get('translator')->trans('fhm.error.route', array(), 'FhmFhmBundle'));

        return parent::detailAction($id);
    }

    /**
    * @Route
    * (
    *      path="/delete/{id}",
    *      name="fhm_mail_delete",
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
    *      name="fhm_mail_lite",
    *      requirements={"id"=".+"}
    * )
    * @Template("::FhmMail/Front/detail.html.twig")
    */
    public function liteAction($id)
    {
        return $this->detailAction($id);
    }
}