<?php
namespace Fhm\FhmBundle\Controller;

use Fhm\FhmBundle\Document\Fhm;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * @Route("/admin")
 * ---------------------------------
 * Class AdminController
 * @package Fhm\FhmBundle\Controller
 */
class AdminController extends RefAdminController
{
    /**
     * AdminController constructor.
     */
    public function __construct()
    {
        self::$repository = "FhmFhmBundle:Fhm";
        self::$source = "fhm";
        self::$domain = "FhmFhmBundle";
        self::$translation = "fhm";
        self::$class = Fhm::class;
        self::$route = 'fhm';
    }
    /**
     * @Route(path="/", name="fhm_admin")
     */
    public function adminAction()
    {
        return $this->render(
            'FhmFhm/Admin/admin.html.twig',
            array(
                'breadcrumbs' => $this->get('fhm_tools')->generateBreadcrumbs(
                    array(
                        'domain' => self::$domain,
                        '_route' => $this->get('request_stack')->getCurrentRequest()->get('_route'),
                    )
                ),
            )
        );
    }

    /**
     * @Route(path="/cache/{env}", name="fhm_admin_cache", defaults={"env"="prod"})
     */
    public function cacheAction($env)
    {
        exec(
            "sudo rm -rf ".__DIR__."/../../../../app/cache ".__DIR__."/../../../../app/logs".
            "&& php ".__DIR__."/../../../../app/console cache:clear --env=".$env.
            "&& sudo chmod -R 777 ".__DIR__."/../../../../app/cache ".__DIR__."/../../../../app/logs"
        );

        return new JsonResponse(
            array(
                'error' => '',
                'status' => 200,
            )
        );
    }
}