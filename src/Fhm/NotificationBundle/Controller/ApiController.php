<?php
namespace Fhm\NotificationBundle\Controller;

use Fhm\FhmBundle\Controller\RefApiController as FhmController;
use Fhm\NotificationBundle\Document\Notification;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * @Route("/api/notification", service="fhm_notification_controller_api")
 */
class ApiController extends FhmController
{
    /**
     * ApiController constructor.
     *
     * @param \Fhm\FhmBundle\Services\Tools $tools
     */
    public function __construct(\Fhm\FhmBundle\Services\Tools $tools)
    {
        $this->setFhmTools($tools);
        parent::__construct('Fhm', 'Notification', 'notification');
    }

    /**
     * @Route
     * (
     *      path="/",
     *      name="fhm_api_notification"
     * )
     * @Template("::FhmNotification/Api/index.html.twig")
     */
    public function indexAction()
    {
        return parent::indexAction();
    }

    /**
     * @Route
     * (
     *      path="/autocomplete",
     *      name="fhm_api_notification_autocomplete"
     * )
     * @Template("::FhmNotification/Api/autocomplete.html.twig")
     */
    public function autocompleteAction(Request $request)
    {
        return parent::autocompleteAction($request);
    }

    /**
     * @Route
     * (
     *      path="/counter",
     *      name="fhm_api_notification_counter"
     * )
     * @Template("::FhmNotification/Template/counter.html.twig")
     */
    public function counterAction(Request $request)
    {
        return array(
            'count' => $this->fhm_tools->dmRepository()->getCountNew($this->getUser()),
            'instance' => $this->fhm_tools->instanceData(),
        );
    }

    /**
     * @Route
     * (
     *      path="/counter/number",
     *      name="fhm_api_notification_counter_number"
     * )
     */
    public function counterNumberAction(Request $request)
    {
        $response = new JsonResponse();
        $response->setData(
            array(
                'count' => $this->fhm_tools->dmRepository()->getCountNew($this->getUser()),
            )
        );

        return $response;
    }

    /**
     * @Route
     * (
     *      path="/modal/new",
     *      name="fhm_api_notification_modal_new"
     * )
     * @Template("::FhmNotification/Template/modal.new.html.twig")
     */
    public function modalNewAction(Request $request)
    {
        return array(
            'documents' => $this->fhm_tools->dmRepository()->getIndexNew($this->getUser()),
            'instance' => $this->fhm_tools->instanceData(),
        );
    }

    /**
     * @Route
     * (
     *      path="/modal/all",
     *      name="fhm_api_notification_modal_all"
     * )
     * @Template("::FhmNotification/Template/modal.all.html.twig")
     */
    public function modalAllAction(Request $request)
    {
        return array(
            'documents' => $this->fhm_tools->dmRepository()->getIndexAll($this->getUser()),
            'instance' => $this->fhm_tools->instanceData(),
        );
    }

    /**
     * @Route
     * (
     *      path="/detail/{id}",
     *      name="fhm_api_notification_detail",
     *      requirements={"id"=".+"}
     * )
     */
    public function detailAction($id)
    {
        $document = $this->fhm_tools->dmRepository()->getById($id);
        $document = ($document) ? $document : $this->fhm_tools->dmRepository()->getByAlias($id);
        $document = ($document) ? $document : $this->fhm_tools->dmRepository()->getByName($id);
        $instance = $this->fhm_tools->instanceData($document);
        if ($document == "") {
            throw $this->createNotFoundException($this->fhm_tools->trans('.error.unknown'));
        } // ERROR - Forbidden
        elseif (!$instance->user->admin && ($document->getDelete() || !$document->getActive())) {
            throw new HttpException(403, $this->fhm_tools->trans('.error.forbidden'));
        }

        return new Response(
            $this->renderView(
                "::FhmNotification/Template/template.".($this->get('templating')->exists(
                    "::FhmNotification/Template/template.".$document->getTemplate().".html.twig"
                ) ? $document->getTemplate() : "default").".html.twig",
                array(
                    'document' => $document,
                    'instance' => $instance,
                )
            )
        );
    }

    /**
     * @Route
     * (
     *      path="/delete/{id}",
     *      name="fhm_api_notification_delete",
     *      requirements={"id"=".+"}
     * )
     */
    public function deleteAction($id)
    {
        $document = $this->fhm_tools->dmRepository()->getById($id);
        $document = ($document) ? $document : $this->fhm_tools->dmRepository()->getByAlias($id);
        $document = ($document) ? $document : $this->fhm_tools->dmRepository()->getByName($id);
        $instance = $this->fhm_tools->instanceData($document);
        if ($document == "") {
            throw $this->createNotFoundException($this->fhm_tools->trans('.error.unknown'));
        } elseif ($document->getUser() != $this->getUser()) {
            throw new HttpException(403, $this->fhm_tools->trans('.error.forbidden'));
        } // ERROR - Forbidden
        elseif (!$instance->user->admin && ($document->getDelete() || !$document->getActive())) {
            throw new HttpException(403, $this->fhm_tools->trans('.error.forbidden'));
        }
        $document->setDelete(true);
        $this->fhm_tools->dmPersist($document);

        return $this->redirect($this->fhm_tools->getUrl('fhm_api_notification_modal_all'));
    }

    /**
     * @Route
     * (
     *      path="/new/{id}/{code}",
     *      name="fhm_api_notification_new",
     *      requirements={"id"=".+"}
     * )
     */
    public function newAction($id, $code)
    {
        $document = $this->fhm_tools->dmRepository()->getById($id);
        $document = ($document) ? $document : $this->fhm_tools->dmRepository()->getByAlias($id);
        $document = ($document) ? $document : $this->fhm_tools->dmRepository()->getByName($id);
        $instance = $this->fhm_tools->instanceData($document);
        if ($document == "") {
            throw $this->createNotFoundException($this->fhm_tools->trans('.error.unknown'));
        } elseif ($document->getUser() != $this->getUser()) {
            throw new HttpException(403, $this->fhm_tools->trans('.error.forbidden'));
        } // ERROR - Forbidden
        elseif (!$instance->user->admin && ($document->getDelete() || !$document->getActive())) {
            throw new HttpException(403, $this->fhm_tools->trans('.error.forbidden'));
        }
        $document->setNew($code);
        $this->fhm_tools->dmPersist($document);

        return $this->redirect($this->fhm_tools->getUrl('fhm_api_notification_modal_all'));
    }
}