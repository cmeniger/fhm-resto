<?php
namespace Fhm\FhmBundle\Controller;

use Fhm\FhmBundle\Document\Fhm;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * All admin class should extend this class
 * Class RefAdminController
 * @package Fhm\FhmBundle\Controller
 */
class RefAdminController extends Controller
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
        $datas = $this->fhm_tools->initData($src, $bundle, $route, $document, 'admin');
        $this->source = $datas['source'];
        $this->class = $datas['class'];
        $this->document = $datas['document'];
        $this->translation = $datas['translation'];
        $this->route = $datas['route'];
        $this->form = $datas['form'];
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
        if (!$this->fhm_tools->routeExists($this->source.'_admin_'.$this->route)) {
            throw $this->createNotFoundException($this->fhm_tools->trans('fhm.error.route', array(), 'FhmFhmBundle'));
        }
        $instance = $this->fhm_tools->instanceData();
        $classType = $this->form->type->search;
        $form = $this->createForm(
            $classType,
            null,
            array(
                'translation_domain' => $instance->translation
            )
        );
        $request = $this->get('request_stack')->getCurrentRequest();
        $dataSearch = $request->get('FhmSearch');
        $dataPagination = $request->get('FhmPagination');
        $dataSort = $request->get('FhmSort') ? $request->get('FhmSort') : $this->fhm_tools->getSort();
        // Ajax request
        if ($request->isXmlHttpRequest()) {
            $this->fhm_tools->setSort($dataSort['field'], $dataSort['order']);
            $dataSort = $this->fhm_tools->getSort($dataSort['field'], $dataSort['order']);
            $documents = $this->fhm_tools->dmRepository()
                ->setSort($dataSort->field, $dataSort->order)
                ->getAdminIndex(
                    $dataSearch['search'],
                    $dataPagination['pagination'],
                    $this->fhm_tools->getParameters(array('pagination', 'admin', 'page'), 'fhm_fhm'),
                    $instance->grouping->filtered,
                    $instance->user->super
                );

            return array(
                'documents' => $documents,
                'pagination' => $this->fhm_tools->getPagination(
                    $dataPagination['pagination'],
                    count($documents),
                    $this->fhm_tools->dmRepository()->getAdminCount(
                        $dataSearch['search'],
                        $instance->grouping->filtered,
                        $instance->user->super
                    ),
                    'pagination',
                    $this->fhm_tools->formRename($form->getName(), $dataSearch)
                ),
                'sort' => $dataSort,
                'instance' => $instance,
            );
        } else {
            $documents = $this->fhm_tools->dmRepository()->setSort($dataSort->field, $dataSort->order)->getAdminIndex(
                $dataSearch['search'],
                1,
                $this->fhm_tools->getParameters(array('pagination', 'admin', 'page'), 'fhm_fhm'),
                $instance->grouping->filtered,
                $instance->user->super
            );

            return array(
                'documents' => $documents,
                'pagination' => $this->fhm_tools->getPagination(
                    1,
                    count($documents),
                    $this->fhm_tools->dmRepository()->getAdminCount(
                        $dataSearch['search'],
                        $instance->grouping->filtered,
                        $instance->user->super
                    ),
                    'pagination',
                    $this->fhm_tools->formRename($form->getName(), $dataSearch)
                ),
                'sort' => $dataSort,
                'form' => $form->createView(),
                'instance' => $instance,
                'breadcrumbs' => array(
                    array(
                        'link' => $this->fhm_tools->getUrl('project_home'),
                        'text' => $this->fhm_tools->trans('project.home.breadcrumb', array(), 'ProjectDefaultBundle'),
                    ),
                    array(
                        'link' => $this->fhm_tools->getUrl('fhm_admin'),
                        'text' => $this->fhm_tools->trans('fhm.admin.breadcrumb', array(), 'FhmFhmBundle'),
                    ),
                    array(
                        'link' => $this->fhm_tools->getUrl($this->source.'_admin_'.$this->route),
                        'text' => $this->fhm_tools->trans('.admin.index.breadcrumb'),
                        'current' => true,
                    ),
                ),
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
        if (!$this->fhm_tools->routeExists($this->source.'_admin_'.$this->route) ||
            !$this->fhm_tools->routeExists($this->source.'_admin_'.$this->route.'_create')
        ) {
            throw $this->createNotFoundException($this->fhm_tools->trans('fhm.error.route', array(), 'FhmFhmBundle'));
        }
        $document = $this->document;
        $instance = $this->fhm_tools->instanceData();
        $classType = $this->form->type->create;
        $classHandler = $this->form->handler->create;
        $form = $this->createForm(
            $classType,
            $document,
            array(
                'data_class' => $instance->class,
                'translation_domain' => $instance->translation
            )
        );
        $handler = new $classHandler($form, $request);
        $process = $handler->process();
        if ($process) {
            $data = $request->get($form->getName());
            // Persist
            $document->setUserCreate($this->getUser());
            $document->setAlias($this->fhm_tools->getAlias($document->getId(), $document->getName()));
            $this->fhm_tools->dmPersist($document);
            // Message
            $this->get('session')->getFlashBag()->add('notice', $this->fhm_tools->trans('.admin.create.flash.ok'));
            // Redirect
            $redirect = $this->redirect($this->fhm_tools->getUrl($this->source.'_admin_'.$this->route));
            $redirect = isset($data['submitSave']) ?
                $this->redirect(
                    $this->fhm_tools->getUrl(
                        $this->source.'_admin_'.$this->route.'_update',
                        array('id' => $document->getId())
                    )
                ) :
                $redirect;
            $redirect = isset($data['submitDuplicate']) ?
                $this->redirect(
                    $this->fhm_tools->getUrl(
                        $this->source.'_admin_'.$this->route.'_duplicate',
                        array('id' => $document->getId())
                    )
                ) :
                $redirect;
            $redirect = isset($data['submitNew']) ?
                $this->redirect(
                    $this->fhm_tools->getUrl($this->source.'_admin_'.$this->route.'_create')
                ) :
                $redirect;
            $redirect = isset($data['submitConfig']) ?
                $this->redirect(
                    $this->fhm_tools->getUrl(
                        $this->source.'_admin_'.$this->route.'_detail',
                        array('id' => $document->getId())
                    )
                ) :
                $redirect;

            return $redirect;
        }

        return array(
            'form' => $form->createView(),
            'instance' => $instance,
            'breadcrumbs' => array(
                array(
                    'link' => $this->fhm_tools->getUrl('project_home'),
                    'text' => $this->fhm_tools->trans('project.home.breadcrumb', array(), 'ProjectDefaultBundle'),
                ),
                array(
                    'link' => $this->fhm_tools->getUrl('fhm_admin'),
                    'text' => $this->fhm_tools->trans('fhm.admin.breadcrumb', array(), 'FhmFhmBundle'),
                ),
                array(
                    'link' => $this->fhm_tools->getUrl($this->source.'_admin_'.$this->route),
                    'text' => $this->fhm_tools->trans('.admin.index.breadcrumb'),
                ),
                array(
                    'link' => $this->fhm_tools->getUrl($this->source.'_admin_'.$this->route.'_create'),
                    'text' => $this->fhm_tools->trans('.admin.create.breadcrumb'),
                    'current' => true,
                ),
            ),
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
        if (!$this->fhm_tools->routeExists($this->source.'_admin_'.$this->route) ||
            !$this->fhm_tools->routeExists($this->source.'_admin_'.$this->route.'_duplicate')
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
     */
    public function updateAction(Request $request, $id)
    {
        // ERROR - Unknown route
        if (!$this->fhm_tools->routeExists($this->source.'_admin_'.$this->route) ||
            !$this->fhm_tools->routeExists($this->source.'_admin_'.$this->route.'_update')
        ) {
            throw $this->createNotFoundException($this->fhm_tools->trans('fhm.error.route', array(), 'FhmFhmBundle'));
        }
        $document = $this->fhm_tools->dmRepository()->find($id);
        $instance = $this->fhm_tools->instanceData($document);
        $classType = $this->form->type->update;
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
        $this->fhm_tools->historic($document);
        $form = $this->createForm(
            $classType,
            $document,
            array(
                'data_class' => $instance->class,
                'translation_domain' => $instance->translation
            )
        );        $handler = new $classHandler($form, $request);
        $process = $handler->process();
        if ($process) {
            $data = $request->get($form->getName());
            // Persist
            $document->setUserUpdate($this->getUser());
            //            $document->setAlias($this->fhm_tools->getAlias($document->getId(), $document->getName()));
            $this->fhm_tools->dmPersist($document);
            // Historic
            $this->fhm_tools->historicAdd($document);
            // Message
            $this->get('session')->getFlashBag()->add('notice', $this->fhm_tools->trans('.admin.update.flash.ok'));
            // Redirect
            $redirect = $this->redirect($this->fhm_tools->getUrl($this->source.'_admin_'.$this->route));
            $redirect = isset($data['submitSave']) ?
                $this->redirect(
                    $this->fhm_tools->getUrl(
                        $this->source.'_admin_'.$this->route.'_update',
                        array('id' => $document->getId())
                    )
                ) :
                $redirect;
            $redirect = isset($data['submitDuplicate']) ?
                $this->redirect(
                    $this->fhm_tools->getUrl(
                        $this->source.'_admin_'.$this->route.'_duplicate',
                        array('id' => $document->getId())
                    )
                ) :
                $redirect;
            $redirect = isset($data['submitNew']) ?
                $this->redirect(
                    $this->fhm_tools->getUrl($this->source.'_admin_'.$this->route.'_create')
                ) :
                $redirect;
            $redirect = isset($data['submitConfig']) ?
                $this->redirect(
                    $this->fhm_tools->getUrl(
                        $this->source.'_admin_'.$this->route.'_detail',
                        array('id' => $document->getId())
                    )
                ) :
                $redirect;

            return $redirect;
        }

        return array(
            'document' => $document,
            'form' => $form->createView(),
            'instance' => $instance,
            'breadcrumbs' => array(
                array(
                    'link' => $this->fhm_tools->getUrl('project_home'),
                    'text' => $this->fhm_tools->trans('project.home.breadcrumb', array(), 'ProjectDefaultBundle'),
                ),
                array(
                    'link' => $this->fhm_tools->getUrl('fhm_admin'),
                    'text' => $this->fhm_tools->trans('fhm.admin.breadcrumb', array(), 'FhmFhmBundle'),
                ),
                array(
                    'link' => $this->fhm_tools->getUrl($this->source.'_admin_'.$this->route),
                    'text' => $this->fhm_tools->trans('.admin.index.breadcrumb'),
                ),
                array(
                    'link' => $this->fhm_tools->getUrl(
                        $this->source.'_admin_'.$this->route.'_detail',
                        array('id' => $id)
                    ),
                    'text' => $this->fhm_tools->trans(
                        '.admin.detail.breadcrumb',
                        array('%name%' => $document->getName())
                    ),
                ),
                array(
                    'link' => $this->fhm_tools->getUrl(
                        $this->source.'_admin_'.$this->route.'_update',
                        array('id' => $id)
                    ),
                    'text' => $this->fhm_tools->trans('.admin.update.breadcrumb'),
                    'current' => true,
                ),
            ),
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
        if (!$this->fhm_tools->routeExists($this->source.'_admin_'.$this->route) ||
            !$this->fhm_tools->routeExists($this->source.'_admin_'.$this->route.'_detail')
        ) {
            throw $this->createNotFoundException($this->fhm_tools->trans('fhm.error.route', array(), 'FhmFhmBundle'));
        }
        $document = $this->fhm_tools->dmRepository()->find($id);
        $instance = $this->fhm_tools->instanceData($document);
        // ERROR - unknown
        if ($document == "") {
            throw $this->createNotFoundException($this->fhm_tools->trans('.error.unknown'));
        }

        return array(
            'document' => $document,
            'instance' => $instance,
            'historics' => $this->fhm_tools->historicData($document),
            'paginationHistoric' => $this->fhm_tools->historicPagination($document),
            'breadcrumbs' => array(
                array(
                    'link' => $this->fhm_tools->getUrl('project_home'),
                    'text' => $this->fhm_tools->trans('project.home.breadcrumb', array(), 'ProjectDefaultBundle'),
                ),
                array(
                    'link' => $this->fhm_tools->getUrl('fhm_admin'),
                    'text' => $this->fhm_tools->trans('fhm.admin.breadcrumb', array(), 'FhmFhmBundle'),
                ),
                array(
                    'link' => $this->fhm_tools->getUrl($this->source.'_admin_'.$this->route),
                    'text' => $this->fhm_tools->trans('.admin.index.breadcrumb'),
                ),
                array(
                    'link' => $this->fhm_tools->getUrl(
                        $this->source.'_admin_'.$this->route.'_detail',
                        array('id' => $id)
                    ),
                    'text' => $this->fhm_tools->trans(
                        '.admin.detail.breadcrumb',
                        array('%name%' => $document->getName())
                    ),
                    'current' => true,
                ),
            ),
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
        if (!$this->fhm_tools->routeExists($this->source.'_admin_'.$this->route)) {
            throw $this->createNotFoundException($this->fhm_tools->trans('fhm.error.route', array(), 'FhmFhmBundle'));
        }
        $document = $this->fhm_tools->dmRepository()->find($id);
        // ERROR - Unknown
        if ($document == "") {
            throw $this->createNotFoundException($this->fhm_tools->trans('.error.unknown'));
        } elseif ($document->getDelete() && !$this->getUser()->isSuperAdmin()) {
            throw new HttpException(403, $this->fhm_tools->trans('.error.forbidden'));
        }
        // Delete
        if ($document->getDelete()) {
            $this->fhm_tools->dmRemove($document);
            // Message
            $this->get('session')->getFlashBag()->add('notice', $this->fhm_tools->trans('.admin.delete.flash.ok'));
        } else {
            $document->setDelete(true);
            $this->fhm_tools->dmPersist($document);
            // Message
            $this->get('session')->getFlashBag()->add('notice', $this->fhm_tools->trans('.admin.delete.flash.ok'));
        }

        return $this->redirect($this->fhm_tools->getUrl($this->source.'_admin_'.$this->route));
    }

    /**
     * @param $id
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function undeleteAction($id)
    {
        // ERROR - Unknown route
        if (!$this->fhm_tools->routeExists($this->source.'_admin_'.$this->route)) {
            throw $this->createNotFoundException($this->fhm_tools->trans('fhm.error.route', array(), 'FhmFhmBundle'));
        }
        $document = $this->fhm_tools->dmRepository()->find($id);
        // ERROR - Unknown
        if ($document == "") {
            throw $this->createNotFoundException($this->fhm_tools->trans('.error.unknown'));
        } elseif ($document->getDelete() && !$this->getUser()->isSuperAdmin()) {
            throw new HttpException(403, $this->fhm_tools->trans('.error.forbidden'));
        }
        // Undelete
        $document->setDelete(false);
        $this->fhm_tools->dmPersist($document);
        // Message
        $this->get('session')->getFlashBag()->add('notice', $this->fhm_tools->trans('.admin.undelete.flash.ok'));

        return $this->redirect($this->fhm_tools->getUrl($this->source.'_admin_'.$this->route));
    }

    /**
     * @param $id
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function activateAction($id)
    {
        // ERROR - Unknown route
        if (!$this->fhm_tools->routeExists($this->source.'_admin_'.$this->route)) {
            throw $this->createNotFoundException($this->fhm_tools->trans('fhm.error.route', array(), 'FhmFhmBundle'));
        }
        $document = $this->fhm_tools->dmRepository()->find($id);
        // ERROR - Unknown
        if ($document == "") {
            throw $this->createNotFoundException($this->fhm_tools->trans('.error.unknown'));
        }
        // Deactivate
        $document->setActive(true);
        $this->fhm_tools->dmPersist($document);
        // Message
        $this->get('session')->getFlashBag()->add('notice', $this->fhm_tools->trans('.admin.activate.flash.ok'));

        return $this->redirect($this->fhm_tools->getUrl($this->source.'_admin_'.$this->route));
    }

    /**
     * @param $id
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deactivateAction($id)
    {
        // ERROR - Unknown route
        if (!$this->fhm_tools->routeExists($this->source.'_admin_'.$this->route)) {
            throw $this->createNotFoundException($this->fhm_tools->trans('fhm.error.route', array(), 'FhmFhmBundle'));
        }
        $document = $this->fhm_tools->dmRepository()->find($id);
        // ERROR - Unknown
        if ($document == "") {
            throw $this->createNotFoundException($this->fhm_tools->trans('.error.unknown'));
        }
        // Deactivate
        $document->setActive(false);
        $this->fhm_tools->dmPersist($document);
        // Message
        $this->get('session')->getFlashBag()->add('notice', $this->fhm_tools->trans('.admin.deactivate.flash.ok'));

        return $this->redirect($this->fhm_tools->getUrl($this->source.'_admin_'.$this->route));
    }

    /**
     * @param Request $request
     *
     * @return array
     */
    public function importAction(Request $request)
    {
        // ERROR - Unknown route
        if (!$this->fhm_tools->routeExists($this->source.'_admin_'.$this->route) ||
            !$this->fhm_tools->routeExists($this->source.'_admin_'.$this->route.'_import')
        ) {
            throw $this->createNotFoundException($this->fhm_tools->trans('fhm.error.route', array(), 'FhmFhmBundle'));
        }
        $instance = $this->fhm_tools->instanceData();
        $classType = $this->form->type->import;
        $classHandler = $this->form->handler->import;
        $form = $this->createForm(
            $classType,
            null,
            array(
                'translation_domain' => $instance->translation
            )
        );
        $handler = new $classHandler($form, $request);
        $process = $handler->process();
        if ($datas = $process) {
            $count = array(0, 0, 0);
            foreach ($datas as $data) {
                if (!isset($data['alias'])) {
                    $data['alias'] = $this->fhm_tools->getAlias(isset($data['id']) ? $data['id'] : null, $data['name']);
                }
                $document = $this->fhm_tools->dmRepository()->getImport($data);
                // Counter
                if ($document === 'error') {
                    $count[2]++;
                } elseif ($document) {
                    $count[1]++;
                    $document->setCsvData($data);
                    $this->fhm_tools->dmPersist($document);
                } else {
                    $count[0]++;
                    $document = new $this->class();
                    $document->setCsvData($data);
                    $this->fhm_tools->dmPersist($document);
                }
            }
            // Message
            $this->get('session')->getFlashBag()->add(
                'notice',
                $this->fhm_tools->trans(
                    '.admin.import.flash.ok',
                    array('%countAdd%' => $count[0], '%countUpdate%' => $count[1], '%countError%' => $count[2])
                )
            );
        }

        return array(
            'form' => $form->createView(),
            'instance' => $instance,
            'breadcrumbs' => array(
                array(
                    'link' => $this->fhm_tools->getUrl('project_home'),
                    'text' => $this->fhm_tools->trans('project.home.breadcrumb', array(), 'ProjectDefaultBundle'),
                ),
                array(
                    'link' => $this->fhm_tools->getUrl('fhm_admin'),
                    'text' => $this->fhm_tools->trans('fhm.admin.breadcrumb', array(), 'FhmFhmBundle'),
                ),
                array(
                    'link' => $this->fhm_tools->getUrl($this->source.'_admin_'.$this->route),
                    'text' => $this->fhm_tools->trans('.admin.index.breadcrumb'),
                ),
                array(
                    'link' => $this->fhm_tools->getUrl($this->source.'_admin_'.$this->route.'_import'),
                    'text' => $this->fhm_tools->trans('.admin.import.breadcrumb'),
                    'current' => true,
                ),
            ),
        );
    }

    /**
     * @param Request $request
     *
     * @return array|\Symfony\Component\HttpFoundation\Response
     */
    public function exportAction(Request $request)
    {
        // ERROR - Unknown route
        if (!$this->fhm_tools->routeExists($this->source.'_admin_'.$this->route) ||
            !$this->fhm_tools->routeExists($this->source.'_admin_'.$this->route.'_export')
        ) {
            throw $this->createNotFoundException($this->fhm_tools->trans('fhm.error.route', array(), 'FhmFhmBundle'));
        }
        $instance = $this->fhm_tools->instanceData();
        $classType = $this->form->type->export;
        $classHandler = $this->form->handler->export;
        $form = $this->createForm(
            $classType,
            null,
            array(
                'translation_domain' => $instance->translation
            )
        );        $handler = new $classHandler($form, $request);
        $process = $handler->process();
        if ($process) {
            $documents = $this->fhm_tools->dmRepository()->getExport();
            $datas = array($this->document->getCsvHeader());
            foreach ($documents as $document) {
                $datas[] = $document->getCsvData();
            }

            return $this->fhm_tools->csvExport($datas);
        }

        return array(
            'form' => $form->createView(),
            'instance' => $instance,
            'breadcrumbs' => array(
                array(
                    'link' => $this->fhm_tools->getUrl('project_home'),
                    'text' => $this->fhm_tools->trans('project.home.breadcrumb', array(), 'ProjectDefaultBundle'),
                ),
                array(
                    'link' => $this->fhm_tools->getUrl('fhm_admin'),
                    'text' => $this->fhm_tools->trans('fhm.admin.breadcrumb', array(), 'FhmFhmBundle'),
                ),
                array(
                    'link' => $this->fhm_tools->getUrl($this->source.'_admin_'.$this->route),
                    'text' => $this->fhm_tools->trans('.admin.index.breadcrumb'),
                ),
                array(
                    'link' => $this->fhm_tools->getUrl($this->source.'_admin_'.$this->route.'_export'),
                    'text' => $this->fhm_tools->trans('.admin.export.breadcrumb'),
                    'current' => true,
                ),
            ),
        );
    }

    /**
     * @param Request $request
     *
     * @return Response
     */
    public function groupingAction(Request $request)
    {
        $groupings = json_decode($request->get('list'));
        $document = $this->fhm_tools->dmRepository()->find($request->get('id'));
        $document->resetGrouping();
        foreach ($groupings as $key => $grouping) {
            $document->addGrouping($grouping->id);
        }
        $this->fhm_tools->dmPersist($document);

        return new Response();
    }
}