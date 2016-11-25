<?php
namespace Fhm\FhmBundle\Controller;

use Fhm\FhmBundle\Document\Fhm;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * Class RefFrontController
 * @package Fhm\FhmBundle\Controller
 */
class RefFrontController extends Controller
{
    protected $fhm_tools;
    protected $source;
    protected $class;
    protected $document;
    protected $translation;
    protected $route;
    protected $form;
    protected $container;

    /**
     * @param string $src
     * @param string $bundle
     * @param string $route
     * @param string $document
     */
    public function __construct($src = 'Fhm', $bundle = 'Fhm', $route = '', $document = '')
    {
        $datas             = $this->fhm_tools->initData($src, $bundle, $route, $document, 'front');
        $this->source      = $datas['source'];
        $this->class       = $datas['class'];
        $this->document    = $datas['document'];
        $this->translation = $datas['translation'];
        $this->route       = $datas['route'];
        $this->form        = $datas['form'];
    }

    /**
     * @param \Fhm\FhmBundle\Services\Tools $tools
     *
     * @return $this
     */
    public function setFhmTools(\Fhm\FhmBundle\Services\Tools $tools)
    {
        $this->setContainer($tools->getContainer());
        $this->fhm_tools = $tools;

        return $this;
    }

    /**
     * @return array
     */
    public function indexAction()
    {
        // ERROR - Unknown route
        if (!$this->fhm_tools->routeExists($this->source . '_' . $this->route)) {
            throw $this->createNotFoundException($this->fhm_tools->trans('fhm.error.route', array(), 'FhmFhmBundle'));
        }
        $instance  = $this->fhm_tools->instanceData();
        $classType = $this->form->type->search;
        $form      = $this->createForm($classType);
        $form->setData($this->get('request_stack')->get($form->getName()));
        $dataSearch     = $form->getData();
        $dataPagination = $this->get('request_stack')->get('FhmPagination');
        // Ajax pagination request
        if (isset($dataPagination['pagination'])) {
            $documents = $this->fhm_tools->dmRepository()->getFrontIndex(
                $dataSearch['search'],
                $dataPagination['pagination'],
                $this->fhm_tools->getParameters(array('pagination', 'front', 'page'), 'fhm_fhm'),
                $instance->grouping->current
            );

            return array(
                'documents'  => $documents,
                'pagination' => $this->fhm_tools->getPagination(
                    $dataPagination['pagination'],
                    count($documents),
                    $this->fhm_tools->dmRepository()->getFrontCount(
                        $dataSearch['search'],
                        $instance->grouping->current
                    ),
                    'pagination',
                    $this->fhm_tools->formRename($form->getName(), $dataSearch)
                ),
                'instance'   => $instance,
            );
        } else {
            $documents = $this->fhm_tools->dmRepository()->getFrontIndex(
                $dataSearch['search'],
                1,
                $this->fhm_tools->getParameters(array('pagination', 'front', 'page'), 'fhm_fhm'),
                $instance->grouping->current
            );

            return array(
                'documents'   => $documents,
                'pagination'  => $this->fhm_tools->getPagination(
                    1,
                    count($documents),
                    $this->fhm_tools->dmRepository()->getFrontCount(
                        $dataSearch['search'],
                        $instance->grouping->current
                    ),
                    'pagination',
                    $this->fhm_tools->formRename($form->getName(), $dataSearch)
                ),
                'form'        => $form->createView(),
                'instance'    => $instance,
                'breadcrumbs' => array(
                    array(
                        'link' => $this->fhm_tools->getUrl('project_home'),
                        'text' => $this->fhm_tools->trans('project.home.breadcrumb', array(), 'ProjectDefaultBundle'),
                    ),
                    array(
                        'link' => $this->fhm_tools->getUrl($this->source . '_' . $this->route),
                        'text' => $this->fhm_tools->trans('.front.index.breadcrumb'),
                        'current' => true
                    )
                )
            );
        }
    }

    /**
     * @param Request $request
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function createAction(Request $request)
    {
        // ERROR - Unknown route
        if (!$this->fhm_tools->routeExists($this->source . '_' . $this->route) ||
            !$this->fhm_tools->routeExists($this->source . '_' . $this->route . '_create')
        ) {
            throw $this->createNotFoundException($this->fhm_tools->trans('fhm.error.route', array(), 'FhmFhmBundle'));
        }
        $document     = $this->document;
        $instance     = $this->fhm_tools->instanceData();
        $classType    = $this->form->type->create;
        $classHandler = $this->form->handler->create;
        $form           = $this->createForm($classType, $document);
        $handler      = new $classHandler($form, $request);
        $process      = $handler->process();
        if ($process) {
            $data = $request->get($form->getName());
            // Persist
            $document->setUserCreate($this->getUser());
            $document->setAlias($this->getAlias($document->getId(), $document->getName()));
            $document->addGrouping($instance->grouping->current);
            $this->fhm_tools->dmPersist($document);
            // Message
            $this->get('session')->getFlashBag()->add('notice', $this->fhm_tools->trans('.front.create.flash.ok'));
            // Redirect
            $redirect = $this->redirect($this->fhm_tools->getUrl($this->source . '_' . $this->route));
            $redirect = isset($data['submitDuplicate']) ?
                $this->redirect(
                    $this->fhm_tools->getUrl(
                        $this->source . '_' . $this->route . '_duplicate',
                        array('id' => $document->getId())
                    )
                ) :
                $redirect;
            $redirect = isset($data['submitNew']) ?
                $this->redirect($this->fhm_tools->getUrl($this->source . '_' . $this->route . '_create')) :
                $redirect;

            return $redirect;
        }

        return array(
            'form'        => $form->createView(),
            'instance'    => $this->fhm_tools->instanceData(),
            'breadcrumbs' => array(
                array(
                    'link' => $this->fhm_tools->getUrl('project_home'),
                    'text' => $this->fhm_tools->trans('project.home.breadcrumb', array(), 'ProjectDefaultBundle'),
                ),
                array(
                    'link' => $this->fhm_tools->getUrl($this->source . '_' . $this->route),
                    'text' => $this->fhm_tools->trans('.front.index.breadcrumb'),
                ),
                array(
                    'link'    => $this->fhm_tools->getUrl($this->source . '_' . $this->route . '_create'),
                    'text'    => $this->fhm_tools->trans('.front.create.breadcrumb'),
                    'current' => true
                )
            )
        );
    }

    /**
     * @param Request $request
     * @param         $id
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function duplicateAction(Request $request, $id)
    {
        // ERROR - Unknown route
        if (!$this->fhm_tools->routeExists($this->source . '_' . $this->route) ||
            !$this->fhm_tools->routeExists($this->source . '_' . $this->route . '_duplicate')
        ) {
            throw $this->createNotFoundException($this->fhm_tools->trans('fhm.error.route', array(), 'FhmFhmBundle'));
        }
        $document = $this->fhm_tools->dmRepository()->find($id);
        // ERROR - unknown
        if ($document == "") {
            throw $this->createNotFoundException($this->fhm_tools->trans('.error.unknown'));
        }
        $this->document = $document;

        return $this->createAction($request);
    }

    /**
     * @param Request $request
     * @param         $id
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     * @throws HttpException
     */
    public function updateAction(Request $request, $id)
    {
        // ERROR - Unknown route
        if(!$this->fhm_tools->routeExists($this->source . '_' . $this->route) ||
           !$this->fhm_tools->routeExists($this->source . '_' . $this->route . '_update')
        ) {
            throw $this->createNotFoundException($this->fhm_tools->trans('fhm.error.route', array(), 'FhmFhmBundle'));
        }
        $document     = $this->fhm_tools->dmRepository()->find($id);
        $instance     = $this->fhm_tools->instanceData($document);
        $classType    = $this->form->type->update;
        $classHandler = $this->form->handler->update;
        // ERROR - unknown
        if ($document == "") {
            throw $this->createNotFoundException($this->fhm_tools->trans('.error.unknown'));
        }
        if (!$instance->user->admin && $instance->grouping->different) {
            throw new HttpException(403, $this->fhm_tools->trans('.error.forbidden'));
        }
        if (!$instance->user->super && $document->getDelete()) {
            throw new HttpException(403, $this->fhm_tools->trans('.error.forbidden'));
        }
        $form    = $this->createForm($classType, $document);

        $handler = new $classHandler($form, $request);
        $process = $handler->process($document, $this->fhm_tools->dm(), $this->bundle);
        if ($process) {
            // Persist
            $document->setUserUpdate($this->getUser());
            $document->setAlias($this->getAlias($document->getId(), $document->getName()));
            $document->setActive(false);
            $this->fhm_tools->dmPersist($document);
            // Message
            $this->get('session')->getFlashBag()->add('notice', $this->fhm_tools->trans('.front.update.flash.ok'));
            // Redirect
            $redirect = $this->redirect($this->fhm_tools->getUrl($this->source . '_' . $this->route));
            $redirect = isset($data['submitDuplicate']) ?
                $this->redirect(
                    $this->fhm_tools->getUrl(
                        $this->source . '_' . $this->route . '_duplicate',
                        array('id' => $document->getId())
                    )
                ) :
                $redirect;

            $redirect = isset($data['submitNew']) ?
                $this->redirect($this->fhm_tools->getUrl($this->source . '_' . $this->route . '_create')) :
                $redirect;

            return $redirect;
        }

        return array(
            'document'    => $document,
            'form'        => $form->createView(),
            'instance'    => $instance,
            'breadcrumbs' => array(
                array(
                    'link' => $this->fhm_tools->getUrl('project_home'),
                    'text' => $this->fhm_tools->trans('project.home.breadcrumb', array(), 'ProjectDefaultBundle'),
                ),
                array(
                    'link' => $this->fhm_tools->getUrl($this->source . '_' . $this->route),
                    'text' => $this->fhm_tools->trans('.front.index.breadcrumb'),
                ),
                array(
                    'link' => $this->fhm_tools->getUrl(
                        $this->source . '_' . $this->route . '_detail',
                        array('id' => $id)
                    ),
                    'text' => $this->fhm_tools->trans(
                        '.front.detail.breadcrumb',
                        array('%name%' => $document->getName())
                    ),
                ),
                array(
                    'link'    => $this->fhm_tools->getUrl(
                        $this->source . '_' . $this->route . '_update',
                        array('id' => $id)
                    ),
                    'text'    => $this->fhm_tools->trans('.front.update.breadcrumb'),
                    'current' => true
                )
            )
        );
    }

    /**
     * @param $id
     *
     * @return array
     */
    public function detailAction($id)
    {
        // ERROR - Unknown route
        if (!$this->fhm_tools->routeExists($this->source . '_' . $this->route) ||
           !$this->fhm_tools->routeExists($this->source . '_' . $this->route . '_detail')
        ) {
            throw $this->createNotFoundException($this->fhm_tools->trans('fhm.error.route', array(), 'FhmFhmBundle'));
        }
        $document = $this->fhm_tools->dmRepository()->getById($id);
        $document = ($document) ? $document : $this->fhm_tools->dmRepository()->getByAlias($id);
        $document = ($document) ? $document : $this->fhm_tools->dmRepository()->getByName($id);
        $instance = $this->fhm_tools->instanceData($document);
        // ERROR - unknown
        if ($document == "") {
            throw $this->createNotFoundException($this->fhm_tools->trans('.error.unknown'));
        } elseif (!$instance->user->admin && ($document->getDelete() || !$document->getActive())) {
            throw new HttpException(403, $this->fhm_tools->trans('.error.forbidden'));
        }
        // Change grouping
        if ($instance->grouping->different && $document->getGrouping()) {
            $this->get($this->fhm_tools->getParameters("grouping", "fhm_fhm"))
                ->setGrouping($document->getFirstGrouping());
        }

        return array(
            'document'    => $document,
            'instance'    => $instance,
            'breadcrumbs' => array(
                array(
                    'link' => $this->fhm_tools->getUrl('project_home'),
                    'text' => $this->fhm_tools->trans('project.home.breadcrumb', array(), 'ProjectDefaultBundle'),
                ),
                array(
                    'link' => $this->fhm_tools->getUrl($this->source . '_' . $this->route),
                    'text' => $this->fhm_tools->trans('.front.index.breadcrumb'),
                ),
                array(
                    'link'    => $this->fhm_tools->getUrl(
                        $this->source . '_' . $this->route . '_detail',
                        array('id' => $id)
                    ),
                    'text'    => $this->fhm_tools->trans(
                        '.front.detail.breadcrumb',
                        array('%name%' => $document->getName())
                    ),
                    'current' => true
                )
            )
        );
    }

    /**
     * @param $id
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteAction($id)
    {
        // ERROR - Unknown route
        if (!$this->fhm_tools->routeExists($this->source . '_' . $this->route)) {
            throw $this->createNotFoundException($this->fhm_tools->trans('fhm.error.route', array(), 'FhmFhmBundle'));
        }
        $document = $this->fhm_tools->dmRepository()->find($id);
        $instance = $this->fhm_tools->instanceData($document);
        // ERROR - Unknown
        if ($document == "") {
            throw $this->createNotFoundException($this->fhm_tools->trans('.error.unknown'));
        } elseif (!$instance->user->admin) {
            throw new HttpException(403, $this->fhm_tools->trans('.error.forbidden'));
        }
        // Delete
        $document->setDelete(true);
        $this->fhm_tools->dmPersist($document);
        // Message
        $this->get('session')->getFlashBag()->add('notice', $this->fhm_tools->trans('.front.delete.flash.ok'));

        return $this->redirect($this->fhm_tools->getUrl($this->source . '_' . $this->route));
    }
}