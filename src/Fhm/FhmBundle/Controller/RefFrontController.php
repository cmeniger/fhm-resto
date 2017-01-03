<?php
namespace Fhm\FhmBundle\Controller;

use Fhm\FhmBundle\Form\Type\Front\SearchType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * Class RefFrontController
 * @package Fhm\FhmBundle\Controller
 */
class RefFrontController extends GenericController
{
    /**
     * @return array
     */
    public function indexAction()
    {
        $form = $this->createForm(SearchType::class);
        $request = $this->get('request_stack')->getCurrentRequest();
        $dataSearch = $request->request->get('FhmSearch');
        $query = $this->get('fhm_tools')->dmRepository(self::$repository)->getFrontIndex(
            $dataSearch['search']
        );
        $pagination = $this->get('knp_paginator')->paginate(
            $query,
            $request->query->getInt('page', 1),
            $this->getParameters('pagination', 'fhm_fhm')
        );
        return array(
            'pagination' => $pagination,
            'form' => $form->createView(),
            'breadcrumbs' => array(
                array(
                    'link' => $this->getUrl('project_home'),
                    'text' => $this->trans('project.home.breadcrumb', array(), 'ProjectDefaultBundle'),
                ),
                array(
                    'link' => $this->getUrl(self::$source.'_'.self::$route),
                    'text' => $this->trans(self::$translation.'.front.index.breadcrumb'),
                ),
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
        $document = new self::$class;
        $form = $this->createForm(self::$form->createType, $document);
        $handler = new self::$form->createHandler($form, $request);
        $process = $handler->process();
        if ($process) {
            $data = $request->get($form->getName());
            $document->setUserCreate($this->getUser());
            $document->setAlias(
                $this->get('fhm_tools')->getAlias($document->getId(), $document->getName(), self::$repository)
            );
            $this->get('fhm_tools')->dmPersist($document);
            $this->get('session')->getFlashBag()->add(
                'notice',
                $this->trans(self::$translation.'.front.create.flash.ok')
            );
            return $this->redirectUrl($data, $document, 'front');
        }

        return array(
            'form' => $form->createView(),
            'breadcrumbs' => array(
                array(
                    'link' => $this->getUrl('project_home'),
                    'text' => $this->trans('project.home.breadcrumb', array(), 'ProjectDefaultBundle'),
                ),
                array(
                    'link' => $this->getUrl(self::$source.'_'.self::$route),
                    'text' => $this->trans(self::$translation.'.front.index.breadcrumb'),
                ),
                array(
                    'link' => $this->getUrl(self::$source.'_'.self::$route.'_create'),
                    'text' => $this->trans(self::$translation.'.front.create.breadcrumb'),
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
        $document = $this->get('fhm_tools')->dmRepository(self::$repository)->find($id);
        if ($document == "") {
            throw $this->createNotFoundException($this->trans(self::$translation.'.error.unknown'));
        }
        self::$document = $document;

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
        $document = $this->get('fhm_tools')->dmRepository(self::$repository)->find($id);
        if ($document == "") {
            throw $this->createNotFoundException($this->trans(self::$translation.'.error.unknown'));
        }
        if (!$this->get('security.authorization_checker')->isGranted('ROLE_SUPER_ADMIN') && $document->getDelete()) {
            throw new HttpException(403, $this->trans(self::$translation.'.error.forbidden'));
        }
        $form = $this->createForm(self::$form->updateType, $document);
        $handler = new self::$form->updateHandler($form, $request);
        $process = $handler->process();
        if ($process) {
            $document->setUserUpdate($this->getUser());
            $document->setAlias($this->getAlias($document->getId(), $document->getName(), self::$repository));
            $document->setActive(false);
            $this->get('fhm_tools')->dmPersist($document);
            $this->get('session')->getFlashBag()->add(
                'notice',
                $this->trans(self::$translation.'.front.update.flash.ok')
            );

            return $this->redirectUrl($form->getData(), $document, 'front');
        }
        return array(
            'document' => $document,
            'form' => $form->createView(),
            'breadcrumbs' => array(
                array(
                    'link' => $this->getUrl('project_home'),
                    'text' => $this->trans('project.home.breadcrumb', array(), 'ProjectDefaultBundle'),
                ),
                array(
                    'link' => $this->getUrl(self::$source.'_'.self::$route),
                    'text' => $this->trans(self::$translation.'.front.index.breadcrumb'),
                ),
                array(
                    'link' => $this->getUrl(
                        self::$source.'_'.self::$route.'_detail',
                        array('id' => $id)
                    ),
                    'text' => $this->trans(
                        self::$translation.'.front.detail.breadcrumb',
                        array('%name%' => $document->getName())
                    ),
                ),
                array(
                    'link' => $this->getUrl(
                        self::$source.'_'.self::$route.'_update',
                        array('id' => $id)
                    ),
                    'text' => $this->trans(self::$translation.'.front.update.breadcrumb'),
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
        $document = $this->get('fhm_tools')->dmRepository(self::$repository)->getById($id);
        $document = ($document) ? $document : $this->get('fhm_tools')->dmRepository(self::$repository)->getByAlias($id);
        $document = ($document) ? $document : $this->get('fhm_tools')->dmRepository(self::$repository)->getByName($id);
        if (!is_object($document)) {
            throw $this->createNotFoundException($this->trans(self::$translation.'.error.unknown'));
        } elseif (!$this->get('security.authorization_checker')->isGranted('ROLE_ADMIN') && ($document->getDelete(
                ) || !$document->getActive())
        ) {
            throw new HttpException(403, $this->trans(self::$translation.'.error.forbidden'));
        }

        return array(
            'document' => $document,
            'breadcrumbs' => array(
                array(
                    'link' => $this->getUrl('project_home'),
                    'text' => $this->trans('project.home.breadcrumb', array(), 'ProjectDefaultBundle'),
                ),
                array(
                    'link' => $this->getUrl(self::$source.'_'.self::$route),
                    'text' => $this->trans(self::$translation.'.front.index.breadcrumb'),
                ),
                array(
                    'link' => $this->getUrl(
                        self::$source.'_'.self::$route.'_detail',
                        array('id' => $id)
                    ),
                    'text' => $this->trans(
                        self::$translation.'.front.detail.breadcrumb',
                        array('%name%' => $document->getName())
                    ),
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
        $document = $this->get('fhm_tools')->dmRepository(self::$repository)->find($id);
        if ($document == "") {
            throw $this->createNotFoundException($this->trans(self::$source.'.error.unknown'));
        } elseif (!$this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')) {
            throw new HttpException(403, $this->trans(self::$translation.'.error.forbidden'));
        }
        $document->setDelete(true);
        $this->get('fhm_tools')->dmPersist($document);
        $this->get('session')->getFlashBag()->add('notice', $this->trans(self::$translation.'.front.delete.flash.ok'));

        return $this->redirect($this->getUrl(self::$source.'_'.self::$route));
    }
}