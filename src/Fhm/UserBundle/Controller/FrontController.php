<?php

namespace Fhm\UserBundle\Controller;

use Fhm\FhmBundle\Services\Tools;
use Fhm\UserBundle\Document\User;
use Fhm\FhmBundle\Controller\RefFrontController as FhmController;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * @Route("/user", service ="fhm_user_controller_front")
 */
class FrontController extends FhmController
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
     *      name="fhm_user"
     * )
     * @Template("::FhmUser/Front/index.html.twig")
     */
    public function indexAction()
    {
        return parent::indexAction();
    }

    /**
     * @Route
     * (
     *      path="/detail/{id}",
     *      name="fhm_user_detail",
     *      requirements={"id"=".+"}
     * )
     * @Template("::FhmUser/Front/detail.html.twig")
     */
    public function detailAction($id)
    {
        return parent::detailAction($id);
    }

    /**
     * @Route
     * (
     *      path="/create",
     *      name="fhm_user_create"
     * )
     * @Template("::FhmUser/Front/create.html.twig")
     */
    public function createAction(Request $request)
    {
        return parent::createAction($request);
    }

    /**
     * @Route
     * (
     *      path="/duplicate/{id}",
     *      name="fhm_user_duplicate",
     *      requirements={"id"="[a-z0-9]*"}
     * )
     * @Template("::FhmUser/Front/create.html.twig")
     */
    public function duplicateAction(Request $request, $id)
    {
        return parent::duplicateAction($request, $id);
    }

    /**
     * @Route
     * (
     *      path="/update/{id}",
     *      name="fhm_user_update",
     *      requirements={"id"="[a-z0-9]*"}
     * )
     * @Template("::FhmUser/Front/update.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        return parent::updateAction($request, $id);
    }

    /**
     * @Route
     * (
     *      path="/delete/{id}",
     *      name="fhm_user_delete",
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
     *      path="/check/facebook",
     *      name="fhm_user_check_facebook"
     * )
     */
    public function checkFacebookAction()
    {
    }

    /**
     * @Route
     * (
     *      path="/check/twitter",
     *      name="fhm_user_check_twitter"
     * )
     */
    public function checkTwitterAction()
    {
    }

    /**
     * @Route
     * (
     *      path="/check/google",
     *      name="fhm_user_check_google"
     * )
     */
    public function checkGoogleAction()
    {
    }

    /**
     * @Route
     * (
     *      path="/{id}",
     *      name="fhm_user_lite",
     *      requirements={"id"=".+"}
     * )
     * @Template("::FhmUser/Front/detail.html.twig")
     */
    public function liteAction($id)
    {
        return $this->detailAction($id);
    }
}