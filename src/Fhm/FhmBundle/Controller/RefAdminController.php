<?php
namespace Fhm\FhmBundle\Controller;

use Fhm\FhmBundle\Form\Type\Admin\ExportType;
use Fhm\FhmBundle\Form\Type\Admin\SearchType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * All admin class should extend this class
 * Class RefAdminController
 * @package Fhm\FhmBundle\Controller
 */
class RefAdminController extends GenericController
{
    /**
     * @return array
     */
    public function indexAction()
    {
        $request = $this->get('request_stack')->getCurrentRequest();
        $dataSearch = $request->request->get('FhmSearch');
        $query = $this->get('fhm_tools')->dmRepository(self::$repository)->getAdminIndex(
            $dataSearch['search'],
            $this->getUser()->hasRole('ROLE_SUPER_ADMIN')
        );
        $pagination = $this->get('knp_paginator')->paginate(
            $query,
            $request->query->getInt('page', 1),
            $this->getParameters('pagination', 'fhm_fhm')
        );
        if ($request->isXmlHttpRequest()) {
            return array('documents' => $query->execute()->toArray());
        } else {
            return array(
                'form' => $this->createForm(SearchType::class)->createView(),
                'pagination' => $pagination,
                'breadcrumbs' => $this->get('fhm_tools')->generateBreadcrumbs(
                    array(
                        'domain' => self::$domain,
                        '_route' => $this->get('request_stack')->getCurrentRequest()->get('_route'),
                    )
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
        $document = new self::$class;
        $form = $this->createForm(
            self::$form->createType,
            $document,
            array('user_admin' => $this->getUser()->hasRole('ROLE_SUPER_ADMIN'))
        );
        $handler = new self::$form->createHandler($form, $request);
        $process = $handler->process();
        if ($process) {
            $this->get('fhm_tools')->dmPersist($document);
            $this->get('session')->getFlashBag()->add(
                'notice',
                $this->trans(self::$translation.'.admin.create.flash.ok')
            );

            $data = $request->get($form->getName());
            return $this->redirectUrl($data, $document);
        }

        return array(
            'form' => $form->createView(),
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
        $document = $this->get('fhm_tools')->dmRepository(self::$repository)->find($id);
        if (!is_object($document)) {
            throw $this->createNotFoundException(
                $this->trans(self::$translation.'.error.unknown', self::$domain)
            );
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
        $this->denyAccessUnlessGranted('ROLE_ADMIN', null, $this->trans(self::$translation.'.error.forbidden'));
        $document = $this->get('fhm_tools')->dmRepository(self::$repository)->find($id);
        if (!is_object($document)) {
            throw $this->createNotFoundException($this->trans(self::$translation.'.error.unknown'));
        }
        $form = $this->createForm(
            self::$form->updateType,
            $document,
            array('user_admin' => $this->get('security.authorization_checker')->isGranted('ROLE_SUPER_ADMIN'))
        );
        $handler = new self::$form->updateHandler($form, $request);
        $process = $handler->process();
        if ($process) {
            $this->get('fhm_tools')->dmPersist($document);
            $this->get('session')->getFlashBag()->add(
                'notice',
                $this->trans(self::$translation.'.admin.update.flash.ok')
            );

            /** Redirect **/
            $data = $request->get($form->getName());
            return $this->redirectUrl($data, $document);
        }

        return array(
            'document' => $document,
            'form' => $form->createView(),
            'breadcrumbs' => $this->get('fhm_tools')->generateBreadcrumbs(
                array(
                    'domain' => self::$domain,
                    '_route' => $this->get('request_stack')->getCurrentRequest()->get('_route'),
                ),
                $document
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
        $document = $this->get('fhm_tools')->dmRepository(self::$repository)->find($id);
        if (!is_object($document)) {
            throw $this->createNotFoundException($this->trans(self::$translation.'.error.unknown'));
        }
        return array(
            'document' => $document,
//            'historics' => $this->get('fhm_historic')->getAllHistoricsByObject($document),
            'breadcrumbs' => $this->get('fhm_tools')->generateBreadcrumbs(
                array(
                    'domain' => self::$domain,
                    '_route' => $this->get('request_stack')->getCurrentRequest()->get('_route'),
                ),
                $document
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
        $this->denyAccessUnlessGranted('ROLE_ADMIN', null, $this->trans(self::$translation.'.error.forbidden'));
        $document = $this->get('fhm_tools')->dmRepository(self::$repository)->find($id);
        if (!is_object($document)) {
            throw $this->createNotFoundException($this->trans(self::$translation.'.error.unknown'));
        }
        if ($document->getDelete()) {
            $this->get('fhm_tools')->dm()->remove($document);
            $this->get('session')->getFlashBag()->add(
                'notice',
                $this->trans(self::$translation.'.admin.delete.flash.ok')
            );
        } else {
            $document->setDelete(true);
            $this->get('fhm_tools')->dm()->persist($document);
            $this->get('session')->getFlashBag()->add(
                'notice',
                $this->trans(self::$translation.'.admin.delete.flash.ok')
            );
        }
        $this->get('fhm_tools')->dm()->flush();

        return $this->redirect($this->getUrl(self::$source.'_admin_'.self::$route));
    }

    /**
     * @param $id
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function undeleteAction($id)
    {
        $this->denyAccessUnlessGranted('ROLE_SUPER_ADMIN', null, $this->trans(self::$translation.'.error.forbidden'));
        $document = $this->get('fhm_tools')->dmRepository(self::$repository)->find($id);
        if (!is_object($document)) {
            throw $this->createNotFoundException($this->trans(self::$translation.'.error.unknown'));
        }
        $document->setDelete(false);
        $this->get('fhm_tools')->dmPersist($document);
        $this->get('session')->getFlashBag()->add(
            'notice',
            $this->trans(self::$translation.'.admin.undelete.flash.ok')
        );

        return $this->redirect($this->getUrl(self::$source.'_admin_'.self::$route));
    }

    /**
     * @param $id
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function activateAction($id)
    {
        $document = $this->get('fhm_tools')->dmRepository(self::$repository)->find($id);
        if (!is_object($document)) {
            throw $this->createNotFoundException($this->trans(self::$translation.'.error.unknown'));
        }
        $document->setActive(true);
        $this->get('fhm_tools')->dmPersist($document);
        $this->get('session')->getFlashBag()->add(
            'notice',
            $this->trans(self::$translation.'.admin.activate.flash.ok')
        );

        return $this->redirect($this->getUrl(self::$source.'_admin_'.self::$route));
    }

    /**
     * @param $id
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deactivateAction($id)
    {
        $document = $this->get('fhm_tools')->dmRepository(self::$repository)->find($id);
        if (!is_object($document)) {
            throw $this->createNotFoundException($this->trans(self::$translation.'.error.unknown'));
        }
        $document->setActive(false);
        $this->get('fhm_tools')->dmPersist($document);
        $this->get('session')->getFlashBag()->add(
            'notice',
            $this->trans(self::$translation.'.admin.deactivate.flash.ok')
        );

        return $this->redirect($this->getUrl(self::$source.'_admin_'.self::$route));
    }

    /**
     * @param Request $request
     *
     * @return array
     */
    public function importAction(Request $request)
    {
        $classType = self::$form->type;
        $classHandler = self::$form->handler;
        $form = $this->createForm($classType);
        $handler = new $classHandler($form, $request);
        $process = $handler->process();
        if ($datas = $process) {
            $count = array(0, 0, 0);
            foreach ($datas as $data) {
                if (!isset($data['alias'])) {
                    $data['alias'] = $this->get('fhm_tools')->getAlias(
                        isset($data['id']) ? $data['id'] : null,
                        $data['name'],
                        self::$repository
                    );
                }
                $document = $this->get('fhm_tools')->dmRepository(self::$repository)->getImport($data);
                if ($document === 'error') {
                    $count[2]++;
                } elseif ($document) {
                    $count[1]++;
                    $document->setCsvData($data);
                    $this->get('fhm_tools')->dm()->persist($document);
                } else {
                    $count[0]++;
                    $document = new self::$class;
                    $document->setCsvData($data);
                    $this->get('fhm_tools')->dm()->persist($document);
                }
            }
            $this->get('fhm_tools')->dm()->flush();
            // Message
            $this->get('session')->getFlashBag()->add(
                'notice',
                $this->trans(
                    self::$translation.'.admin.import.flash.ok',
                    array('%countAdd%' => $count[0], '%countUpdate%' => $count[1], '%countError%' => $count[2])
                )
            );
        }

        return array(
            'form' => $form->createView(),
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
        $form = $this->createForm(ExportType::class);
        $process = $form->handleRequest($request);
        if ($process) {
            $documents = $this->get('fhm_tools')->dmRepository(self::$repository)->getExport();
            $datas = array(self::$document->getCsvHeader());
            foreach ($documents as $document) {
                $datas[] = $document->getCsvData();
            }

            return $this->get('fhm_tools')->csvExport($datas, '', ',');
        }

        return array(
            'form' => $form->createView(),
            'breadcrumbs' => $this->get('fhm_tools')->generateBreadcrumbs(
                array(
                    'domain' => self::$domain,
                    '_route' => $this->get('request_stack')->getCurrentRequest()->get('_route'),
                )
            ),
        );
    }
}