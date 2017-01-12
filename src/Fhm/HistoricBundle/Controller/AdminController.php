<?php
namespace Fhm\HistoricBundle\Controller;

use Fhm\FhmBundle\Controller\RefAdminController;
use Fhm\HistoricBundle\Document\Historic;
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
     * AdminController constructor.
     */
    public function __construct()
    {
        self::$repository = "FhmHistoricBundle:Historic";
        self::$source = "fhm";
        self::$domain = "FhmHistoricBundle";
        self::$translation = "historic";
        self::$class = Historic::class;
        self::$route = 'historic';
    }
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
    public function restoreAction($id)
    {
        $this->get('fhm.historic.manager')->restore($id);
        return $this->redirect($this->getUrl('fhm_admin_historic'));
    }
    /**
     * @Route
     * (
     *      path="/detail/{id}",
     *      name="fhm_admin_historic_detail",
     *      requirements={"id"="[a-z0-9]*"}
     * )
     * @Template("::FhmHistoric/Admin/detail.html.twig")
     */
    public function detailAction($id)
    {
        return parent::detailAction($id);
    }
}
