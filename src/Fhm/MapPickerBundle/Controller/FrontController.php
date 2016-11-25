<?php
namespace Fhm\MapPickerBundle\Controller;

use Fhm\FhmBundle\Controller\RefFrontController as FhmController;
use Fhm\MapPickerBundle\Document\Map;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * @Route("/mappicker", service="fhm_mappicker_controller_front")
 */
class FrontController extends FhmController
{
    /**
     * FrontController constructor.
     *
     * @param \Fhm\FhmBundle\Services\Tools $tools
     */
    public function __construct(\Fhm\FhmBundle\Services\Tools $tools)
    {
        $this->setFhmTools($tools);
        parent::__construct('Fhm', 'MapPicker', 'mappicker');
    }

    /**
     * @Route
     * (
     *      path="/",
     *      name="fhm_mappicker"
     * )
     * @Template("::FhmMapPicker/Front/index.html.twig")
     */
    public function indexAction()
    {
        $documents = $this->fhm_tools->dmRepository()->getFrontIndex();

        foreach ($documents as &$document) {
            $document->mappicker = $this->container->get('mappicker.'.$document->getMap())->setDocument($document);
            foreach ($document->getZone() as $zone) {
                $document->addZone(
                    $zone['code'],
                    $this->fhm_tools->dm()->getRepository('FhmSiteBundle:Site')->find($zone['site'])
                );
            }
        }

        return array(
            'documents' => $documents,
            'instance' => $this->fhm_tools->instanceData(),
        );
    }

    /**
     * @Route
     * (
     *      path="/detail/{id}",
     *      name="fhm_mappicker_detail",
     *      requirements={"id"="[a-z0-9]*"}
     * )
     * @Template("::FhmMapPicker/Front/detail.html.twig")
     */
    public function detailAction($id)
    {
        // For activate this route, delete next line
        throw $this->createNotFoundException($this->fhm_tools->trans('fhm.error.route', array(), 'FhmFhmBundle'));
    }

    /**
     * @Route
     * (
     *      path="/create",
     *      name="fhm_mappicker_create"
     * )
     * @Template("::FhmMapPicker/Front/create.html.twig")
     */
    public function createAction(Request $request)
    {
        // For activate this route, delete next line
        throw $this->createNotFoundException($this->fhm_tools->trans('fhm.error.route', array(), 'FhmFhmBundle'));
    }

    /**
     * @Route
     * (
     *      path="/update/{id}",
     *      name="fhm_mappicker_update",
     *      requirements={"id"="[a-z0-9]*"}
     * )
     * @Template("::FhmMapPicker/Front/update.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        // For activate this route, delete next line
        throw $this->createNotFoundException($this->fhm_tools->trans('fhm.error.route', array(), 'FhmFhmBundle'));
    }

    /**
     * @Route
     * (
     *      path="/delete/{id}",
     *      name="fhm_mappicker_delete",
     *      requirements={"id"="[a-z0-9]*"}
     * )
     */
    public function deleteAction($id)
    {
        // For activate this route, delete next line
        throw $this->createNotFoundException($this->fhm_tools->trans('fhm.error.route', array(), 'FhmFhmBundle'));
    }

    /**
     * @Route
     * (
     *      path="/{id}",
     *      name="fhm_mappicker_lite",
     *      requirements={"id"=".+"}
     * )
     * @Template("::FhmMapPicker/Front/detail.html.twig")
     */
    public function liteAction($id)
    {
        // For activate this route, delete next line
        throw $this->createNotFoundException($this->fhm_tools->trans('fhm.error.route', array(), 'FhmFhmBundle'));
    }
}