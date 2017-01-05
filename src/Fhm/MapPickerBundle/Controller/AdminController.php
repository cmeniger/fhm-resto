<?php
namespace Fhm\MapPickerBundle\Controller;

use Fhm\FhmBundle\Controller\RefAdminController as FhmController;
use Fhm\FhmBundle\Form\Handler\Admin\CreateHandler;
use Fhm\FhmBundle\Form\Handler\Admin\UpdateHandler;
use Fhm\MapPickerBundle\Document\MapPicker;
use Fhm\MapPickerBundle\Form\Type\Admin\CreateType;
use Fhm\MapPickerBundle\Form\Type\Admin\UpdateType;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * @Route("/admin/mappicker")
 * ---------------------------------------
 * Class AdminController
 * @package Fhm\MapPickerBundle\Controller
 */
class AdminController extends FhmController
{
    /**
     * AdminController constructor.
     */
    public function __construct()
    {
        self::$repository = "FhmMapPickerBundle:MapPicker";
        self::$source = "fhm";
        self::$domain = "FhmMapPickerBundle";
        self::$translation = "mappicker";
        self::$class = MapPicker::class;
        self::$route = "mappicker";
        self::$form = new \stdClass();
        self::$form->createType = CreateType::class;
        self::$form->createHandler = CreateHandler::class;
        self::$form->updateType = UpdateType::class;
        self::$form->updateHandler = UpdateHandler::class;
    }

    /**
     * @Route
     * (
     *      path="/",
     *      name="fhm_admin_mappicker"
     * )
     * @Template("::FhmMapPicker/Admin/index.html.twig")
     */
    public function indexAction()
    {
        return parent::indexAction();
    }

    /**
     * @Route
     * (
     *      path="/create",
     *      name="fhm_admin_mappicker_create"
     * )
     * @Template("::FhmMapPicker/Admin/create.html.twig")
     */
    public function createAction(Request $request)
    {
        $document = new self::$class;
        $form = $this->createForm(
            self::$form->createType,
            $document,
            array('map' => $this->getParameters('maps', 'fhm_mappicker'))
        );
        $handler = new self::$form->createHandler($form, $request);
        $process = $handler->process();
        if ($process) {
            $data = $request->get($form->getName());
            $this->get('fhm_tools')->dmPersist($document);
            $this->get('session')->getFlashBag()->add(
                'notice',
                $this->trans(self::$translation.'.admin.create.flash.ok')
            );

            return $this->redirectUrl($data, $document);
        }

        return array(
            'form' => $form->createView(),
            'breadcrumbs' => $this->get('fhm_tools')->generateBreadcrumbs(
                array(
                    'domain' => self::$domain,
                    '_route' => $this->get('request_stack')->getCurrentRequest()->get('_route'),
                )
            ),
        );
    }

    /**
     * @Route
     * (
     *      path="/update/{id}",
     *      name="fhm_admin_mappicker_update",
     *      requirements={"id"="[a-z0-9]*"}
     * )
     * @Template("::FhmMapPicker/Admin/update.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $document = $this->get('fhm_tools')->dmRepository(self::$repository)->find($id);
        // ERROR - unknown
        if ($document == "") {
            throw $this->createNotFoundException($this->trans(self::$translation.'.error.unknown'));
        }
        $form = $this->createForm(
            self::$form->updateType,
            $document,
            array('map' => $this->getParameters('maps', 'fhm_mappicker'))
        );
        $handler = new self::$form->updateHandler($form, $request);
        $process = $handler->process();
        if ($process) {
            $data = $request->get($form->getName());
            $this->get('fhm_tools')->dmPersist($document);
            $this->get('session')->getFlashBag()->add('notice', $this->trans('.admin.update.flash.ok'));

            return $this->redirectUrl($data, $document);
        }

        return array(
            'document' => $document,
            'form' => $form->createView(),
            'breadcrumbs' => $this->get('fhm_tools')->generateBreadcrumbs(
                array(
                    'domain' => self::$domain,
                    '_route' => $this->get('request_stack')->getCurrentRequest()->get('_route'),
                ),
                $document
            ),
        );
    }

    /**
     * @Route
     * (
     *      path="/detail/{id}",
     *      name="fhm_admin_mappicker_detail",
     *      requirements={"id"="[a-z0-9]*"}
     * )
     * @Template("::FhmMapPicker/Admin/detail.html.twig")
     */
    public function detailAction($id)
    {
        $response = parent::detailAction($id);
        $document = $response['document'];
        $document->mappicker = $this->get('mappicker.'.$response['document']->getMap())->setDocument(
            $response['document']
        );
        $document->sites = $this->get('fhm_tools')->dmRepository('FhmSiteBundle:Site')->getFrontIndex('');
        foreach ((array)$document->getZone() as $zone) {
            $document->addZone(
                $zone['code'],
                $this->get('fhm_tools')->dm()->getRepository('FhmSiteBundle:Site')->find($zone['site'])
            );
        }
        $response['document'] = $document;

        return $response;
    }

    /**
     * @Route
     * (
     *      path="/duplicate/{id}",
     *      name="fhm_admin_mappicker_duplicate",
     *      requirements={"id"="[a-z0-9]*"}
     * )
     * @Template("::FhmMapPicker/Admin/create.html.twig")
     */
    public function duplicateAction(Request $request, $id)
    {
        return parent::duplicateAction($request, $id);
    }

    /**
     * @Route
     * (
     *      path="/delete/{id}",
     *      name="fhm_admin_mappicker_delete",
     *      requirements={"id"="[a-z0-9]*"}
     * )
     */
    public function deleteAction($id)
    {
        return parent::deleteAction($id);
    }

    /**
     * @Route
     * (
     *      path="/undelete/{id}",
     *      name="fhm_admin_mappicker_undelete",
     *      requirements={"id"="[a-z0-9]*"}
     * )
     */
    public function undeleteAction($id)
    {
        return parent::undeleteAction($id);
    }

    /**
     * @Route
     * (
     *      path="/activate/{id}",
     *      name="fhm_admin_mappicker_activate",
     *      requirements={"id"="[a-z0-9]*"}
     * )
     */
    public function activateAction($id)
    {
        return parent::activateAction($id);
    }

    /**
     * @Route
     * (
     *      path="/deactivate/{id}",
     *      name="fhm_admin_mappicker_deactivate",
     *      requirements={"id"="[a-z0-9]*"}
     * )
     */
    public function deactivateAction($id)
    {
        return parent::deactivateAction($id);
    }

    /**
     * @Route
     * (
     *      path="/import",
     *      name="fhm_admin_mappicker_import"
     * )
     * @Template("::FhmMapPicker/Admin/import.html.twig")
     */
    public function importAction(Request $request)
    {
        return parent::importAction($request);
    }

    /**
     * @Route
     * (
     *      path="/export",
     *      name="fhm_admin_mappicker_export"
     * )
     * @Template("::FhmMapPicker/Admin/export.html.twig")
     */
    public function exportAction(Request $request)
    {
        return parent::exportAction($request);
    }

    /**
     * @Route
     * (
     *      path="/add",
     *      name="fhm_admin_mappicker_add"
     * )
     */
    public function addAction(Request $request)
    {
        $datas = $request->get('FhmAdd');
        $document = $this->get('fhm_tools')->dmRepository(self::$repository)->find($datas['id']);
        $document->addZone($datas['code'], $datas['site']);
        $this->get('fhm_tools')->dmPersist($document);

        return $this->redirect($this->get('fhm_tools')->getLastRoute($this->get('request_stack')->getCurrentRequest()));
    }

    /**
     * @Route
     * (
     *      path="/remove/{id}/{code}",
     *      name="fhm_admin_mappicker_remove",
     *      requirements={"id"="[a-z0-9]*", "code"="[0-9]*"}
     * )
     */
    public function removeAction($id, $code)
    {
        $document = $this->get('fhm_tools')->dmRepository(self::$repository)->find($id);
        $document->removeZone($code);
        $this->get('fhm_tools')->dmPersist($document);

        return $this->redirect($this->get('fhm_tools')->getLastRoute($this->get('request_stack')->getCurrentRequest()));
    }
}