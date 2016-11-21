<?php
namespace Fhm\MenuBundle\Controller;

use Fhm\FhmBundle\Controller\RefApiController as FhmController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Fhm\MenuBundle\Document\Menu;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * @Route("/api/menu")
 */
class ApiController extends FhmController
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
     *      name="fhm_api_menu"
     * )
     * @Template("::FhmMenu/Api/index.html.twig")
     */
    public function indexAction()
    {
        return parent::indexAction();
    }

    /**
     * @Route
     * (
     *      path="/detail/{template}/{id}",
     *      name="fhm_api_menu_detail",
     *      requirements={"id"=".+"},
     *      defaults={"id"=null}
     * )
     */
    public function detailAction($template, $id)
    {
        $response = $this->get('fhm_cache')->getResponseCache(0, 0, true);
        if(is_null($id))
        {
            $site = $this->dmRepository("FhmSiteBundle:Site")->getDefault();
            $id   = ($site && $site->getMenu()) ? $site->getMenu()->getId() : null;
            if(is_null($id))
            {
                return $this->render(
                    "::FhmMenu/Template/" . $template . ".html.twig",
                    array(
                        'document' => null,
                        'tree'     => null,
                        'instance' => $this->instanceData(),
                    ),
                    $response
                );
            }
        }
        $document = $this->dmRepository()->getById($id);
        $document = ($document) ? $document : $this->dmRepository()->getByAlias($id);
        $document = ($document) ? $document : $this->dmRepository()->getByName($id);
        $instance = $this->instanceData($document);
        // ERROR - unknown
        if($document == "")
        {
            throw $this->createNotFoundException($this->get('translator')->trans('menu.error.unknown', array(), 'FhmMenuBundle'));
        }
        // ERROR - Forbidden
        elseif(!$instance->user->admin && ($document->getDelete() || !$document->getActive()))
        {
            throw new HttpException(403, $this->get('translator')->trans('menu.error.forbidden', array(), 'FhmMenuBundle'));
        }
        // Change grouping
        if($instance->grouping->different && $document->getGrouping())
        {
            $this->get($this->getParameters("grouping", "fhm_fhm"))->setGrouping($document->getFirstGrouping());
        }

        return $this->render(
            "::FhmMenu/Template/" . $template . ".html.twig",
            array(
                'document' => $document,
                'tree'     => $this->dmRepository()->getTree($document->getId()),
                'instance' => $instance,
            ),
            $response
        );
    }

    /**
     * @Route
     * (
     *      path="/autocomplete/",
     *      name="fhm_api_menu_autocomplete"
     * )
     * @Template("::FhmMenu/Api/autocomplete.html.twig")
     */
    public function autocompleteAction(Request $request)
    {
        return parent::autocompleteAction($request);
    }

    /**
     * @Route
     * (
     *      path="/addmodule",
     *      name="fhm_api_menu_addmodule"
     * )
     */
    public function addmoduleAction(Request $request)
    {
        $datas = $request->get('FhmAdd');
        $data  = array();
        if($datas['module'] != '' && $datas['data'] != '')
        {
            if($datas['module'] == "newsGroup")
            {
                $documentMenu = $this->dmRepository('FhmNewsBundle:' . ucfirst($datas['module']))->find($datas['data']);
            }
            else
            {
                $documentMenu = $this->dmRepository('Fhm' . ucfirst($datas['module']) . 'Bundle:' . ucfirst($datas['module']))->find($datas['data']);
            }
            $data['link']   = utf8_encode($this->_getRoute($datas['module'], $documentMenu));
            $data['module'] = utf8_encode($datas['module']);
            $data['id']     = utf8_encode($datas['data']);
        }
        $response = new JsonResponse();

        return $response->setData($data);
    }


    private function _getRoute($module, $document)
    {
        if($module === "media")
        {
            $route = $this->get($this->getParameters('service','fhm_media'))->setDocument($document)->getPathWeb();
        }
        else
        {
            $route = '/' . $module . '/detail/' . $document->getAlias();
        }

        return $route;
    }
}