<?php
namespace Fhm\FhmBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * @Route("/api")
 */
class ApiController extends RefApiController
{
    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @Route
     * (
     *      path="/locale/{locale}",
     *      name="fhm_api_locale",
     *      defaults={"locale"=null}
     * )
     */
    public function localeAction(Request $request, $locale)
    {
        if ($locale) {
            $this->get('session')->set('_locale', $locale);
        }
        if ($this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')) {
            $this->get('session')->set('_localeAdmin', $locale);
        }

        return $this->redirect($this->getLastRoute() ? $this->getLastRoute() : $this->generateUrl('project_home'));
    }
}