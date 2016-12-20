<?php
namespace Fhm\EventBundle\Controller;

use Fhm\FhmBundle\Controller\RefApiController as FhmController;
use Fhm\EventBundle\Document\Event;
use Fhm\FhmBundle\Form\Type\Admin\SearchType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * @Route("/api/event")
 * -----------------------------------
 * Class ApiController
 * @package Fhm\EventBundle\Controller
 */
class ApiController extends FhmController
{
    /**
     * ApiController constructor.
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
        $translation = "event",
        $document = Event::class,
        $route = 'event'
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
     *      name="fhm_api_event"
     * )
     * @Template("::FhmEvent/Api/index.html.twig")
     */
    public function indexAction()
    {
        return parent::indexAction();
    }

    /**
     * @Route
     * (
     *      path="/autocomplete/",
     *      name="fhm_api_event_autocomplete"
     * )
     * @Template("::FhmEvent/Api/autocomplete.html.twig")
     */
    public function autocompleteAction(Request $request)
    {
        return parent::autocompleteAction($request);
    }

    /**
     * @Route
     * (
     *      path="/detail/{template}/{rows}/{pagination}/{id}",
     *      name="fhm_api_event_detail",
     *      requirements={"id"=".+", "rows"="\d*", "pagination"="[0-1]"},
     *      defaults={"id"=null, "rows"=null, "pagination"=1}
     * )
     * @Template("::FhmEvent/Template/short.html.twig")
     */
    public function detailAction($template, $id, $rows, $pagination)
    {
        $document = "";
        // Event
        if ($id && $template == 'full') {
            $document = $this->get('fhm_tools')->dmRepository(self::$repository)->getById($id);
            $document = ($document) ? $document : $this->get('fhm_tools')->dmRepository(self::$repository)->getByAlias(
                $id
            );
            $document = ($document) ? $document : $this->get('fhm_tools')->dmRepository(self::$repository)->getByName(
                $id
            );
            $documents = '';
            $form = '';
            if ($document == "") {
                throw $this->createNotFoundException(
                    $this->trans('event.group.error.unknown', array(), 'FhmEventBundle')
                );
            } // ERROR - Forbidden
            elseif (!$this->getUser()->hasRole('ROLE_ADMIN') && ($document->getDelete() || !$document->getActive())) {
                throw new HttpException(
                    403,
                    $this->trans('event.group.error.forbidden', array(), 'FhmEventBundle')
                );
            }
        } else {
            // Group
            if ($id) {
                $document = $this->get('fhm_tools')->dmRepository("FhmEventBundle:EventGroup")->getById($id);
                $document = ($document) ? $document : $this->get('fhm_tools')->dmRepository(
                    "FhmEventBundle:EventGroup"
                )->getByAlias($id);
                $document = ($document) ? $document : $this->get('fhm_tools')->dmRepository(
                    "FhmEventBundle:EventGroup"
                )->getByName($id);
                // ERROR - unknown
                if ($document == "") {
                    throw $this->createNotFoundException(
                        $this->trans('event.group.error.unknown', array(), 'FhmEventBundle')
                    );
                } // ERROR - Forbidden
                elseif (
                    !$this->getUser()->hasRole('ROLE_ADMIN') &&
                    (
                        $document->getDelete() ||
                        !$document->getActive()
                    )
                ) {
                    throw new HttpException(
                        403,
                        $this->trans('event.group.error.forbidden', array(), 'FhmEventBundle')
                    );
                }
            }
            // Event
            $form = $this->createForm(SearchType::class);
            $form->setData($this->get('request_stack')->getCurrentRequest()->get($form->getName()));
            $dataSearch = $form->getData();
            $documents = $document ? $this->get('fhm_tools')->dmRepository(self::$repository)->getEventByGroupIndex(
                $document,
                $dataSearch['search']
            ) : $this->get('fhm_tools')->dmRepository(self::$repository)->getFrontIndex($dataSearch['search']);
        }

        return new Response(
            $this->renderView(
                "::FhmEvent/Template/".$template.".html.twig",
                array(
                    'document' => $document,
                    'documents' => $documents,
                    'form' => $form ? $form->createView() : $form,
                )
            )
        );
    }

    /**
     * @Route
     * (
     *      path="/all",
     *      name="fhm_api_event_all"
     * )
     */
    public function getAllAction()
    {
        $events = array();
        $aEvents = $this->get('fhm_tools')->dmRepository(self::$repository)->getEventEnable();
        foreach ($aEvents as $event) {
            $interval = $event->getDateStart()->diff($event->getDateEnd());
            $nbJours = $interval->format('%a');
            $events[$event->getDateStart()->format('Y-m-d')][] = $event;
            for ($i = 0; $i < $nbJours; $i++) {
                $datePlusOne = $event->getDateStart()->modify('+1 day')->format('Y-m-d');
                $events[$datePlusOne][] = $event;
            }
        }
        $response = new JsonResponse();
        $response->setData($events);

        return $response;
    }

    /**
     * @Route
     * (
     *      path="/date/{date}",
     *      name="fhm_api_event_date",
     *      requirements={"date"="[0-9\-]*"}
     * )
     * @Template("::FhmEvent/Front/index.html.twig")
     */
    public function getDateAction($date)
    {
        $form = $this->createForm(SearchType::class, null);
        $form->setData($this->get('request_stack')->getCurrentRequest()->get($form->getName()));
        $dataSearch = $form->getData();
        $documents = $this->get('fhm_tools')->dmRepository(self::$repository)->getFrontDateIndex(
            $date,
            $dataSearch['search']
        );

        return array(
            'documents' => $documents,
            'form' => $form->createView(),
        );
    }
}