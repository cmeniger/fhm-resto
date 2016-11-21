<?php
namespace Fhm\FhmBundle\Controller;

use Fhm\FhmBundle\Document\Fhm;
use Fhm\FhmBundle\Controller\FhmController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

class RefAdminController extends FhmController
{
    /**
     * @param string $src
     * @param string $bundle
     * @param string $route
     * @param string $document
     */
    public function __construct($src = 'Fhm', $bundle = 'Fhm', $route = '', $document = '')
    {
        parent::__construct();
        $this->source      = strtolower($src);
        $this->repository  = $src . $bundle . 'Bundle:' . ($document ? $document : $bundle);
        $this->class       = $src . '\\' . $bundle . 'Bundle' . '\\Document\\' . ($document ? $document : $bundle);
        $this->document    = new $this->class();
        $this->translation = array($src . $bundle . 'Bundle', strtolower($bundle));
        $this->view        = $src . $bundle;
        $this->route       = $route;
        $this->bundle      = strtolower($bundle);
        $this->section     = "Admin";
        $this->initForm($src . '\\' . $bundle . 'Bundle');
    }

    /**
     * @return array
     */
    public function indexAction()
    {
        // ERROR - Unknown route
        if(!$this->routeExists($this->source . '_admin_' . $this->route))
        {
            throw $this->createNotFoundException($this->get('translator')->trans('fhm.error.route', array(), 'FhmFhmBundle'));
        }
        $instance       = $this->instanceData();
        $classType      = $this->form->type->search;
        $form           = $this->createForm(new $classType($instance), null);
        $request        = $this->get('request');
        $dataSearch     = $request->get('FhmSearch');
        $dataPagination = $request->get('FhmPagination');
        $dataSort       = $request->get('FhmSort') ? $request->get('FhmSort') : $this->getSort();
        // Ajax request
        if($request->isXmlHttpRequest())
        {
            $this->setSort($dataSort['field'], $dataSort['order']);
            $dataSort  = $this->getSort($dataSort['field'], $dataSort['order']);
            $documents = $this->dmRepository()->setSort($dataSort->field, $dataSort->order)->getAdminIndex($dataSearch['search'], $dataPagination['pagination'], $this->getParameters(array('pagination', 'admin', 'page'), 'fhm_fhm'), $instance->grouping->filtered, $instance->user->super);

            return array(
                'documents'  => $documents,
                'pagination' => $this->getPagination($dataPagination['pagination'], count($documents), $this->dmRepository()->getAdminCount($dataSearch['search'], $instance->grouping->filtered, $instance->user->super), 'pagination', $this->formRename($form->getName(), $dataSearch)),
                'sort'       => $dataSort,
                'instance'   => $instance,
            );
        }
        // Router request
        else
        {
            $documents = $this->dmRepository()->setSort($dataSort->field, $dataSort->order)->getAdminIndex($dataSearch['search'], 1, $this->getParameters(array('pagination', 'admin', 'page'), 'fhm_fhm'), $instance->grouping->filtered, $instance->user->super);

            return array(
                'documents'   => $documents,
                'pagination'  => $this->getPagination(1, count($documents), $this->dmRepository()->getAdminCount($dataSearch['search'], $instance->grouping->filtered, $instance->user->super), 'pagination', $this->formRename($form->getName(), $dataSearch)),
                'sort'        => $dataSort,
                'form'        => $form->createView(),
                'instance'    => $instance,
                'breadcrumbs' => array(
                    array(
                        'link' => $this->get('router')->generate('project_home'),
                        'text' => $this->get('translator')->trans('project.home.breadcrumb', array(), 'ProjectDefaultBundle'),
                    ),
                    array(
                        'link' => $this->get('router')->generate('fhm_admin'),
                        'text' => $this->get('translator')->trans('fhm.admin.breadcrumb', array(), 'FhmFhmBundle'),
                    ),
                    array(
                        'link'    => $this->get('router')->generate($this->source . '_admin_' . $this->route),
                        'text'    => $this->get('translator')->trans($this->translation[1] . '.admin.index.breadcrumb', array(), $this->translation[0]),
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
        if(!$this->routeExists($this->source . '_admin_' . $this->route) || !$this->routeExists($this->source . '_admin_' . $this->route . '_create'))
        {
            throw $this->createNotFoundException($this->get('translator')->trans('fhm.error.route', array(), 'FhmFhmBundle'));
        }
        $document     = $this->document;
        $instance     = $this->instanceData();
        $classType    = $this->form->type->create;
        $classHandler = $this->form->handler->create;
        $form         = $this->createForm(new $classType($instance, $document), $document);
        $handler      = new $classHandler($form, $request);
        $process      = $handler->process();
        if($process)
        {
            $data = $request->get($form->getName());
            // Persist
            $document->setUserCreate($this->getUser());
            $document->setAlias($this->getAlias($document->getId(), $document->getName()));
            $this->dmPersist($document);
            // Message
            $this->get('session')->getFlashBag()->add('notice', $this->get('translator')->trans($this->translation[1] . '.admin.create.flash.ok', array(), $this->translation[0]));
            // Redirect
            $redirect = $this->redirect($this->generateUrl($this->source . '_admin_' . $this->route));
            $redirect = isset($data['submitSave']) ? $this->redirect($this->generateUrl($this->source . '_admin_' . $this->route . '_update', array('id' => $document->getId()))) : $redirect;
            $redirect = isset($data['submitDuplicate']) ? $this->redirect($this->generateUrl($this->source . '_admin_' . $this->route . '_duplicate', array('id' => $document->getId()))) : $redirect;
            $redirect = isset($data['submitNew']) ? $this->redirect($this->generateUrl($this->source . '_admin_' . $this->route . '_create')) : $redirect;
            $redirect = isset($data['submitConfig']) ? $this->redirect($this->generateUrl($this->source . '_admin_' . $this->route . '_detail', array('id' => $document->getId()))) : $redirect;

            return $redirect;
        }

        return array(
            'form'        => $form->createView(),
            'instance'    => $instance,
            'breadcrumbs' => array(
                array(
                    'link' => $this->get('router')->generate('project_home'),
                    'text' => $this->get('translator')->trans('project.home.breadcrumb', array(), 'ProjectDefaultBundle'),
                ),
                array(
                    'link' => $this->get('router')->generate('fhm_admin'),
                    'text' => $this->get('translator')->trans('fhm.admin.breadcrumb', array(), 'FhmFhmBundle'),
                ),
                array(
                    'link' => $this->get('router')->generate($this->source . '_admin_' . $this->route),
                    'text' => $this->get('translator')->trans($this->translation[1] . '.admin.index.breadcrumb', array(), $this->translation[0]),
                ),
                array(
                    'link'    => $this->get('router')->generate($this->source . '_admin_' . $this->route . '_create'),
                    'text'    => $this->get('translator')->trans($this->translation[1] . '.admin.create.breadcrumb', array(), $this->translation[0]),
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
        if(!$this->routeExists($this->source . '_admin_' . $this->route) || !$this->routeExists($this->source . '_admin_' . $this->route . '_duplicate'))
        {
            throw $this->createNotFoundException($this->get('translator')->trans('fhm.error.route', array(), 'FhmFhmBundle'));
        }
        $document = $this->dmRepository()->find($id);
        // ERROR - unknown
        if($document == "")
        {
            throw $this->createNotFoundException($this->get('translator')->trans($this->translation[1] . '.error.unknown', array(), $this->translation[0]));
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
        if(!$this->routeExists($this->source . '_admin_' . $this->route) || !$this->routeExists($this->source . '_admin_' . $this->route . '_update'))
        {
            throw $this->createNotFoundException($this->get('translator')->trans('fhm.error.route', array(), 'FhmFhmBundle'));
        }
        $document     = $this->dmRepository()->find($id);
        $instance     = $this->instanceData($document);
        $classType    = $this->form->type->update;
        $classHandler = $this->form->handler->update;
        // ERROR - unknown
        if($document == "")
        {
            throw $this->createNotFoundException($this->get('translator')->trans($this->translation[1] . '.error.unknown', array(), $this->translation[0]));
        }
        if(!$instance->user->admin && $instance->grouping->different)
        {
            throw new HttpException(403, $this->get('translator')->trans($this->translation[1] . '.error.forbidden', array(), $this->translation[0]));
        }
        if(!$instance->user->super && $document->getDelete())
        {
            throw new HttpException(403, $this->get('translator')->trans($this->translation[1] . '.error.forbidden', array(), $this->translation[0]));
        }
        $this->historic($document);
        $form    = $this->createForm(new $classType($instance, $document), $document);
        $handler = new $classHandler($form, $request);
        $process = $handler->process();
        if($process)
        {
            $data = $request->get($form->getName());
            // Persist
            $document->setUserUpdate($this->getUser());
//            $document->setAlias($this->getAlias($document->getId(), $document->getName()));
            $this->dmPersist($document);
            // Historic
            $this->historicAdd($document);
            // Message
            $this->get('session')->getFlashBag()->add('notice', $this->get('translator')->trans($this->translation[1] . '.admin.update.flash.ok', array(), $this->translation[0]));
            // Redirect
            $redirect = $this->redirect($this->generateUrl($this->source . '_admin_' . $this->route));
            $redirect = isset($data['submitSave']) ? $this->redirect($this->generateUrl($this->source . '_admin_' . $this->route . '_update', array('id' => $document->getId()))) : $redirect;
            $redirect = isset($data['submitDuplicate']) ? $this->redirect($this->generateUrl($this->source . '_admin_' . $this->route . '_duplicate', array('id' => $document->getId()))) : $redirect;
            $redirect = isset($data['submitNew']) ? $this->redirect($this->generateUrl($this->source . '_admin_' . $this->route . '_create')) : $redirect;
            $redirect = isset($data['submitConfig']) ? $this->redirect($this->generateUrl($this->source . '_admin_' . $this->route . '_detail', array('id' => $document->getId()))) : $redirect;

            return $redirect;
        }

        return array(
            'document'    => $document,
            'form'        => $form->createView(),
            'instance'    => $instance,
            'breadcrumbs' => array(
                array(
                    'link' => $this->get('router')->generate('project_home'),
                    'text' => $this->get('translator')->trans('project.home.breadcrumb', array(), 'ProjectDefaultBundle'),
                ),
                array(
                    'link' => $this->get('router')->generate('fhm_admin'),
                    'text' => $this->get('translator')->trans('fhm.admin.breadcrumb', array(), 'FhmFhmBundle'),
                ),
                array(
                    'link' => $this->get('router')->generate($this->source . '_admin_' . $this->route),
                    'text' => $this->get('translator')->trans($this->translation[1] . '.admin.index.breadcrumb', array(), $this->translation[0]),
                ),
                array(
                    'link' => $this->get('router')->generate($this->source . '_admin_' . $this->route . '_detail', array('id' => $id)),
                    'text' => $this->get('translator')->trans($this->translation[1] . '.admin.detail.breadcrumb', array('%name%' => $document->getName()), $this->translation[0]),
                ),
                array(
                    'link'    => $this->get('router')->generate($this->source . '_admin_' . $this->route . '_update', array('id' => $id)),
                    'text'    => $this->get('translator')->trans($this->translation[1] . '.admin.update.breadcrumb', array(), $this->translation[0]),
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
        if(!$this->routeExists($this->source . '_admin_' . $this->route) || !$this->routeExists($this->source . '_admin_' . $this->route . '_detail'))
        {
            throw $this->createNotFoundException($this->get('translator')->trans('fhm.error.route', array(), 'FhmFhmBundle'));
        }
        $document = $this->dmRepository()->find($id);
        $instance = $this->instanceData($document);
        // ERROR - unknown
        if($document == "")
        {
            throw $this->createNotFoundException($this->get('translator')->trans($this->translation[1] . '.error.unknown', array(), $this->translation[0]));
        }

        return array(
            'document'           => $document,
            'instance'           => $instance,
            'historics'          => $this->historicData($document),
            'paginationHistoric' => $this->historicPagination($document),
            'breadcrumbs'        => array(
                array(
                    'link' => $this->get('router')->generate('project_home'),
                    'text' => $this->get('translator')->trans('project.home.breadcrumb', array(), 'ProjectDefaultBundle'),
                ),
                array(
                    'link' => $this->get('router')->generate('fhm_admin'),
                    'text' => $this->get('translator')->trans('fhm.admin.breadcrumb', array(), 'FhmFhmBundle'),
                ),
                array(
                    'link' => $this->get('router')->generate($this->source . '_admin_' . $this->route),
                    'text' => $this->get('translator')->trans($this->translation[1] . '.admin.index.breadcrumb', array(), $this->translation[0]),
                ),
                array(
                    'link'    => $this->get('router')->generate($this->source . '_admin_' . $this->route . '_detail', array('id' => $id)),
                    'text'    => $this->get('translator')->trans($this->translation[1] . '.admin.detail.breadcrumb', array('%name%' => $document->getName()), $this->translation[0]),
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
        if(!$this->routeExists($this->source . '_admin_' . $this->route))
        {
            throw $this->createNotFoundException($this->get('translator')->trans('fhm.error.route', array(), 'FhmFhmBundle'));
        }
        $document = $this->dmRepository()->find($id);
        // ERROR - Unknown
        if($document == "")
        {
            throw $this->createNotFoundException($this->get('translator')->trans($this->translation[1] . '.error.unknown', array(), $this->translation[0]));
        }
        // ERROR - Forbidden
        elseif($document->getDelete() && !$this->getUser()->isSuperAdmin())
        {
            throw new HttpException(403, $this->get('translator')->trans($this->translation[1] . '.error.forbidden', array(), $this->translation[0]));
        }
        // Delete
        if($document->getDelete())
        {
            $this->dmRemove($document);
            // Message
            $this->get('session')->getFlashBag()->add('notice', $this->get('translator')->trans($this->translation[1] . '.admin.delete.flash.ok', array(), $this->translation[0]));
        }
        else
        {
            $document->setDelete(true);
            $this->dmPersist($document);
            // Message
            $this->get('session')->getFlashBag()->add('notice', $this->get('translator')->trans($this->translation[1] . '.admin.delete.flash.ok', array(), $this->translation[0]));
        }

        return $this->redirect($this->generateUrl($this->source . '_admin_' . $this->route));
    }

    /**
     * @param $id
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function undeleteAction($id)
    {
        // ERROR - Unknown route
        if(!$this->routeExists($this->source . '_admin_' . $this->route))
        {
            throw $this->createNotFoundException($this->get('translator')->trans('fhm.error.route', array(), 'FhmFhmBundle'));
        }
        $document = $this->dmRepository()->find($id);
        // ERROR - Unknown
        if($document == "")
        {
            throw $this->createNotFoundException($this->get('translator')->trans($this->translation[1] . '.error.unknown', array(), $this->translation[0]));
        }
        // ERROR - Forbidden
        elseif($document->getDelete() && !$this->getUser()->isSuperAdmin())
        {
            throw new HttpException(403, $this->get('translator')->trans($this->translation[1] . '.error.forbidden', array(), $this->translation[0]));
        }
        // Undelete
        $document->setDelete(false);
        $this->dmPersist($document);
        // Message
        $this->get('session')->getFlashBag()->add('notice', $this->get('translator')->trans($this->translation[1] . '.admin.undelete.flash.ok', array(), $this->translation[0]));

        return $this->redirect($this->generateUrl($this->source . '_admin_' . $this->route));
    }

    /**
     * @param $id
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function activateAction($id)
    {
        // ERROR - Unknown route
        if(!$this->routeExists($this->source . '_admin_' . $this->route))
        {
            throw $this->createNotFoundException($this->get('translator')->trans('fhm.error.route', array(), 'FhmFhmBundle'));
        }
        $document = $this->dmRepository()->find($id);
        // ERROR - Unknown
        if($document == "")
        {
            throw $this->createNotFoundException($this->get('translator')->trans($this->translation[1] . '.error.unknown', array(), $this->translation[0]));
        }
        // Deactivate
        $document->setActive(true);
        $this->dmPersist($document);
        // Message
        $this->get('session')->getFlashBag()->add('notice', $this->get('translator')->trans($this->translation[1] . '.admin.activate.flash.ok', array(), $this->translation[0]));

        return $this->redirect($this->generateUrl($this->source . '_admin_' . $this->route));
    }

    /**
     * @param $id
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deactivateAction($id)
    {
        // ERROR - Unknown route
        if(!$this->routeExists($this->source . '_admin_' . $this->route))
        {
            throw $this->createNotFoundException($this->get('translator')->trans('fhm.error.route', array(), 'FhmFhmBundle'));
        }
        $document = $this->dmRepository()->find($id);
        // ERROR - Unknown
        if($document == "")
        {
            throw $this->createNotFoundException($this->get('translator')->trans($this->translation[1] . '.error.unknown', array(), $this->translation[0]));
        }
        // Deactivate
        $document->setActive(false);
        $this->dmPersist($document);
        // Message
        $this->get('session')->getFlashBag()->add('notice', $this->get('translator')->trans($this->translation[1] . '.admin.deactivate.flash.ok', array(), $this->translation[0]));

        return $this->redirect($this->generateUrl($this->source . '_admin_' . $this->route));
    }

    /**
     * @param Request $request
     *
     * @return array
     */
    public function importAction(Request $request)
    {
        // ERROR - Unknown route
        if(!$this->routeExists($this->source . '_admin_' . $this->route) || !$this->routeExists($this->source . '_admin_' . $this->route . '_import'))
        {
            throw $this->createNotFoundException($this->get('translator')->trans('fhm.error.route', array(), 'FhmFhmBundle'));
        }
        $instance     = $this->instanceData();
        $classType    = $this->form->type->import;
        $classHandler = $this->form->handler->import;
        $form         = $this->createForm(new $classType($instance), null);
        $handler      = new $classHandler($form, $request);
        $process      = $handler->process();
        if($datas = $process)
        {
            $count = array(0, 0, 0);
            foreach($datas as $data)
            {
                if(!isset($data['alias']))
                {
                    $data['alias'] = $this->getAlias(isset($data['id']) ? $data['id'] : null, $data['name']);
                }
                $document = $this->dmRepository()->getImport($data);
                // Counter
                if($document === 'error')
                {
                    $count[2]++;
                }
                elseif($document)
                {
                    $count[1]++;
                    $document->setCsvData($data);
                    $this->dmPersist($document);
                }
                else
                {
                    $count[0]++;
                    $document = new $this->class();
                    $document->setCsvData($data);
                    $this->dmPersist($document);
                }
            }
            // Message
            $this->get('session')->getFlashBag()->add('notice', $this->get('translator')->trans($this->translation[1] . '.admin.import.flash.ok', array('%countAdd%' => $count[0], '%countUpdate%' => $count[1], '%countError%' => $count[2]), $this->translation[0]));
        }

        return array(
            'form'        => $form->createView(),
            'instance'    => $instance,
            'breadcrumbs' => array(
                array(
                    'link' => $this->get('router')->generate('project_home'),
                    'text' => $this->get('translator')->trans('project.home.breadcrumb', array(), 'ProjectDefaultBundle'),
                ),
                array(
                    'link' => $this->get('router')->generate('fhm_admin'),
                    'text' => $this->get('translator')->trans('fhm.admin.breadcrumb', array(), 'FhmFhmBundle'),
                ),
                array(
                    'link' => $this->get('router')->generate($this->source . '_admin_' . $this->route),
                    'text' => $this->get('translator')->trans($this->translation[1] . '.admin.index.breadcrumb', array(), $this->translation[0]),
                ),
                array(
                    'link'    => $this->get('router')->generate($this->source . '_admin_' . $this->route . '_import'),
                    'text'    => $this->get('translator')->trans($this->translation[1] . '.admin.import.breadcrumb', array(), $this->translation[0]),
                    'current' => true
                )
            )
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
        if(!$this->routeExists($this->source . '_admin_' . $this->route) || !$this->routeExists($this->source . '_admin_' . $this->route . '_export'))
        {
            throw $this->createNotFoundException($this->get('translator')->trans('fhm.error.route', array(), 'FhmFhmBundle'));
        }
        $instance     = $this->instanceData();
        $classType    = $this->form->type->export;
        $classHandler = $this->form->handler->export;
        $form         = $this->createForm(new $classType($instance), null);
        $handler      = new $classHandler($form, $request);
        $process      = $handler->process();
        if($process)
        {
            $documents = $this->dmRepository()->getExport();
            $datas     = array($this->document->getCsvHeader());
            foreach($documents as $document)
            {
                $datas[] = $document->getCsvData();
            }

            return $this->csvExport($datas);
        }

        return array(
            'form'        => $form->createView(),
            'instance'    => $instance,
            'breadcrumbs' => array(
                array(
                    'link' => $this->get('router')->generate('project_home'),
                    'text' => $this->get('translator')->trans('project.home.breadcrumb', array(), 'ProjectDefaultBundle'),
                ),
                array(
                    'link' => $this->get('router')->generate('fhm_admin'),
                    'text' => $this->get('translator')->trans('fhm.admin.breadcrumb', array(), 'FhmFhmBundle'),
                ),
                array(
                    'link' => $this->get('router')->generate($this->source . '_admin_' . $this->route),
                    'text' => $this->get('translator')->trans($this->translation[1] . '.admin.index.breadcrumb', array(), $this->translation[0]),
                ),
                array(
                    'link'    => $this->get('router')->generate($this->source . '_admin_' . $this->route . '_export'),
                    'text'    => $this->get('translator')->trans($this->translation[1] . '.admin.export.breadcrumb', array(), $this->translation[0]),
                    'current' => true
                )
            )
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
        $document  = $this->dmRepository()->find($request->get('id'));
        $document->resetGrouping();
        foreach($groupings as $key => $grouping)
        {
            $document->addGrouping($grouping->id);
        }
        $this->dmPersist($document);

        return new Response();
    }
}