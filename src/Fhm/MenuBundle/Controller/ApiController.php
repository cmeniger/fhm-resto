<?php
namespace Fhm\MenuBundle\Controller;

use Fhm\FhmBundle\Controller\RefApiController as FhmController;
use Fhm\FhmBundle\Services\Tools;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Fhm\MenuBundle\Document\Menu;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * @Route("/api/menu")
 * ----------------------------------
 * Class ApiController
 * @package Fhm\MenuBundle\Controller
 */
class ApiController extends FhmController
{
    /**
     * ApiController constructor.
     */
    public function __construct()
    {
        self::$repository = "FhmMenuBundle:Menu";
        self::$source = "fhm";
        self::$domain = "FhmMenuBundle";
        self::$translation = "menu";
        self::$class = Menu::class;
        self::$route = "menu";
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
        //TODO manage cache later with varnish
        if (is_null($id)) {
            $site = $this->get('fhm_tools')->dmRepository("FhmSiteBundle:Site")->getDefault();
            $id = ($site && $site->getMenu()) ? $site->getMenu()->getId() : null;
            if (is_null($id)) {
                return $this->render(
                    "::FhmMenu/Template/".$template.".html.twig",
                    array(
                        'document' => null,
                        'tree' => null,
                    )
                );
            }
        }
        $menuRepository = $this->get('doctrine.odm.mongodb.document_manager')->getRepository("FhmMenuBundle:Menu");
        $document = $menuRepository->find($id);
        $document = ($document) ? $document : $menuRepository->findOneBy(array("alias" => $id));
        $document = ($document) ? $document : $menuRepository->findOneBy(array("name" => $id));
        // ERROR - unknown
        if (!is_object($document)) {
            throw $this->createNotFoundException($this->trans('menu.error.unknown'));
        } elseif (!$this->getUser()->isSuperAdmin() && ($document->getDelete() || !$document->getActive())) {
            throw new HttpException(
                403,
                $this->trans('menu.error.forbidden', array(), 'FhmMenuBundle')
            );
        }
        return $this->render(
            "::FhmMenu/Template/".$template.".html.twig",
            array(
                'document' => $document,
                'tree' => $menuRepository->getTree($document->getId()),
                'instance' => $this->getProperties(),
            )
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
        $data = array();
        if ($datas['module'] != '' && $datas['data'] != '') {
            if ($datas['module'] == "newsGroup") {
                $documentMenu = $this->getDoctrine()->getManager()->getRepository(
                    'FhmNewsBundle:'.ucfirst($datas['module'])
                )->find($datas['data']);
            } else {
                $documentMenu = $this->getDoctrine()->getManager()->getRepository(
                    'Fhm'.ucfirst($datas['module']).'Bundle:'.ucfirst($datas['module'])
                )->find($datas['data']);
            }
            $data['link'] = utf8_encode($this->getRoute($datas['module'], $documentMenu));
            $data['module'] = utf8_encode($datas['module']);
            $data['id'] = utf8_encode($datas['data']);
        }
        $response = new JsonResponse();

        return $response->setData($data);
    }

    /**
     * @param $module
     * @param $document
     * @return string
     */
    private function getRoute($module, $document)
    {
        if ($module === "media") {
            $route = $this->get($this->getParameters('service', 'fhm_media'))->setDocument($document)->getPathWeb();
        } else {
            $route = '/'.$module.'/detail/'.$document->getAlias();
        }

        return $route;
    }
}