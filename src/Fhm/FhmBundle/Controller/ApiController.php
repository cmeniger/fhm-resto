<?php
namespace Fhm\FhmBundle\Controller;

use Fhm\FhmBundle\Document\Fhm;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * @Route("/api")
 * ---------------------------------
 * Class ApiController
 * @package Fhm\FhmBundle\Controller
 */
class ApiController extends RefApiController
{
    /**
     * ApiController constructor.
     */
    public function __construct()
    {
        self::$repository = "FhmFhmBundle:Fhm";
        self::$source = "fhm";
        self::$domain = "FhmFhmBundle";
        self::$translation = "fhm";
        self::$document = new Fhm();
        self::$class = get_class(self::$document);
        self::$route = 'fhm';
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

        return $this->redirect(
            $this->get('fhm_tools')->getLastRoute($request) ? $this->get('fhm_tools')->getLastRoute(
                $request
            ) : $this->generateUrl('project_home')
        );
    }
}