<?php

namespace Fhm\CardBundle\Controller;

use Fhm\FhmBundle\Controller\RefApiController as FhmController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * @Route("/api/card")
 * ----------------------------------
 * Class ApiController
 *
 * @package Fhm\CardBundle\Controller
 */
class ApiController extends FhmController
{
    /**
     * FrontController constructor.
     */
    public function __construct()
    {
        self::$repository  = "FhmCardBundle:Card";
        self::$source      = "fhm";
        self::$domain      = "FhmCardBundle";
        self::$translation = "card";
        self::$route       = 'card';
    }

    /**
     * @Route
     * (
     *      path="/",
     *      name="fhm_api_card"
     * )
     * @Template("::FhmCard/Api/index.html.twig")
     */
    public function indexAction()
    {
        return parent::indexAction();
    }

    /**
     * @Route
     * (
     *      path="/autocomplete",
     *      name="fhm_api_card_autocomplete"
     * )
     * @Template("::FhmCard/Api/autocomplete.html.twig")
     */
    public function autocompleteAction(Request $request)
    {
        return parent::autocompleteAction($request);
    }

    /**
     * @Route
     * (
     *      path="/embed/{template}/{id}",
     *      name="fhm_api_card_embed",
     *      requirements={"id"="[a-z0-9]*"},
     *      defaults={"template"="default"}
     * )
     */
    public function embedAction($id, $template)
    {
        $document = $this->get('fhm_tools')->dmRepository(self::$repository)->find($id);
        // ERROR - unknown
        if($document == "")
        {
            throw $this->createNotFoundException($this->get('translator')->trans(self::$translation . '.error.unknown', array(), self::$domain));
        }

        return new Response(
            $this->renderView(
                "::FhmCard/Template/Embed/" . ucfirst(strtolower($template)) . "/index.html.twig",
                array(
                    "document"  => $document,
                    "documents" => $this->get('fhm_tools')->dmRepository('FhmCardBundle:CardCategory')->getByCard($document),
                )
            )
        );
    }

    /**
     * @Route
     * (
     *      path="/editor/{template}/{id}",
     *      name="fhm_api_card_editor",
     *      requirements={"id"="[a-z0-9]*"},
     *      defaults={"template"="default"}
     * )
     */
    public function editorAction($id, $template)
    {
        return $this->_model($template)->index($id);
    }

    /**
     * @Route
     * (
     *      path="/editor/preview/{template}/{id}",
     *      name="fhm_api_card_editor_preview",
     *      requirements={"id"="[a-z0-9]*"},
     *      defaults={"template"="default"}
     * )
     */
    public function editorPreviewAction($id, $template)
    {
        return $this->_model($template)->previewIndex($id);
    }

    /**
     * @param $template
     *
     * @return string
     */
    private function _model($template)
    {
        $model = $this->get('fhm_card_model_default');
        if($this->has('fhm_card_model_' . $template))
        {
            $model = $this->get('fhm_card_model_' . $template);
        }

        return $model;
    }
}