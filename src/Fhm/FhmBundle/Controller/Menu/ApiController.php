<?php

namespace Fhm\FhmBundle\Controller\Menu;

use Fhm\FhmBundle\Controller\RefApiController as FhmController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * @Route("/api/menu")
 * ----------------------------------
 * Class ApiController
 *
 * @package Fhm\FhmBundle\Controller\Menu
 */
class ApiController extends FhmController
{
    /**
     * ApiController constructor.
     */
    public function __construct()
    {
        self::$repository  = "FhmFhmBundle:Menu";
        self::$source      = "fhm";
        self::$domain      = "FhmFhmMenu";
        self::$translation = "menu";
        self::$route       = "menu";
    }

    /**
     * @Route
     * (
     *      path="/",
     *      name="fhm_api_menu"
     * )
     * @Template("::FhmFhm/Menu/Api/index.html.twig")
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
        if($id == 'demo')
        {
            return new Response(
                $this->renderView(
                    "::FhmFhm/Menu/Template/" . $template . ".html.twig",
                    array(
                        'object' => null,
                        'tree'   => null,
                        'demo'   => true
                    )
                )
            );
        }
        elseif(is_null($id))
        {
            $site = $this->get('fhm_tools')->dmRepository("FhmFhmBundle:Site")->getDefault();
            $id   = ($site && $site->getMenu()) ? $site->getMenu()->getId() : null;
            if(is_null($id))
            {
                return $this->render(
                    "::FhmFhm/Menu/Template/" . $template . ".html.twig",
                    array(
                        'object' => null,
                        'tree'   => null,
                    )
                );
            }
        }
        $menuRepository = $this->get('fhm.object.manager')->getCurrentRepository(self::$repository);
        $object         = $menuRepository->find($id);
        $object         = ($object) ? $object : $menuRepository->findOneBy(array("alias" => $id));
        $object         = ($object) ? $object : $menuRepository->findOneBy(array("name" => $id));
        // ERROR - unknown
        if(!is_object($object))
        {
            throw $this->createNotFoundException($this->trans('menu.error.unknown'));
        }
        elseif($object->getDelete() || !$object->getActive())
        {
            throw new HttpException(
                403,
                $this->trans('menu.error.forbidden', array(), 'FhmFhmMenu')
            );
        }

        return $this->render(
            "::FhmFhm/Menu/Template/" . $template . ".html.twig",
            array(
                'object' => $object,
                'tree'   => $menuRepository->getTree($object->getId())
            )
        );
    }

    /**
     * @Route
     * (
     *      path="/autocomplete/",
     *      name="fhm_api_menu_autocomplete"
     * )
     * @Template("::FhmFhm/Menu/Api/autocomplete.html.twig")
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
                $objectMenu = $this->getDoctrine()->getManager()->getRepository(
                    'FhmNewsBundle:' . ucfirst($datas['module'])
                )->find($datas['data']);
            }
            else
            {
                $objectMenu = $this->getDoctrine()->getManager()->getRepository(
                    'Fhm' . ucfirst($datas['module']) . 'Bundle:' . ucfirst($datas['module'])
                )->find($datas['data']);
            }
            $data['link']   = utf8_encode($this->getRoute($datas['module'], $objectMenu));
            $data['module'] = utf8_encode($datas['module']);
            $data['id']     = utf8_encode($datas['data']);
        }
        $response = new JsonResponse();

        return $response->setData($data);
    }

    /**
     * @param $module
     * @param $object
     *
     * @return string
     */
    private function getRoute($module, $object)
    {
        if($module === "media")
        {
            $route = $this->get('fhm_media_service')->setDocument($object)->getPathWeb();
        }
        else
        {
            $route = '/' . $module . '/detail/' . $object->getAlias();
        }

        return $route;
    }
}