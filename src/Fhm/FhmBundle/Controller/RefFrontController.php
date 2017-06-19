<?php

namespace Fhm\FhmBundle\Controller;

use Fhm\FhmBundle\Form\Type\Front\SearchType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * Class RefFrontController
 *
 * @package Fhm\FhmBundle\Controller
 */
class RefFrontController extends GenericController
{
    /**
     * @return array
     */
    public function indexAction()
    {
        $form       = $this->createForm(SearchType::class);
        $request    = $this->get('request_stack')->getCurrentRequest();
        $dataSearch = $request->request->get('FhmSearch');
        $query      = $this->get('fhm_tools')->dmRepository(self::$repository)->getFrontIndex(
            $dataSearch['search']
        );
        $pagination = $this->get('knp_paginator')->paginate(
            $query,
            $request->query->getInt('page', 1),
            $this->getParameters(array('pagination', 'front', 'page'), 'fhm_fhm')
        );

        return array(
            'pagination'  => $pagination,
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
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function createAction(Request $request)
    {
        self::$class = $this->get('fhm.object.manager')->getCurrentModelName(self::$repository);
        $object      = new self::$class;
        $form        = $this->createForm(self::$form->createType, $object);
        $handler     = new self::$form->createHandler($form, $request);
        $process     = $handler->process();
        if($process)
        {
            $data = $request->get($form->getName());
            $object->setUserCreate($this->getUser());
            $object->setAlias(
                $this->get('fhm_tools')->getAlias($object->getId(), $object->getName(), self::$repository)
            );
            $this->get('fhm_tools')->dmPersist($object);
            $this->get('session')->getFlashBag()->add(
                'notice',
                $this->trans(self::$translation . '.front.create.flash.ok')
            );

            return $this->redirectUrl($data, $object, 'front');
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
        if($object == "")
        {
            throw $this->createNotFoundException($this->trans(self::$translation . '.error.unknown'));
        }
        self::$object = $object;

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
        $object = $this->get('fhm_tools')->dmRepository(self::$repository)->find($id);
        if($object == "")
        {
            throw $this->createNotFoundException($this->trans(self::$translation . '.error.unknown'));
        }
        if(!$this->get('security.authorization_checker')->isGranted('ROLE_SUPER_ADMIN') && $object->getDelete())
        {
            throw new HttpException(403, $this->trans(self::$translation . '.error.forbidden'));
        }
        $form    = $this->createForm(self::$form->updateType, $object);
        $handler = new self::$form->updateHandler($form, $request);
        $process = $handler->process();
        if($process)
        {
            $object->setUserUpdate($this->getUser());
            $object->setAlias($this->getAlias($object->getId(), $object->getName(), self::$repository));
            $object->setActive(false);
            $this->get('fhm_tools')->dmPersist($object);
            $this->get('session')->getFlashBag()->add(
                'notice',
                $this->trans(self::$translation . '.front.update.flash.ok')
            );

            return $this->redirectUrl($form->getData(), $object, 'front');
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
        $object        = $this->get('fhm_tools')->dmRepository(self::$repository)->getById($id);
        $object        = ($object) ? $object : $this->get('fhm_tools')->dmRepository(self::$repository)->getByAlias($id);
        $object        = ($object) ? $object : $this->get('fhm_tools')->dmRepository(self::$repository)->getByName($id);
        $authorization = $this->get('security.authorization_checker')->isGranted('ROLE_ADMIN');
        if(!is_object($object))
        {
            throw $this->createNotFoundException($this->trans(self::$translation . '.error.unknown'));
        }
        elseif(!$authorization && ($object->getDelete() || !$object->getActive()))
        {
            throw new HttpException(403, $this->trans(self::$translation . '.error.forbidden'));
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
        $object = $this->get('fhm_tools')->dmRepository(self::$repository)->find($id);
        if($object == "")
        {
            throw $this->createNotFoundException($this->trans(self::$source . '.error.unknown'));
        }
        elseif(!$this->get('security.authorization_checker')->isGranted('ROLE_ADMIN'))
        {
            throw new HttpException(403, $this->trans(self::$translation . '.error.forbidden'));
        }
        $object->setDelete(true);
        $this->get('fhm_tools')->dmPersist($object);
        $this->get('session')->getFlashBag()->add('notice', $this->trans(self::$translation . '.front.delete.flash.ok'));

        return $this->redirect($this->getUrl(self::$source . '_' . self::$route));
    }
}