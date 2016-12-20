<?php
namespace Fhm\MenuBundle\Controller;

use Fhm\FhmBundle\Controller\RefFrontController as FhmController;
use Fhm\FhmBundle\Services\Tools;
use Fhm\MenuBundle\Document\Menu;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * @Route("/menu")
 * ----------------------------------
 * Class FrontController
 * @package Fhm\MenuBundle\Controller
 */
class FrontController extends FhmController
{
    /**
     * FrontController constructor.
     * @param string $repository
     * @param string $source
     * @param string $domain
     * @param string $translation
     * @param $document
     * @param string $route
     */
    public function __construct(
        $repository = "FhmMenuBundle:Menu",
        $source = "fhm",
        $domain = "FhmMenuBundle",
        $translation = "menu",
        $document = Menu::class,
        $route = 'menu'
    ) {
        self::$repository = $repository;
        self::$source = $source;
        self::$domain = $domain;
        self::$translation = $translation;
        self::$document = new $document();
        self::$class = get_class(self::$document);
        self::$route = $route;
    }

    /**
     * @Route
     * (
     *      path="/detail/{id}",
     *      name="fhm_menu_detail",
     *      requirements={"id"=".+"}
     * )
     * @Template("::FhmMenu/Front/detail.html.twig")
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
                return $this->get('project_default_controller_front')->templateAction($variables["name"], null);
            } else {
                return $this->redirect($this->generateUrl($route['_route'], $variables));
            }
        }

        return array(
            'document' => $document,
            'sites' => $this->get('fhm_tools')->dmRepository("FhmSiteBundle:Site")->getFrontIndex(),
            'modules' => $modules,
            'submenu' => $this->get('fhm_tools')->dmRepository("FhmMenuBundle:Menu")->getTree($document->getId())
        );
    }

    /**
     * @Route
     * (
     *      path="/{id}",
     *      name="fhm_menu_lite",
     *      requirements={"string"=".+"}
     * )
     * @Template("::FhmMenu/Front/detail.html.twig")
     */
    public function liteAction($id)
    {
        return $this->detailAction($id);
    }
}