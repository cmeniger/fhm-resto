<?php
namespace Fhm\NotificationBundle\Controller;

use Fhm\FhmBundle\Controller\RefApiController as FhmController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * @Route("/api/notification")
 * ------------------------------------------
 * Class ApiController
 * @package Fhm\NotificationBundle\Controller
 */
class ApiController extends FhmController
{
    /**
     * ApiController constructor.
     */
    public function __construct()
    {
        self::$repository = "FhmNotificationBundle:Notification";
        self::$source = "fhm";
        self::$domain = "FhmNotificationBundle";
        self::$translation = "notification";
        self::$route = "notification";
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
            'count' => $this->get('fhm_tools')->dmRepository(self::$repository)->getCountNew($this->getUser()),
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
                'count' => $this->get('fhm_tools')->dmRepository(self::$repository)->getCountNew($this->getUser()),
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
            'object' => $this->get('fhm_tools')->dmRepository(self::$repository)->getIndexNew($this->getUser()),
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
            'objects' => $this->get('fhm_tools')->dmRepository(self::$repository)->getIndexAll($this->getUser()),
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
        $object = $this->get('fhm_tools')->dmRepository(self::$repository)->getById($id);
        $object = ($object) ? $object : $this->get('fhm_tools')->dmRepository(self::$repository)->getByAlias($id);
        $object = ($object) ? $object : $this->get('fhm_tools')->dmRepository(self::$repository)->getByName($id);
        if ($object == "") {
            throw $this->createNotFoundException($this->trans('.error.unknown'));
        } // ERROR - Forbidden
        elseif (!$this->get('security.authorization_checker')->isGranted('ROLE_ADMIN') && ($object->getDelete(
                ) || !$object->getActive())
        ) {
            throw new HttpException(403, $this->trans('.error.forbidden'));
        }

        return new Response(
            $this->renderView(
                "::FhmNotification/Template/template.".($this->get('templating')->exists(
                    "::FhmNotification/Template/template.".$object->getTemplate().".html.twig"
                ) ? $object->getTemplate() : "default").".html.twig",
                array(
                    'object' => $object,
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
        $object = $this->get('fhm_tools')->dmRepository(self::$repository)->getById($id);
        $object = ($object) ? $object : $this->get('fhm_tools')->dmRepository(self::$repository)->getByAlias($id);
        $object = ($object) ? $object : $this->get('fhm_tools')->dmRepository(self::$repository)->getByName($id);
        if ($object == "") {
            throw $this->createNotFoundException($this->trans('notification.error.unknown'));
        } elseif ($object->getUser() != $this->getUser()) {
            throw new HttpException(403, $this->trans('notification.error.forbidden'));
        } // ERROR - Forbidden
        elseif (!$this->get('security.authorization_checker')->isGranted('ROLE_ADMIN') && ($object->getDelete(
                ) || !$object->getActive())
        ) {
            throw new HttpException(403, $this->trans('notification.error.forbidden'));
        }
        $object->setDelete(true);
        $this->get('fhm_tools')->dmPersist($object);

        return $this->redirect($this->getUrl('fhm_api_notification_modal_all'));
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
        $object = $this->get('fhm_tools')->dmRepository(self::$repository)->getById($id);
        $object = ($object) ? $object : $this->get('fhm_tools')->dmRepository(self::$repository)->getByAlias($id);
        $object = ($object) ? $object : $this->get('fhm_tools')->dmRepository(self::$repository)->getByName($id);
        if ($object == "") {
            throw $this->createNotFoundException($this->trans('notification.error.unknown'));
        } elseif ($object->getUser() != $this->getUser()) {
            throw new HttpException(403, $this->trans('notification.error.forbidden'));
        } // ERROR - Forbidden
        elseif (!$this->get('security.authorization_checker')->isGranted('ROLE_ADMIN') && ($object->getDelete(
                ) || !$object->getActive())
        ) {
            throw new HttpException(403, $this->trans('notification.error.forbidden'));
        }
        $object->setNew($code);
        $this->get('fhm_tools')->dmPersist($object);

        return $this->redirect($this->getUrl('fhm_api_notification_modal_all'));
    }
}