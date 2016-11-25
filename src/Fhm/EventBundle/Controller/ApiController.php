<?php
namespace Fhm\EventBundle\Controller;

use Fhm\FhmBundle\Controller\RefApiController as FhmController;
use Fhm\EventBundle\Document\Event;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * @Route("/api/event", service="fhm_event_controller_api")
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
        parent::__construct('Fhm', 'Event', 'event');
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
     *      path="/historic/",
     *      name="fhm_api_event_historic"
     * )
     * @Template("::FhmEvent/Api/historic.html.twig")
     */
    public function historicAction(Request $request)
    {
        return parent::historicAction($request);
    }

    /**
     * @Route
     * (
     *      path="/historic/copy/{id}",
     *      name="fhm_api_event_historic_copy",
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
        $instance = $this->fhm_tools->instanceData();
        // Event
        if ($id && $template == 'full') {
            $document = $this->fhm_tools->dmRepository()->getById($id);
            $document = ($document) ? $document : $this->fhm_tools->dmRepository()->getByAlias($id);
            $document = ($document) ? $document : $this->fhm_tools->dmRepository()->getByName($id);
            $instance = $this->fhm_tools->instanceData($document);
            $documents = '';
            $form = '';
            // ERROR - unknown
            if ($document == "") {
                throw $this->createNotFoundException(
                    $this->fhm_tools->trans('event.group.error.unknown', array(), 'FhmEventBundle')
                );
            } // ERROR - Forbidden
            elseif (!$instance->user->admin && ($document->getDelete() || !$document->getActive())) {
                throw new HttpException(
                    403,
                    $this->fhm_tools->trans('event.group.error.forbidden', array(), 'FhmEventBundle')
                );
            }
            // Change grouping
            if ($instance->grouping->different && $document->getGrouping() && !$document->getGlobal()) {
                $this->get($this->fhm_tools->getParameters("grouping", "fhm_fhm"))->setGrouping(
                    $document->getFirstGrouping()
                );
            }
        } else {
            // Group
            if ($id) {
                $document = $this->fhm_tools->dmRepository("FhmEventBundle:EventGroup")->getById($id);
                $document = ($document) ? $document : $this->fhm_tools->dmRepository(
                    "FhmEventBundle:EventGroup"
                )->getByAlias($id);
                $document = ($document) ? $document : $this->fhm_tools->dmRepository(
                    "FhmEventBundle:EventGroup"
                )->getByName($id);
                $instance = $this->fhm_tools->instanceData($document);
                // ERROR - unknown
                if ($document == "") {
                    throw $this->createNotFoundException(
                        $this->fhm_tools->trans('event.group.error.unknown', array(), 'FhmEventBundle')
                    );
                } // ERROR - Forbidden
                elseif (!$instance->user->admin && ($document->getDelete() || !$document->getActive())) {
                    throw new HttpException(
                        403,
                        $this->fhm_tools->trans('event.group.error.forbidden', array(), 'FhmEventBundle')
                    );
                }
                // Change grouping
                if ($instance->grouping->different && $document->getGrouping() && !$document->getGlobal()) {
                    $this->get($this->fhm_tools->getParameters("grouping", "fhm_fhm"))->setGrouping(
                        $document->getFirstGrouping()
                    );
                }
            }
            // Event
            $classType = '\Fhm\FhmBundle\Form\Type\Front\SearchType';
            $form = $this->createForm($classType);
            $form->setData($this->get('request_stack')->get($form->getName()));
            $dataSearch = $form->getData();
            $dataPagination = $this->get('request_stack')->get('FhmPagination');
            $this->fhm_tools->setPagination($rows);
            // Ajax pagination request
            if ($pagination && isset($dataPagination['pagination'])) {
                $documents = $document ?
                    $this->fhm_tools->dmRepository()->getEventByGroupIndex(
                        $document,
                        $dataSearch['search'],
                        $dataPagination['pagination'],
                        $this->pagination->page
                    ) :
                    $this->fhm_tools->dmRepository()->getFrontIndex(
                        $dataSearch['search'],
                        $dataPagination['pagination'],
                        $this->pagination->page
                    );
                $pagination = $document ?
                    $this->fhm_tools->getPagination(
                        $dataPagination['pagination'],
                        count($documents),
                        $this->fhm_tools->dmRepository("FhmEventBundle:Event")->getEventByGroupCount(
                            $document,
                            $dataSearch['search']
                        ),
                        'pagination',
                        $this->fhm_tools->formRename($form->getName(), $dataSearch),
                        $this->fhm_tools->getUrl(
                            'fhm_api_event_detail',
                            array('template' => $template, 'group' => $id, 'rows' => $rows, 'pagination' => $pagination)
                        )
                    ) :
                    $this->fhm_tools->getPagination(
                        $dataPagination['pagination'],
                        count($documents),
                        $this->fhm_tools->dmRepository("FhmEventBundle:Event")->getFrontCount($dataSearch['search']),
                        'pagination',
                        $this->fhm_tools->formRename($form->getName(), $dataSearch),
                        $this->fhm_tools->getUrl(
                            'fhm_api_event_detail',
                            array('template' => $template, 'group' => $id, 'rows' => $rows, 'pagination' => $pagination)
                        )
                    );
            } // Router request
            else {
                $documents = $document ?
                    $this->fhm_tools->dmRepository()->getEventByGroupIndex(
                        $document,
                        $dataSearch['search'],
                        1,
                        $this->pagination->page
                    ) :
                    $this->fhm_tools->dmRepository()->getFrontIndex($dataSearch['search'], 1, $this->pagination->page);
                if ($pagination) {
                    $pagination = $document ?
                        $this->fhm_tools->getPagination(
                            1,
                            count($documents),
                            $this->fhm_tools->dmRepository("FhmEventBundle:Event")->getEventByGroupCount(
                                $document,
                                $dataSearch['search']
                            ),
                            'pagination',
                            $this->fhm_tools->formRename($form->getName(), $dataSearch),
                            $this->fhm_tools->getUrl(
                                'fhm_api_event_detail',
                                array(
                                    'template' => $template,
                                    'group' => $id,
                                    'rows' => $rows,
                                    'pagination' => $pagination,
                                )
                            )
                        ) :
                        $this->fhm_tools->getPagination(
                            1,
                            count($documents),
                            $this->fhm_tools->dmRepository("FhmEventBundle:Event")->getFrontCount(
                                $dataSearch['search']
                            ),
                            'pagination',
                            $this->fhm_tools->formRename($form->getName(), $dataSearch),
                            $this->fhm_tools->getUrl(
                                'fhm_api_event_detail',
                                array(
                                    'template' => $template,
                                    'group' => $id,
                                    'rows' => $rows,
                                    'pagination' => $pagination,
                                )
                            )
                        );
                }
            }
        }

        return new Response(
            $this->renderView(
                "::FhmEvent/Template/".$template.".html.twig",
                array(
                    'document' => $document,
                    'documents' => $documents,
                    'pagination' => $pagination ? $pagination : array(),
                    'instance' => $instance,
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
        $instance = $this->fhm_tools->instanceData();
        $events = array();
        $aEvents = $this->fhm_tools->dmRepository()->getEventEnable($instance->grouping->current);
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
        // ERROR - Unknown route
        if (!$this->fhm_tools->routeExists('fhm_'.$this->route)) {
            throw $this->createNotFoundException($this->fhm_tools->trans('fhm.error.route', array(), 'FhmFhmBundle'));
        }
        $instance = $this->fhm_tools->instanceData();
        $classType = "\\Fhm\\FhmBundle\\Form\\Type\\Front\\SearchType";
        $form = $this->createForm($classType, null);
        $form->setData($this->get('request_stack')->get($form->getName()));
        $dataSearch = $form->getData();
        $dataPagination = $this->get('request_stack')->get('FhmPagination');
        // Ajax pagination request
        if (isset($dataPagination['pagination'])) {
            $documents = $this->fhm_tools->dmRepository()->getFrontDateIndex(
                $date,
                $dataSearch['search'],
                $dataPagination['pagination'],
                $this->fhm_tools->getParameter(array('pagination', 'front', 'page'), 'fhm_fhm'),
                $instance->grouping->current
            );

            return array(
                'documents' => $documents,
                'pagination' => $this->fhm_tools->getPagination(
                    $dataPagination['pagination'],
                    count($documents),
                    $this->fhm_tools->dmRepository()->getFrontDateCount(
                        $date,
                        $dataSearch['search'],
                        $instance->grouping->current
                    ),
                    'pagination',
                    $this->fhm_tools->formRename($form->getName(), $dataSearch),
                    $this->fhm_tools->getUrl('fhm_api_event_date', array('date' => $date))
                ),
                'instance' => $instance,
            );
        } // Router request
        else {
            $documents = $this->fhm_tools->dmRepository()->getFrontDateIndex(
                $date,
                $dataSearch['search'],
                1,
                $this->fhm_tools->getParameter(array('pagination', 'front', 'page'), 'fhm_fhm'),
                $instance->grouping->current
            );

            return array(
                'documents' => $documents,
                'pagination' => $this->fhm_tools->getPagination(
                    1,
                    count($documents),
                    $this->fhm_tools->dmRepository()->getFrontDateCount(
                        $date,
                        $dataSearch['search'],
                        $instance->grouping->current
                    ),
                    'pagination',
                    $this->fhm_tools->formRename($form->getName(), $dataSearch),
                    $this->fhm_tools->getUrl('fhm_api_event_date', array('date' => $date))
                ),
                'form' => $form->createView(),
                'instance' => $instance,
            );
        }
    }
}