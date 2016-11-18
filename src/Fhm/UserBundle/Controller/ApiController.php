<?php

namespace Fhm\UserBundle\Controller;

use Fhm\FhmBundle\Controller\RefApiController as FhmController;
use Fhm\UserBundle\Document\User;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * @Route("/api/user")
 */
class ApiController extends FhmController
{
    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct('Fhm', 'User', 'user');
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