<?php

namespace Fhm\FhmBundle\Controller;

use Fhm\FhmBundle\Form\Type\Admin\ExportType;
use Fhm\FhmBundle\Form\Type\Admin\SearchType;
use Fhm\FhmBundle\Manager\TypeManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * All admin class should extend this class
 * Class RefAdminController
 *
 * @package Fhm\FhmBundle\Controller
 */
class RefAdminController extends GenericController
{
    /**
     * @return array
     */
    public function indexAction()
    {
        $request    = $this->get('request_stack')->getCurrentRequest();
        $dataSearch = $request->request->get('FhmSearch');
        $query      = $this->get('fhm_tools')->dmRepository(self::$repository)->getAdminIndex(
            $dataSearch['search'],
            $this->getUser()->hasRole('ROLE_SUPER_ADMIN')
        );
        $pagination = $this->get('knp_paginator')->paginate(
            $query,
            $request->query->getInt('page', 1),
            $this->getParameters(array('pagination', 'admin', 'page'), 'fhm_fhm')
        );

        return array(
            'form'        => $this->createForm(SearchType::class)->createView(),
            'pagination'  => $pagination,
            'breadcrumbs' => $this->get('fhm_tools')->generateBreadcrumbs(
                array(
                    'domain' => self::$domain,
                    '_route' => $this->get('request_stack')->getCurrentRequest()->get('_route'),
                )
            ),
        );
    }

    /**
     * @param Request $request
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function createAction(Request $request)
    {
        self::$class = $this->get('fhm.object.manager')->getCurrentModelName(self::$repository);
        $object      = new self::$class;
        $form        = $this->createForm(
            self::$form->createType,
            $object,
            array(
                'user_admin'     => $this->getUser()->hasRole('ROLE_ADMIN'),
                'data_class'     => self::$class,
                'object_manager' => $this->get('fhm.object.manager'),
            )
        );
        $handler     = new self::$form->createHandler($form, $request);
        $process     = $handler->process();
        if($process)
        {
            $object->setAlias($this->get('fhm_tools')->getAlias($object->getId(), $object->getName(), self::$repository));
            $object->setUserCreate($this->getUser());
            $this->get('fhm_tools')->dmPersist($object);
            $this->get('session')->getFlashBag()->add(
                'notice',
                $this->trans(self::$translation . '.admin.create.flash.ok')
            );
            $data = $request->get($form->getName());

            return $this->redirectUrl($data, $object);
        }

        return array(
            'form'        => $form->createView(),
            'breadcrumbs' => $this->get('fhm_tools')->generateBreadcrumbs(
                array(
                    'domain' => self::$domain,
                    '_route' => $this->get('request_stack')->getCurrentRequest()->get('_route'),
                )
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
        $object = $this->get('fhm_tools')->dmRepository(self::$repository)->find($id);
        if(!is_object($object))
        {
            throw $this->createNotFoundException(
                $this->trans(self::$translation . '.error.unknown', self::$domain)
            );
        }
        self::$class = $this->get('fhm.object.manager')->getCurrentModelName(self::$repository);
        $form        = $this->createForm(
            self::$form->createType,
            $object,
            array(
                'user_admin'     => $this->getUser()->hasRole('ROLE_ADMIN'),
                'data_class'     => self::$class,
                'object_manager' => $this->get('fhm.object.manager'),
            )
        );
        $handler     = new self::$form->createHandler($form, $request);
        $process     = $handler->process();
        if($process)
        {
            $object->setAlias($this->get('fhm_tools')->getAlias($object->getId(), $object->getName(), self::$repository));
            $object->setUserCreate($this->getUser());
            $this->get('fhm_tools')->dmPersist($object);
            $this->get('session')->getFlashBag()->add(
                'notice',
                $this->trans(self::$translation . '.admin.create.flash.ok')
            );
            $data = $request->get($form->getName());

            return $this->redirectUrl($data, $object);
        }

        return array(
            'form'        => $form->createView(),
            'breadcrumbs' => $this->get('fhm_tools')->generateBreadcrumbs(
                array(
                    'domain' => self::$domain,
                    '_route' => str_replace('_duplicate', '_create', $this->get('request_stack')->getCurrentRequest()->get('_route')),
                )
            ),
        );
    }

    /**
     * @param Request $request
     * @param         $id
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function updateAction(Request $request, $id)
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN', null, $this->trans(self::$translation . '.error.forbidden'));
        $object = $this->get('fhm_tools')->dmRepository(self::$repository)->find($id);
        if(!is_object($object))
        {
            throw $this->createNotFoundException($this->trans(self::$translation . '.error.unknown'));
        }
        self::$class = $this->get('fhm.object.manager')->getCurrentModelName(self::$repository);
        $form        = $this->createForm(
            self::$form->updateType,
            $object,
            array(
                'user_admin'     => $this->getUser()->hasRole('ROLE_SUPER_ADMIN'),
                'data_class'     => self::$class,
                'object_manager' => $this->get('fhm.object.manager'),
            )
        );
        $handler     = new self::$form->updateHandler($form, $request);
        $process     = $handler->process();
        if($process)
        {
            $object->setAlias($this->get('fhm_tools')->getAlias($object->getId(), $object->getName(), self::$repository));
            $object->setUserUpdate($this->getUser());
            $this->get('fhm_tools')->dmPersist($object);
            $this->get('session')->getFlashBag()->add(
                'notice',
                $this->trans(self::$translation . '.admin.update.flash.ok')
            );
            /** Redirect **/
            $data = $request->get($form->getName());

            return $this->redirectUrl($data, $object);
        }

        return array(
            'object'      => $object,
            'form'        => $form->createView(),
            'breadcrumbs' => $this->get('fhm_tools')->generateBreadcrumbs(
                array(
                    'domain' => self::$domain,
                    '_route' => $this->get('request_stack')->getCurrentRequest()->get('_route'),
                ),
                $object
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
        $object = $this->get('fhm_tools')->dmRepository(self::$repository)->find($id);
        if(!is_object($object))
        {
            throw $this->createNotFoundException($this->trans(self::$translation . '.error.unknown'));
        }

        return array(
            'object'      => $object,
            'breadcrumbs' => $this->get('fhm_tools')->generateBreadcrumbs(
                array(
                    'domain' => self::$domain,
                    '_route' => $this->get('request_stack')->getCurrentRequest()->get('_route'),
                ),
                $object
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
        $this->denyAccessUnlessGranted('ROLE_ADMIN', null, $this->trans(self::$translation . '.error.forbidden'));
        $object = $this->get('fhm_tools')->dmRepository(self::$repository)->find($id);
        if(!is_object($object))
        {
            throw $this->createNotFoundException($this->trans(self::$translation . '.error.unknown'));
        }
        if($object->getDelete())
        {
            $this->get('fhm_tools')->dmRemove($object);
            $this->get('session')->getFlashBag()->add(
                'notice',
                $this->trans(self::$translation . '.admin.delete.flash.ok')
            );
        }
        else
        {
            $object->setDelete(true);
            $this->get('fhm_tools')->dm()->persist($object);
            $this->get('session')->getFlashBag()->add(
                'notice',
                $this->trans(self::$translation . '.admin.delete.flash.ok')
            );
        }
        $this->get('fhm_tools')->dm()->flush();

        return $this->redirect($this->getUrl(self::$source . '_admin_' . self::$route));
    }

    /**
     * @param $id
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function undeleteAction($id)
    {
        $this->denyAccessUnlessGranted('ROLE_SUPER_ADMIN', null, $this->trans(self::$translation . '.error.forbidden'));
        $object = $this->get('fhm_tools')->dmRepository(self::$repository)->find($id);
        if(!is_object($object))
        {
            throw $this->createNotFoundException($this->trans(self::$translation . '.error.unknown'));
        }
        $object->setDelete(false);
        $this->get('fhm_tools')->dmPersist($object);
        $this->get('session')->getFlashBag()->add(
            'notice',
            $this->trans(self::$translation . '.admin.undelete.flash.ok')
        );

        return $this->redirect($this->getUrl(self::$source . '_admin_' . self::$route));
    }

    /**
     * @param $id
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function activateAction($id)
    {
        $object = $this->get('fhm_tools')->dmRepository(self::$repository)->find($id);
        if(!is_object($object))
        {
            throw $this->createNotFoundException($this->trans(self::$translation . '.error.unknown'));
        }
        $object->setActive(true);
        $this->get('fhm_tools')->dmPersist($object);
        $this->get('session')->getFlashBag()->add(
            'notice',
            $this->trans(self::$translation . '.admin.activate.flash.ok')
        );

        return $this->redirect($this->getUrl(self::$source . '_admin_' . self::$route));
    }

    /**
     * @param $id
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deactivateAction($id)
    {
        $object = $this->get('fhm_tools')->dmRepository(self::$repository)->find($id);
        if(!is_object($object))
        {
            throw $this->createNotFoundException($this->trans(self::$translation . '.error.unknown'));
        }
        $object->setActive(false);
        $this->get('fhm_tools')->dmPersist($object);
        $this->get('session')->getFlashBag()->add(
            'notice',
            $this->trans(self::$translation . '.admin.deactivate.flash.ok')
        );

        return $this->redirect($this->getUrl(self::$source . '_admin_' . self::$route));
    }

    /**
     * @param Request $request
     *
     * @return array
     */
    public function importAction(Request $request)
    {
        $classType    = self::$form->type;
        $classHandler = self::$form->handler;
        $form         = $this->createForm($classType);
        $handler      = new $classHandler($form, $request);
        $process      = $handler->process();
        if($datas = $process)
        {
            $count = array(0, 0, 0);
            foreach($datas as $data)
            {
                if(!isset($data['alias']))
                {
                    $data['alias'] = $this->get('fhm_tools')->getAlias(
                        isset($data['id']) ? $data['id'] : null,
                        $data['name'],
                        self::$repository
                    );
                }
                $object = $this->get('fhm_tools')->dmRepository(self::$repository)->getImport($data);
                if($object === 'error')
                {
                    $count[2]++;
                }
                elseif($object)
                {
                    $count[1]++;
                    $object->setCsvData($data);
                    $this->get('fhm_tools')->dm()->persist($object);
                }
                else
                {
                    $count[0]++;
                    $object = new self::$class;
                    $object->setCsvData($data);
                    $this->get('fhm_tools')->dm()->persist($object);
                }
            }
            $this->get('fhm_tools')->dm()->flush();
            // Message
            $this->get('session')->getFlashBag()->add(
                'notice',
                $this->trans(
                    self::$translation . '.admin.import.flash.ok',
                    array('%countAdd%' => $count[0], '%countUpdate%' => $count[1], '%countError%' => $count[2])
                )
            );
        }

        return array(
            'form'        => $form->createView(),
            'breadcrumbs' => $this->get('fhm_tools')->generateBreadcrumbs(
                array(
                    'domain' => self::$domain,
                    '_route' => $this->get('request_stack')->getCurrentRequest()->get('_route'),
                )
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
        $form    = $this->createForm(ExportType::class);
        $process = $form->handleRequest($request);
        if($process)
        {
            $objects = $this->get('fhm_tools')->dmRepository(self::$repository)->getExport();
            $datas   = array(self::$object->getCsvHeader());
            foreach($objects as $object)
            {
                $datas[] = $object->getCsvData();
            }

            return $this->get('fhm_tools')->csvExport($datas, '', ',');
        }

        return array(
            'form'        => $form->createView(),
            'breadcrumbs' => $this->get('fhm_tools')->generateBreadcrumbs(
                array(
                    'domain' => self::$domain,
                    '_route' => $this->get('request_stack')->getCurrentRequest()->get('_route'),
                )
            ),
        );
    }
}