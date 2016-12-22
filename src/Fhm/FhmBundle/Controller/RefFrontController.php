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
        $form->setData($this->get('request_stack')->getCurrentRequest()->get($form->getName()));
        $dataSearch = $form->getData();
        $documents = $this->get('fhm_tools')->dmRepository(self::$repository)->getFrontIndex($dataSearch['search']);

        return array(
            'documents' => $documents,
            'form' => $form->createView(),
            'breadcrumbs' => array(
                array(
                    'link' => $this->getUrl('project_home'),
                    'text' => $this->trans('project.home.breadcrumb', array(), 'ProjectDefaultBundle'),
                ),
                array(
                    'link' => $this->getUrl(self::$source.'_'.self::$route),
                    'text' => $this->trans(self::$translation.'.front.index.breadcrumb'),
                    'current' => true,
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
        $document = self::$document;
        $classHandler = self::$form->handler;
        $form = $this->createForm(
            self::$form->type,
            $document,
            array(
                'data_class' => self::$class,
                'translation_domain' => self::$domain,
                'translation_route' => self::$translation,
                'user_admin' => $this->get('security.authorization_checker')->isGranted('ROLE_ADMIN'),
            )
        );
        $handler = new $classHandler($form, $request);
        $process = $handler->process();
        if ($process) {
            $data = $request->get($form->getName());
            $document->setUserCreate($this->getUser());
            $document->setAlias(
                $this->get('fhm_tools')->getAlias($document->getId(), $document->getName(), self::$repository)
            );
            $this->get('fhm_tools')->dmPersist($document);
            // Message
            $this->get('session')->getFlashBag()->add(
                'notice',
                $this->trans(self::$translation.'.front.create.flash.ok')
            );
            // Redirect
            $redirect = $this->redirect($this->getUrl(self::$source.'_'.self::$route));
            $redirect = isset($data['submitDuplicate']) ? $this->redirect(
                $this->getUrl(
                    self::$source.'_'.self::$route.'_duplicate',
                    array('id' => $document->getId())
                )
            ) : $redirect;
            $redirect = isset($data['submitNew']) ? $this->redirect(
                $this->getUrl(self::$source.'_'.self::$route.'_create')
            ) : $redirect;

            return $redirect;
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
        $document = $this->get('fhm_tools')->dmRepository(self::$repository)->find($id);
        $classHandler = self::$form->handler;
        if ($document == "") {
            throw $this->createNotFoundException($this->trans(self::$translation.'.error.unknown'));
        }
        if (!$this->get('security.authorization_checker')->isGranted('ROLE_SUPER_ADMIN') && $document->getDelete()) {
            throw new HttpException(403, $this->trans(self::$translation.'.error.forbidden'));
        }
        $form = $this->createForm(
            self::$form->type,
            $document,
            array(
                'data_class' => self::$class,
                'translation_domain' => self::$domain,
                'translation_route' => self::$translation,
                'user_admin' => $this->get('security.authorization_checker')->isGranted('ROLE_ADMIN'),
            )
        );
        $handler = new $classHandler($form, $request);
        $process = $handler->process($document, $this->get('fhm_tools')->dm(), self::$bundle);
        if ($process) {
            $document->setUserUpdate($this->getUser());
            $document->setAlias($this->getAlias($document->getId(), $document->getName()));
            $document->setActive(false);
            $this->get('fhm_tools')->dmPersist($document);
            $this->get('session')->getFlashBag()->add(
                'notice',
                $this->trans(self::$translation.'.front.update.flash.ok')
            );
            // Redirect
            $redirect = $this->redirect($this->getUrl(self::$source.'_'.self::$route));
            $redirect = isset($data['submitDuplicate']) ? $this->redirect(
                $this->getUrl(
                    self::$source.'_'.self::$route.'_duplicate',
                    array('id' => $document->getId())
                )
            ) : $redirect;
            $redirect = isset($data['submitNew']) ? $this->redirect(
                $this->getUrl(self::$source.'_'.self::$route.'_create')
            ) : $redirect;

            return $redirect;
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
        $document = $this->get('fhm_tools')->dmRepository(self::$repository)->getById($id);
        $document = ($document) ? $document : $this->get('fhm_tools')->dmRepository(self::$repository)->getByAlias($id);
        $document = ($document) ? $document : $this->get('fhm_tools')->dmRepository(self::$repository)->getByName($id);
        if ($document == "") {
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