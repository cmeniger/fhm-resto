<?php
namespace Fhm\EventBundle\Controller\Group;

use Fhm\EventBundle\Document\EventGroup;
use Fhm\FhmBundle\Controller\RefFrontController as FhmController;
use Fhm\EventBundle\Document\Event;
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
        self::$class = EventGroup::class;
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
        foreach ($response['documents'] as $key => $document) {
            $response['documents'][$key]->allevent = $this->get('fhm_tools')->dmRepository(
                "FhmEventBundle:Event"
            )->getEventByGroupAll($document);
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
        $document = $response['document'];
        $form = $this->createForm(SearchType::class);
        $form->setData($this->get('request_stack')->getCurrentRequest()->get($form->getName()));
        $dataSearch = $form->getData();
        $documents = $this->get('fhm_tools')->dmRepository("FhmEventBundle:Event")->getEventByGroupIndex(
            $document,
            $dataSearch['search']
        );

        return array_merge(
            $response,
            array(
                'documents' => $documents,
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