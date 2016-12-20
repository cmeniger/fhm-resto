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
     * @param string $repository
     * @param string $source
     * @param string $domain
     * @param string $translation
     * @param string $document
     * @param string $route
     */
    public function __construct(
        $repository = "FhmEventBundle:Event",
        $source = "fhm",
        $domain = "FhmEventBundle",
        $translation = "event.group",
        $document = EventGroup::class,
        $route = 'event_group'
    ) {
        self::$repository = $repository;
        self::$source = $source;
        self::$domain = $domain;
        self::$translation = $translation;
        self::$document = new $document();
        self::$class = get_class(self::$document);
        self::$route = $route;
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