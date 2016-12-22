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
        /** Ajax request**/
        if ($request->isXmlHttpRequest()) {
            return array('documents' => $query->execute()->toArray());
        } else {
            return array(
                'form' => $this->createForm(SearchType::class)->createView(),
                'pagination' => $pagination,
                'breadcrumbs' => array(
                    array(
                        'link' => $this->getUrl('project_home'),
                        'text' => $this->trans('project.home.breadcrumb', array(), 'ProjectDefaultBundle'),
                    ),
                    array(
                        'link' => $this->getUrl('fhm_admin'),
                        'text' => $this->trans('fhm.admin.breadcrumb', array(), 'FhmFhmBundle'),
                    ),
                    array(
                        'link' => $this->getUrl(self::$source.'_admin_'.self::$route),
                        'text' => $this->trans(self::$translation.'.admin.index.breadcrumb'),
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
        $document = new self::$class;
        $form = $this->createForm(
            self::$form->createType,
            $document,
            array(
                'data_class' => self::$class,
                'translation_domain' => self::$domain,
                'translation_route' => self::$translation,
                'user_admin' => $this->getUser()->hasRole('ROLE_SUPER_ADMIN'),
            )
        );
        $handler = new self::$form->createHandler($form, $request);
        $process = $handler->process();
        if ($process) {
            $data = $request->get($form->getName());
            $document->setUserCreate($this->getUser());
            $document->setAlias(
                $this->get('fhm_tools')->getAlias(
                    $document->getId(),
                    $document->getName(),
                    self::$repository
                )
            );
            $this->get('fhm_tools')->dmPersist($document);
            $this->get('session')->getFlashBag()->add(
                'notice',
                $this->trans(self::$translation.'.admin.create.flash.ok')
            );

            return $this->redirectUrl($data, $document);
        }

        return array(
            'form' => $form->createView(),
            'breadcrumbs' => array(
                array(
                    'link' => $this->getUrl('project_home'),
                    'text' => $this->trans('project.home.breadcrumb', array(), 'ProjectDefaultBundle'),
                ),
                array(
                    'link' => $this->getUrl('fhm_admin'),
                    'text' => $this->trans('fhm.admin.breadcrumb', array(), 'FhmFhmBundle'),
                ),
                array(
                    'link' => $this->getUrl(self::$source.'_admin_'.self::$route),
                    'text' => $this->trans(self::$translation.'.admin.index.breadcrumb'),
                ),
                array(
                    'link' => $this->getUrl(self::$source.'_admin_'.self::$route.'_create'),
                    'text' => $this->trans(self::$translation.'.admin.create.breadcrumb'),
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
        if ($document == "") {
            throw $this->createNotFoundException($this->trans(self::$translation.'.error.unknown'));
        }
        $form = $this->createForm(
            self::$form->updateType,
            $document,
            array(
                'data_class' => self::$class,
                'translation_domain' => self::$domain,
                'translation_route' => self::$translation,
                'user_admin' => $this->get('security.authorization_checker')->isGranted('ROLE_SUPER_ADMIN'),
            )
        );
        $handler = new self::$form->updateHandler($form, $request);
        $process = $handler->process();
        if ($process) {
            $data = $request->get($form->getName());
            $document->setUserUpdate($this->getUser());
            $this->get('fhm_tools')->dmPersist($document);
            $this->get('session')->getFlashBag()->add(
                'notice',
                $this->trans(self::$translation.'.admin.update.flash.ok')
            );

            /** Redirect **/
            return $this->redirectUrl($data, $document);
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
                    'link' => $this->getUrl('fhm_admin'),
                    'text' => $this->trans('fhm.admin.breadcrumb', array(), 'FhmFhmBundle'),
                ),
                array(
                    'link' => $this->getUrl(self::$source.'_admin_'.self::$route),
                    'text' => $this->trans(self::$translation.'.admin.index.breadcrumb'),
                ),
                array(
                    'link' => $this->getUrl(
                        self::$source.'_admin_'.self::$route.'_detail',
                        array('id' => $id)
                    ),
                    'text' => $this->trans(
                        self::$translation.'.admin.detail.breadcrumb',
                        array('%name%' => $document->getName())
                    ),
                ),
                array(
                    'link' => $this->getUrl(
                        self::$source.'_admin_'.self::$route.'_update',
                        array('id' => $id)
                    ),
                    'text' => $this->trans(self::$translation.'.admin.update.breadcrumb'),
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
        $document = $this->get('fhm_tools')->dmRepository(self::$repository)->find($id);
        if ($document == "") {
            throw $this->createNotFoundException($this->trans(self::$translation.'.error.unknown'));
        }

        return array(
            'document' => $document,
            'historics' => $this->get('fhm_historic')->getAllHistoricsByObject($document),
            'breadcrumbs' => array(
                array(
                    'link' => $this->getUrl('project_home'),
                    'text' => $this->trans('project.home.breadcrumb', array(), 'ProjectDefaultBundle'),
                ),
                array(
                    'link' => $this->getUrl('fhm_admin'),
                    'text' => $this->trans('fhm.admin.breadcrumb', array(), 'FhmFhmBundle'),
                ),
                array(
                    'link' => $this->getUrl(self::$source.'_admin_'.self::$route),
                    'text' => $this->trans(self::$translation.'.admin.index.breadcrumb'),
                ),
                array(
                    'link' => $this->getUrl(
                        self::$source.'_admin_'.self::$route.'_detail',
                        array('id' => $id)
                    ),
                    'text' => $this->trans(
                        self::$translation.'.admin.detail.breadcrumb',
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
        $this->denyAccessUnlessGranted('ROLE_ADMIN', null, $this->trans(self::$translation.'.error.forbidden'));
        $document = $this->get('fhm_tools')->dmRepository(self::$repository)->find($id);
        if ($document == "") {
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
        if ($document == "") {
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
        if ($document == "") {
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
        if ($document == "") {
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
                        $data['name']
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
            'breadcrumbs' => array(
                array(
                    'link' => $this->getUrl('project_home'),
                    'text' => $this->trans('project.home.breadcrumb', array(), 'ProjectDefaultBundle'),
                ),
                array(
                    'link' => $this->getUrl('fhm_admin'),
                    'text' => $this->trans('fhm.admin.breadcrumb', array(), 'FhmFhmBundle'),
                ),
                array(
                    'link' => $this->getUrl(self::$source.'_admin_'.self::$source),
                    'text' => $this->trans('.admin.index.breadcrumb'),
                ),
                array(
                    'link' => $this->getUrl(self::$source.'_admin_'.self::$source.'_import'),
                    'text' => $this->trans(self::$source.'.admin.import.breadcrumb'),
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
            'breadcrumbs' => array(
                array(
                    'link' => $this->getUrl('project_home'),
                    'text' => $this->trans('project.home.breadcrumb', array(), 'ProjectDefaultBundle'),
                ),
                array(
                    'link' => $this->getUrl('fhm_admin'),
                    'text' => $this->trans('fhm.admin.breadcrumb', array(), 'FhmFhmBundle'),
                ),
                array(
                    'link' => $this->getUrl(self::$source.'_admin_'.self::$source),
                    'text' => $this->trans(self::$translation.'.admin.index.breadcrumb'),
                ),
                array(
                    'link' => $this->getUrl(self::$source.'_admin_'.self::$source.'_export'),
                    'text' => $this->trans(self::$translation.'.admin.export.breadcrumb'),
                    'current' => true,
                ),
            ),
        );
    }

    /**
     * @param $data
     * @return mixed
     */
    private function redirectUrl($data, $document)
    {
        $redirect = $this->redirect($this->getUrl(self::$source.'_admin_'.self::$route));
        $redirect = isset($data['submitSave']) ? $this->redirect(
            $this->getUrl(
                self::$source.'_admin_'.self::$route.'_update',
                array('id' => $document->getId())
            )
        ) : $redirect;
        $redirect = isset($data['submitDuplicate']) ? $this->redirect(
            $this->getUrl(
                self::$source.'_admin_'.self::$route.'_duplicate',
                array('id' => $document->getId())
            )
        ) : $redirect;
        $redirect = isset($data['submitNew']) ? $this->redirect(
            $this->getUrl(self::$source.'_admin_'.self::$route.'_create')
        ) : $redirect;
        $redirect = isset($data['submitConfig']) ? $this->redirect(
            $this->getUrl(
                self::$source.'_admin_'.self::$route.'_detail',
                array('id' => $document->getId())
            )
        ) : $redirect;

        return $redirect;
    }
}