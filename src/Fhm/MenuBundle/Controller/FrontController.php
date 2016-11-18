<?php
namespace Fhm\MenuBundle\Controller;

use Fhm\FhmBundle\Controller\RefFrontController as FhmController;
use Fhm\MenuBundle\Document\Menu;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * @Route("/menu")
 */
class FrontController extends FhmController
{
    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct('Fhm', 'Menu', 'menu');
    }

    /**
     * @Route
     * (
     *      path="/",
     *      name="fhm_menu"
     * )
     * @Template("::FhmMenu/Front/index.html.twig")
     */
    public function indexAction()
    {
        // For activate this route, delete next line
        throw $this->createNotFoundException($this->get('translator')->trans('fhm.error.route', array(), 'FhmFhmBundle'));

        return parent::indexAction();
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
        $parent   = parent::detailAction($id);
        $session  = $this->container->get('session');
        $document = $parent['document'];
        $modules  = '';
        if($document->getRoute())
        {
            $route     = $this->container->get('router')->match($document->getRoute());
            $variables = array();
            foreach($route as $key => $value)
            {
                if(substr($key, 0, 1) !== '_')
                {
                    $variables[$key] = $value;
                }
            }
            $session->set('menu', $id);
            if($route['_route'] == "project_template")
            {
                $controller = new \Project\DefaultBundle\Controller\FrontController();
                $controller->setContainer($this->container);

                return $controller->templateAction($variables["name"], null);
            }
            else
            {
                return $this->redirect($this->generateUrl($route['_route'], $variables));
            }
        }

        return array(
            'document' => $document,
            'sites'    => $this->dmRepository("FhmSiteBundle:Site")->getFrontIndex(),
            'modules'  => $modules,
            'submenu'  => $this->dmRepository("FhmMenuBundle:Menu")->getTree($document->getId()),
            'instance' => $parent['instance']
        );
    }

    /**
     * @Route
     * (
     *      path="/create",
     *      name="fhm_menu_create"
     * )
     * @Template("::FhmMenu/Front/create.html.twig")
     */
    public function createAction(Request $request)
    {
        // For activate this route, delete next line
        throw $this->createNotFoundException($this->get('translator')->trans('fhm.error.route', array(), 'FhmFhmBundle'));

        return parent::createAction($request);
    }

    /**
     * @Route
     * (
     *      path="/update/{id}",
     *      name="fhm_menu_update",
     *      requirements={"id"="[a-z0-9]*"}
     * )
     * @Template("::FhmMenu/Front/update.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        // For activate this route, delete next line
        throw $this->createNotFoundException($this->get('translator')->trans('fhm.error.route', array(), 'FhmFhmBundle'));

        return parent::updateAction($request, $id);
    }

    /**
     * @Route
     * (
     *      path="/delete/{id}",
     *      name="fhm_menu_delete",
     *      requirements={"id"="[a-z0-9]*"}
     * )
     */
    public function deleteAction($id)
    {
        // For activate this route, delete next line
        throw $this->createNotFoundException($this->get('translator')->trans('fhm.error.route', array(), 'FhmFhmBundle'));

        return parent::deleteAction($id);
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