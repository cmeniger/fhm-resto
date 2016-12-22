<?php
namespace Fhm\CardBundle\Controller;

use Doctrine\Common\Collections\ArrayCollection;
use Fhm\FhmBundle\Controller\RefApiController as FhmController;
use Fhm\CardBundle\Document\Card;
use Fhm\FhmBundle\Services\Tools;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * @Route("/api/card")
 * ----------------------------------
 * Class ApiController
 * @package Fhm\CardBundle\Controller
 */
class ApiController extends FhmController
{
    /**
     * FrontController constructor.
     */
    public function __construct()
    {
        self::$repository = "FhmCardBundle:Card";
        self::$source = "fhm";
        self::$domain = "FhmCardBundle";
        self::$translation = "card";
        self::$class = Card::class;
        self::$route = 'card';
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
     *      defaults={"template"="inline"}
     * )
     */
    public function embedAction(Request $request, $id, $template)
    {
        $document = $this->get('fhm_tools')->dmRepository(self::$repository)->find($id);
        if ($document == "") {
            throw $this->createNotFoundException(
                $this->get('translator')->trans(self::$translation.'.error.unknown', array(), self::$domain)
            );
        }

        return new Response(
            $this->renderView(
                "::FhmCard/Template/".ucfirst(strtolower($template))."/index.html.twig",
                array(
                    "document" => $document,
                    "documents" => $this->get('fhm_tools')->dmRepository('FhmCardBundle:CardCategory')->getByCard(
                        $document
                    ),
                )
            )
        );
    }

    /**
     * @Route
     * (
     *      path="/editor/{id}",
     *      name="fhm_api_card_editor",
     *      requirements={"id"="[a-z0-9]*"}
     * )
     */
    public function editorAction(Request $request, $id)
    {
        $document = $this->get('fhm_tools')->dmRepository(self::$repository)->find($id);
        $this->__authorized($document);

        return new Response(
            $this->renderView(
                "::FhmCard/Template/Editor/index.html.twig",
                array(
                    "document" => $document,
                )
            )
        );
    }

    /**
     * @Route
     * (
     *      path="/editor/preview/{id}",
     *      name="fhm_api_card_editor_preview",
     *      requirements={"id"="[a-z0-9]*"}
     * )
     */
    public function editorPreviewAction(Request $request, $id)
    {
        $document = $this->get('fhm_tools')->dmRepository(self::$repository)->find($id);
        $this->__authorized($document);

        return new Response(
            $this->renderView(
                "::FhmCard/Template/Editor/preview.html.twig",
                array(
                    "document" => $document,
                )
            )
        );
    }

    /**
     * User is authorized
     *
     * @param $card
     *
     * @return $this
     */
    protected function __authorized($card)
    {
        if ($card == "") {
            throw $this->createNotFoundException(
                $this->get('translator')->trans('card.error.unknown', array(), self::$domain)
            );
        }
        if ($card->getParent() && method_exists($card->getParent(), 'hasModerator')) {
            if (!$this->getUser()->isSuperAdmin() && !$this->getUser()->hasRole('ROLE_ADMIN') && !$card->getParent(
                )->hasModerator($this->getUser())
            ) {
                throw new HttpException(
                    403, $this->get('translator')->trans(
                    self::$translation.'.error.forbidden',
                    array(),
                    self::$domain
                )
                );
            }
        }

        return $this;
    }
}