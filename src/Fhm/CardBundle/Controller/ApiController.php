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
 * @Route("/api/card", service="fhm_card_controller_api")
 */
class ApiController extends FhmController
{
    /**
     * Constructor
     */
    public function __construct(Tools $tools)
    {
        $this->setFhmTools($tools);
        parent::__construct('Fhm', 'Card', 'card');
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
     *      path="/historic",
     *      name="fhm_api_card_historic"
     * )
     * @Template("::FhmCard/Api/historic.html.twig")
     */
    public function historicAction(Request $request)
    {
        return parent::historicAction($request);
    }


    /**
     * @Route
     * (
     *      path="/historic/copy/{id}",
     *      name="fhm_api_card_historic_copy",
     *      requirements={"id"="[a-z0-9]*"}
     * )
     */
    public function historicCopyAction(Request $request, $id)
    {
        return parent::historicCopyAction($request, $id);
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
        $document = $this->fhm_tools->dmRepository()->find($id);
        $instance = $this->fhm_tools->instanceData($document);
        // ERROR - unknown
        if($document == "")
        {
            throw $this->createNotFoundException($this->get('translator')->trans($this->translation[1] . '.error.unknown', array(), $this->translation[0]));
        }

        return new Response(
            $this->renderView(
                "::FhmCard/Template/" . ucfirst(strtolower($template)) . "/index.html.twig",
                array(
                    "document"  => $document,
                    "documents" => $this->fhm_tools->dmRepository('FhmCardBundle:CardCategory')->getByCard($document, $instance->grouping->filtered),
                    "instance"  => $instance,
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
        $document = $this->fhm_tools->dmRepository()->find($id);
        $instance = $this->fhm_tools->instanceData($document);
        $this->_authorized($document);

        return new Response(
            $this->renderView(
                "::FhmCard/Template/Editor/index.html.twig",
                array(
                    "document" => $document,
                    "instance" => $instance,
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
        $document = $this->fhm_tools->dmRepository()->find($id);
        $instance = $this->fhm_tools->instanceData($document);
        $this->_authorized($document);

        return new Response(
            $this->renderView(
                "::FhmCard/Template/Editor/preview.html.twig",
                array(
                    "document" => $document,
                    "instance" => $instance,
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
    protected function _authorized($card)
    {
        if($card == "")
        {
            throw $this->createNotFoundException($this->get('translator')->trans('card.error.unknown', array(), $this->translation[0]));
        }
        if($card->getParent() && method_exists($card->getParent(), 'hasModerator'))
        {
            if(!$this->getUser()->isSuperAdmin() && !$this->getUser()->hasRole('ROLE_ADMIN') && !$card->getParent()->hasModerator($this->getUser()))
            {
                throw new HttpException(403, $this->get('translator')->trans($this->translation[1] . '.error.forbidden', array(), $this->translation[0]));
            }
        }

        return $this;
    }
}