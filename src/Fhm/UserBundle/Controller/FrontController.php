<?php
namespace Fhm\UserBundle\Controller;

use Fhm\FhmBundle\Form\Handler\Front\CreateHandler;
use Fhm\FhmBundle\Form\Handler\Front\UpdateHandler;
use Fhm\UserBundle\Document\User;
use Fhm\FhmBundle\Controller\RefFrontController as FhmController;
use Fhm\UserBundle\Form\Type\Front\CreateType;
use Fhm\UserBundle\Form\Type\Front\UpdateType;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * @Route("/user")
 */
class FrontController extends FhmController
{
    /**
     * FrontController constructor.
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
        self::$form = new \stdClass();
        self::$form->type = CreateType::class;
        self::$form->handler = CreateHandler::class;

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
        self::$form = new \stdClass();
        self::$form->type = CreateType::class;
        self::$form->handler = CreateHandler::class;

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
        self::$form = new \stdClass();
        self::$form->type = UpdateType::class;
        self::$form->handler = UpdateHandler::class;

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