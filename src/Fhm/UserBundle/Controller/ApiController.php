<?php
namespace Fhm\UserBundle\Controller;

use Fhm\FhmBundle\Controller\RefApiController as FhmController;
use Fhm\UserBundle\Document\User;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * @Route("/api/user")
 * ----------------------------------
 * Class ApiController
 * @package Fhm\UserBundle\Controller
 */
class ApiController extends FhmController
{
    /**
     * ApiController constructor.
     */
    public function __construct()
    {
        self::$repository = "FhmUserBundle:User";
        self::$source = "fhm";
        self::$domain = "FhmUserBundle";
        self::$translation = "user";
        self::$class = User::class;
        self::$route = 'user';
    }

    /**
     * @Route
     * (
     *      path="/",
     *      name="fhm_api_user"
     * )
     * @Template("::FhmUser/Api/index.html.twig")
     */
    public function indexAction()
    {
        return parent::indexAction();
    }

    /**
     * @Route
     * (
     *      path="/autocomplete/",
     *      name="fhm_api_user_autocomplete"
     * )
     * @Template("::FhmUser/Api/autocomplete.html.twig")
     */
    public function autocompleteAction(Request $request)
    {
        return parent::autocompleteAction($request);
    }
}