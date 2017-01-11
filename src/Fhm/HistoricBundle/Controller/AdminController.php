<?php
namespace Fhm\HistoricBundle\Controller;

use Fhm\FhmBundle\Controller\RefAdminController;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * @Route("/admin/historic")
 * --------------------------------------
 * Class AdminController
 * @package Fhm\HistoricBundle\Controller
 */
class AdminController extends RefAdminController
{

    /**
     * @Route
     * (
     *      path="/",
     *      name="fhm_admin_historic"
     * )
     * @Template("::FhmHistoric/Admin/index.html.twig")
     */
    public function indexAction()
    {
        return parent::indexAction();
    }

    /**
     * @Route
     * (
     *      path="/save",
     *      name="fhm_admin_historic_save"
     * )
     */
    public function saveAction(Request $request)
    {
    }

    /**
     * @Route
     * (
     *      path="/restore/{id}",
     *      name="fhm_admin_historic_restore",
     *      requirements={"id"="[a-z0-9]*"}
     * )
     */
    public function restoreAction(Request $request, $id)
    {
    }

}
