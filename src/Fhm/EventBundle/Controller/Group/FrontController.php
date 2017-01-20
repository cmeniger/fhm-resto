<?php
namespace Fhm\EventBundle\Controller\Group;

use Fhm\FhmBundle\Controller\RefFrontController as FhmController;
use Fhm\FhmBundle\Form\Type\Admin\SearchType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * @Route("/eventgroup")
 * -----------------------------------------
 * Class FrontController
 * @package Fhm\EventBundle\Controller\Group
 */
class FrontController extends FhmController
{
    /**
     * FrontController constructor.
     */
    public function __construct()
    {
        self::$repository = "FhmEventBundle:EventGroup";
        self::$source = "fhm";
        self::$domain = "FhmEventBundle";
        self::$translation = "event.group";
        self::$route = "event_group";
    }

    /**
     * @Route
     * (
     *      path="/",
     *      name="fhm_event_group"
     * )
     * @Template("::FhmEvent/Front/Group/index.html.twig")
     */
    public function indexAction()
    {
        $response = parent::indexAction();
        foreach ($response['objects'] as $key => $object) {
            $response['objects'][$key]->allevent = $this->get('fhm_tools')->dmRepository(
                "FhmEventBundle:Event"
            )->getEventByGroupAll($object);
        }

        return $response;
    }

    /**
     * @Route
     * (
     *      path="/detail/{id}",
     *      name="fhm_event_group_detail",
     *      requirements={"id"=".+"}
     * )
     * @Template("::FhmEvent/Front/Group/detail.html.twig")
     */
    public function detailAction($id)
    {
        $response = parent::detailAction($id);
        $object = $response['object'];
        $form = $this->createForm(SearchType::class);
        $form->setData($this->get('request_stack')->getCurrentRequest()->get($form->getName()));
        $dataSearch = $form->getData();
        $objects = $this->get('fhm_tools')->dmRepository("FhmEventBundle:Event")->getEventByGroupIndex(
            $object,
            $dataSearch['search']
        );

        return array_merge(
            $response,
            array(
                'objects' => $objects,
                'form' => $form->createView(),
            )
        );
    }

    /**
     * @Route
     * (
     *      path="/{id}",
     *      name="fhm_event_group_lite",
     *      requirements={"id"=".+"}
     * )
     * @Template("::FhmEvent/Front/Group/detail.html.twig")
     */
    public function liteAction($id)
    {
        return $this->detailAction($id);
    }
}