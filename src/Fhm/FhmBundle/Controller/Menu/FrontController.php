<?php
namespace Fhm\FhmBundle\Controller\Menu;

use Fhm\FhmBundle\Controller\RefFrontController as FhmController;
use Fhm\FhmBundle\Document\Menu;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * @Route("/menu")
 * ----------------------------------
 * Class FrontController
 * @package Fhm\FhmBundle\Controller\Menu
 */
class FrontController extends FhmController
{
    /**
     * FrontController constructor.
     */
    public function __construct()
    {
        self::$repository = "FhmFhmBundle:Menu";
        self::$source = "fhm";
        self::$domain = "FhmFhmMenu";
        self::$translation = "menu";
        self::$class = Menu::class;
        self::$route = "menu";
    }

    /**
     * @Route
     * (
     *      path="/detail/{id}",
     *      name="fhm_menu_detail",
     *      requirements={"id"=".+"}
     * )
     * @Template("::FhmFhm/Menu/Front/detail.html.twig")
     */
    public function detailAction($id)
    {
        $parent = parent::detailAction($id);
        $session = $this->get('session');
        $document = $parent['document'];
        $modules = '';
        if ($document->getRoute()) {
            $route = $this->get('router')->match($document->getRoute());
            $variables = array();
            foreach ($route as $key => $value) {
                if (substr($key, 0, 1) !== '_') {
                    $variables[$key] = $value;
                }
            }
            $session->set('menu', $id);
            if ($route['_route'] == "project_template") {
                return $this->redirectToRoute('project_template', array('name'=>$variables["name"]));
            } else {
                return $this->redirect($this->generateUrl($route['_route'], $variables));
            }
        }

        return array(
            'document' => $document,
            'sites' => $this->get('fhm_tools')->dmRepository("FhmFhmBundle:Site")->getFrontIndex(),
            'modules' => $modules,
            'submenu' => $this->get('fhm_tools')->dmRepository("FhmFhmBundle:Menu")->getTree($document->getId())
        );
    }

    /**
     * @Route
     * (
     *      path="/{id}",
     *      name="fhm_menu_lite",
     *      requirements={"string"=".+"}
     * )
     * @Template("::FhmFhm/Menu/Front/detail.html.twig")
     */
    public function liteAction($id)
    {
        return $this->detailAction($id);
    }
}