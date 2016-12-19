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
        self::$document = new Fhm();
        self::$class = get_class(self::$document);
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
                'breadcrumbs' => array(
                    array(
                        'link' => $this->get('router')->generate('project_home'),
                        'text' => $this->get('translator')->trans(
                            'project.home.breadcrumb',
                            array(),
                            'ProjectDefaultBundle'
                        ),
                    ),
                    array(
                        'link' => $this->get('router')->generate('fhm_admin'),
                        'text' => $this->get('translator')->trans('fhm.admin.breadcrumb', array(), 'FhmFhmBundle'),
                        'current' => true,
                    ),
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