<?php
namespace Fhm\FhmBundle\Controller;

use Fhm\FhmBundle\Document\Fhm;
use Fhm\FhmBundle\Services\Tools;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * @Route("/admin", service="fhm_admin_controller_admin")
 */
class AdminController extends RefAdminController
{
    /**
     * Constructor
     */
    public function __construct(Tools $tools)
    {
        $this->setFhmTools($tools);
        parent::__construct();
    }

    /**
     * @Route
     * (
     *      path="/",
     *      name="fhm_admin"
     * )
     * @Template("::FhmFhm/Admin/admin.html.twig")
     */
    public function adminAction()
    {
        return array(
            'instance'    => $this->fhm_tools->instanceData(),
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
                    'link'    => $this->get('router')->generate('fhm_admin'),
                    'text'    => $this->get('translator')->trans('fhm.admin.breadcrumb', array(), 'FhmFhmBundle'),
                    'current' => true
                )
            )
        );
    }

    /**
     * @Route
     * (
     *      path="/cache/{env}",
     *      name="fhm_admin_cache",
     *      defaults={"env"="prod"}
     * )
     */
    public function cacheAction($env)
    {
        exec(
            "sudo rm -rf " . __DIR__ . "/../../../../app/cache " . __DIR__ . "/../../../../app/logs"
            . "&& php " . __DIR__ . "/../../../../app/console cache:clear --env=" . $env
            . "&& sudo chmod -R 777 " . __DIR__ . "/../../../../app/cache " . __DIR__ . "/../../../../app/logs"
        );

        return new JsonResponse(array(
            'error'  => '',
            'status' => 200
        ));
    }
}