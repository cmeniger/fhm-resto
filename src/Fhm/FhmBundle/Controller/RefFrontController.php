<?php

namespace Fhm\FhmBundle\Controller;

use Fhm\FhmBundle\Document\Fhm;
use Fhm\FhmBundle\Controller\FhmController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

class RefFrontController extends FhmController
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
        $this->section     = "Front";
        $this->initForm($src . '\\' . $bundle . 'Bundle');
    }

    /**
     * @return array
     */
    public function indexAction()
    {
        // ERROR - Unknown route
        if(!$this->routeExists($this->source . '_' . $this->route))
        {
            throw $this->createNotFoundException($this->get('translator')->trans('fhm.error.route', array(), 'FhmFhmBundle'));
        }
        $instance  = $this->instanceData();
        $classType = $this->form->type->search;
        $form      = $this->createForm(new $classType($instance), null);
        $form->setData($this->get('request')->get($form->getName()));
        $dataSearch     = $form->getData();
        $dataPagination = $this->get('request')->get('FhmPagination');
        // Ajax pagination request
        if(isset($dataPagination['pagination']))
        {
            $documents = $this->dmRepository()->getFrontIndex($dataSearch['search'], $dataPagination['pagination'], $this->getParameter(array('pagination', 'front', 'page'), 'fhm_fhm'), $instance->grouping->current);

            return array(
                'documents'  => $documents,
                'pagination' => $this->getPagination($dataPagination['pagination'], count($documents), $this->dmRepository()->getFrontCount($dataSearch['search'], $instance->grouping->current), 'pagination', $this->formRename($form->getName(), $dataSearch)),
                'instance'   => $instance,
            );
        }
        // Router request
        else
        {
            $documents = $this->dmRepository()->getFrontIndex($dataSearch['search'], 1, $this->getParameter(array('pagination', 'front', 'page'), 'fhm_fhm'), $instance->grouping->current);

            return array(
                'documents'   => $documents,
                'pagination'  => $this->getPagination(1, count($documents), $this->dmRepository()->getFrontCount($dataSearch['search'], $instance->grouping->current), 'pagination', $this->formRename($form->getName(), $dataSearch)),
                'form'        => $form->createView(),
                'instance'    => $instance,
                'breadcrumbs' => array(
                    array(
                        'link' => $this->get('router')->generate('project_home'),
                        'text' => $this->get('translator')->trans('project.home.breadcrumb', array(), 'ProjectDefaultBundle'),
                    ),
                    array(
                        'link' => $this->get('router')->generate($this->source . '_' . $this->route),
                        'text' => $this->get('translator')->trans($this->translation[1] . '.front.index.breadcrumb', array(), $this->translation[0]),
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
        if(!$this->routeExists($this->source . '_' . $this->route) || !$this->routeExists($this->source . '_' . $this->route . '_create'))
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
            $document->addGrouping($instance->grouping->current);
            $this->dmPersist($document);
            // Message
            $this->get('session')->getFlashBag()->add('notice', $this->get('translator')->trans($this->translation[1] . '.front.create.flash.ok', array(), $this->translation[0]));
            // Redirect
            $redirect = $this->redirect($this->generateUrl($this->source . '_' . $this->route));
            $redirect = isset($data['submitDuplicate']) ? $this->redirect($this->generateUrl($this->source . '_' . $this->route . '_duplicate', array('id' => $document->getId()))) : $redirect;
            $redirect = isset($data['submitNew']) ? $this->redirect($this->generateUrl($this->source . '_' . $this->route . '_create')) : $redirect;

            return $redirect;
        }

        return array(
            'form'        => $form->createView(),
            'instance'    => $this->instanceData(),
            'breadcrumbs' => array(
                array(
                    'link' => $this->get('router')->generate('project_home'),
                    'text' => $this->get('translator')->trans('project.home.breadcrumb', array(), 'ProjectDefaultBundle'),
                ),
                array(
                    'link' => $this->get('router')->generate($this->source . '_' . $this->route),
                    'text' => $this->get('translator')->trans($this->translation[1] . '.front.index.breadcrumb', array(), $this->translation[0]),
                ),
                array(
                    'link'    => $this->get('router')->generate($this->source . '_' . $this->route . '_create'),
                    'text'    => $this->get('translator')->trans($this->translation[1] . '.front.create.breadcrumb', array(), $this->translation[0]),
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
        if(!$this->routeExists($this->source . '_' . $this->route) || !$this->routeExists($this->source . '_' . $this->route . '_duplicate'))
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
     * @throws HttpException
     */
    public function updateAction(Request $request, $id)
    {
        // ERROR - Unknown route
        if(!$this->routeExists($this->source . '_' . $this->route) || !$this->routeExists($this->source . '_' . $this->route . '_update'))
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
        $form    = $this->createForm(new $classType($instance, $document), $document);
        $handler = new $classHandler($form, $request);
        $process = $handler->process($document, $this->dm(), $this->bundle);
        if($process)
        {
            // Persist
            $document->setUserUpdate($this->getUser());
            $document->setAlias($this->getAlias($document->getId(), $document->getName()));
            $document->setActive(false);
            $this->dmPersist($document);
            // Message
            $this->get('session')->getFlashBag()->add('notice', $this->get('translator')->trans($this->translation[1] . '.front.update.flash.ok', array(), $this->translation[0]));
            // Redirect
            $redirect = $this->redirect($this->generateUrl($this->source . '_' . $this->route));
            $redirect = isset($data['submitDuplicate']) ? $this->redirect($this->generateUrl($this->source . '_' . $this->route . '_duplicate', array('id' => $document->getId()))) : $redirect;
            $redirect = isset($data['submitNew']) ? $this->redirect($this->generateUrl($this->source . '_' . $this->route . '_create')) : $redirect;

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
                    'link' => $this->get('router')->generate($this->source . '_' . $this->route),
                    'text' => $this->get('translator')->trans($this->translation[1] . '.front.index.breadcrumb', array(), $this->translation[0]),
                ),
                array(
                    'link' => $this->get('router')->generate($this->source . '_' . $this->route . '_detail', array('id' => $id)),
                    'text' => $this->get('translator')->trans($this->translation[1] . '.front.detail.breadcrumb', array('%name%' => $document->getName()), $this->translation[0]),
                ),
                array(
                    'link'    => $this->get('router')->generate($this->source . '_' . $this->route . '_update', array('id' => $id)),
                    'text'    => $this->get('translator')->trans($this->translation[1] . '.front.update.breadcrumb', array(), $this->translation[0]),
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
        if(!$this->routeExists($this->source . '_' . $this->route) || !$this->routeExists($this->source . '_' . $this->route . '_detail'))
        {
            throw $this->createNotFoundException($this->get('translator')->trans('fhm.error.route', array(), 'FhmFhmBundle'));
        }
        $document = $this->dmRepository()->getById($id);
        $document = ($document) ? $document : $this->dmRepository()->getByAlias($id);
        $document = ($document) ? $document : $this->dmRepository()->getByName($id);
        $instance = $this->instanceData($document);
        // ERROR - unknown
        if($document == "")
        {
            throw $this->createNotFoundException($this->get('translator')->trans($this->translation[1] . '.error.unknown', array(), $this->translation[0]));
        }
        // ERROR - Forbidden
        elseif(!$instance->user->admin && ($document->getDelete() || !$document->getActive()))
        {
            throw new HttpException(403, $this->get('translator')->trans($this->translation[1] . '.error.forbidden', array(), $this->translation[0]));
        }
        // Change grouping
        if($instance->grouping->different && $document->getGrouping())
        {
            $this->get($this->getParameter("grouping", "fhm_fhm"))->setGrouping($document->getFirstGrouping());
        }

        return array(
            'document'    => $document,
            'instance'    => $instance,
            'breadcrumbs' => array(
                array(
                    'link' => $this->get('router')->generate('project_home'),
                    'text' => $this->get('translator')->trans('project.home.breadcrumb', array(), 'ProjectDefaultBundle'),
                ),
                array(
                    'link' => $this->get('router')->generate($this->source . '_' . $this->route),
                    'text' => $this->get('translator')->trans($this->translation[1] . '.front.index.breadcrumb', array(), $this->translation[0]),
                ),
                array(
                    'link'    => $this->get('router')->generate($this->source . '_' . $this->route . '_detail', array('id' => $id)),
                    'text'    => $this->get('translator')->trans($this->translation[1] . '.front.detail.breadcrumb', array('%name%' => $document->getName()), $this->translation[0]),
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
        if(!$this->routeExists($this->source . '_' . $this->route))
        {
            throw $this->createNotFoundException($this->get('translator')->trans('fhm.error.route', array(), 'FhmFhmBundle'));
        }
        $document = $this->dmRepository()->find($id);
        $instance = $this->instanceData($document);
        // ERROR - Unknown
        if($document == "")
        {
            throw $this->createNotFoundException($this->get('translator')->trans($this->translation[1] . '.error.unknown', array(), $this->translation[0]));
        }
        // ERROR - Forbidden
        elseif(!$instance->user->admin)
        {
            throw new HttpException(403, $this->get('translator')->trans($this->translation[1] . '.error.forbidden', array(), $this->translation[0]));
        }
        // Delete
        $document->setDelete(true);
        $this->dmPersist($document);
        // Message
        $this->get('session')->getFlashBag()->add('notice', $this->get('translator')->trans($this->translation[1] . '.front.delete.flash.ok', array(), $this->translation[0]));

        return $this->redirect($this->generateUrl($this->source . '_' . $this->route));
    }
}