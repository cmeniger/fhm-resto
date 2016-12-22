<?php
namespace Fhm\MediaBundle\Controller;

use Fhm\FhmBundle\Controller\RefAdminController as FhmController;
use Fhm\FhmBundle\Form\Handler\Admin\CreateHandler;
use Fhm\FhmBundle\Form\Handler\Admin\UpdateHandler;
use Fhm\MediaBundle\Document\Media;
use Fhm\MediaBundle\Form\Type\Admin\CreateType;
use Fhm\MediaBundle\Form\Type\Admin\UpdateType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * @Route("/admin/media")
 * ----------------------------------
 * Class AdminController
 * @package Fhm\MediaBundle\Controller
 */
class AdminController extends FhmController
{
    /**
     * AdminController constructor.
     */
    public function __construct()
    {
        self::$repository = "FhmMediaBundle:Media";
        self::$source = "fhm";
        self::$domain = "FhmMediaBundle";
        self::$translation = "media";
        self::$class = Media::class;
        self::$route = "media";
        self::$form = new \stdClass();
        self::$form->createType = CreateType::class;
        self::$form->createHandler = CreateHandler::class;
        self::$form->updateType = UpdateType::class;
        self::$form->updateHandler = UpdateHandler::class;
    }


    /**
     * @Route
     * (
     *      path="/",
     *      name="fhm_admin_media"
     * )
     * @Template("::FhmMedia/Admin/index.html.twig")
     */
    public function indexAction()
    {
        return parent::indexAction();
    }

    /**
     * @Route
     * (
     *      path="/create",
     *      name="fhm_admin_media_create"
     * )
     * @Template("::FhmMedia/Admin/create.html.twig")
     */
    public function createAction(Request $request)
    {
        $document = new self::$class;
        $form = $this->createForm(self::$form->createType, $document);
        $handler = new self::$form->createHandler($form, $request);
        $process = $handler->process();
        if ($process) {
            $data = $request->get($form->getName());
            // Tag
            if (isset($data['tag']) && $data['tag']) {
                $tag = $this->get('fhm_tools')->dmRepository('FhmMediaBundle:MediaTag')->getByName($data['tag']);
                if ($tag == "") {
                    $tag = new \Fhm\MediaBundle\Document\MediaTag();
                    $tag->setName($data['tag']);
                    $tag->setActive(true);
                }
                if (isset($data['parent']) && $data['parent']) {
                    $tag->setParent(
                        $this->get('fhm_tools')->dmRepository('FhmMediaBundle:MediaTag')->find($data['parent'])
                    );
                }
                $this->get('fhm_tools')->dmPersist($tag);
                $document->addTag($tag);
            }
            $fileData = array(
                'tmp_name' => isset($_FILES['file']) ? $_FILES['file']['tmp_name'] : $_FILES[$form->getName(
                )]['tmp_name']['file'],
                'name' => isset($_FILES['file']) ? $_FILES['file']['name'] : $_FILES[$form->getName()]['name']['file'],
                'type' => isset($_FILES['file']) ? $_FILES['file']['type'] : $_FILES[$form->getName()]['type']['file'],
            );
            $file = new UploadedFile($fileData['tmp_name'], $fileData['name'], $fileData['type']);
            $tab = explode('.', $fileData['name']);
            $name = $data['name'] ? $this->get('fhm_tools')->getUnique(null, $data['name'], true) : $tab[0];
            // Persist
            $document->setName($name);
            $document->setFile($file);
            $document->setUserCreate($this->getUser());
            $document->setAlias($this->get('fhm_tools')->getAlias($document->getId(), $document->getName()));
            $document->setWatermark((array)$request->get('watermark'));
            $this->get('fhm_tools')->dmPersist($document);
            $this->get($this->get('fhm_tools')->getParameters('service', 'fhm_media'))->setDocument(
                $document
            )->setWatermark(
                $request->get('watermark')
            )->execute();
        }

        return array(
            'form' => $form->createView(),
            'watermarks' => $this->get('fhm_tools')->getParameters('watermark', 'fhm_media') ? $this->get(
                'fhm_tools'
            )->getParameters(
                'files',
                'fhm_media'
            ) : '',
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
                    'link' => $this->getUrl('fhm_admin_'.self::$route),
                    'text' => $this->trans(self::$translation.'.admin.index.breadcrumb'),
                ),
                array(
                    'link' => $this->getUrl('fhm_admin_'.self::$route.'_create'),
                    'text' => $this->trans(self::$translation.'.admin.create.breadcrumb'),
                    'current' => true,
                ),
            ),
        );
    }

    /**
     * @Route
     * (
     *      path="/duplicate/{id}",
     *      name="fhm_admin_media_duplicate",
     *      requirements={"id"="[a-z0-9]*"}
     * )
     * @Template("::FhmMedia/Admin/create.html.twig")
     */
    public function duplicateAction(Request $request, $id)
    {
        return parent::duplicateAction($request, $id);
    }

    /**
     * @Route
     * (
     *      path="/update/{id}",
     *      name="fhm_admin_media_update",
     *      requirements={"id"="[a-z0-9]*"}
     * )
     * @Template("::FhmMedia/Admin/update.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $document = $this->get('fhm_tools')->dmRepository(self::$repository)->find($id);
        // ERROR - unknown
        if ($document == "") {
            throw $this->createNotFoundException($this->trans(self::$translation.'.error.unknown'));
        }
        if (!$this->getUser()->hasRole('ROLE_ADMIN')) {
            throw new HttpException(403, $this->trans(self::$translation.'.error.forbidden'));
        }
        if (!$this->getUser()->hasRole('ROLE_SUPER_ADMIN') && $document->getDelete()) {
            throw new HttpException(403, $this->trans(self::$translation.'.error.forbidden'));
        }
        $form = $this->createForm(self::$form->updateType, $document);
        $handler = new self::$form->updateHandler($form, $request);
        $process = $handler->process();
        if ($process) {
            $data = $request->get($form->getName());
            // Tag
            if (isset($data['tag']) && $data['tag']) {
                $tag = new \Fhm\MediaBundle\Document\MediaTag();
                $tag->setName($data['tag']);
                $tag->setActive(true);
                if (isset($data['parent']) && $data['parent']) {
                    $tag->setParent(
                        $this->get('fhm_tools')->dmRepository('FhmMediaBundle:MediaTag')->find($data['parent'])
                    );
                }
                $this->get('fhm_tools')->dmPersist($tag);
                $document->addTag($tag);
            }
            // Persist
            $document->setUserUpdate($this->getUser());
            $document->setAlias($this->get('fhm_tools')->getAlias($document->getId(), $document->getName()));
            $this->get('fhm_tools')->dmPersist($document);
            if ($document->getFile()) {
                $this->get($this->get('fhm_tools')->getParameters('service', 'fhm_media'))->setDocument(
                    $document
                )->setWatermark($request->get('watermark'))->execute();
            } elseif ($request->get('generate') || $document->getWatermark() != $request->get('watermark')) {
                $this->get($this->get('fhm_tools')->getParameters('service', 'fhm_media'))->setDocument(
                    $document
                )->setWatermark($request->get('watermark'))->generateImage();
                $document->setWatermark((array)$request->get('watermark'));
                $this->get('fhm_tools')->dmPersist($document);
            }
            // Message
            $this->get('session')->getFlashBag()->add(
                'notice',
                $this->trans(self::$translation.'.admin.update.flash.ok')
            );
            // Redirect
            $redirect = $this->redirect($this->getUrl('fhm_admin_'.self::$route));
            $redirect = isset($data['submitSave']) ? $this->redirect(
                $this->getUrl('fhm_admin_'.self::$route.'_update', array('id' => $document->getId()))
            ) : $redirect;
            $redirect = isset($data['submitDuplicate']) ? $this->redirect(
                $this->getUrl('fhm_admin_'.self::$route.'_duplicate', array('id' => $document->getId()))
            ) : $redirect;
            $redirect = isset($data['submitNew']) ? $this->redirect(
                $this->getUrl('fhm_admin_'.self::$route.'_create')
            ) : $redirect;
            $redirect = isset($data['submitConfig']) ? $this->redirect(
                $this->getUrl('fhm_admin_'.self::$route.'_detail', array('id' => $document->getId()))
            ) : $redirect;

            return $redirect;
        }

        return array(
            'document' => $document,
            'form' => $form->createView(),
            'watermarks' => $this->get('fhm_tools')->getParameters('watermark', 'fhm_media') ? $this->get(
                'fhm_tools'
            )->getParameters(
                'files',
                'fhm_media'
            ) : '',
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
                    'link' => $this->getUrl('fhm_admin_'.self::$route),
                    'text' => $this->trans(self::$translation.'.admin.index.breadcrumb'),
                ),
                array(
                    'link' => $this->getUrl('fhm_admin_'.self::$route.'_detail', array('id' => $id)),
                    'text' => $this->trans(
                        '.admin.detail.breadcrumb',
                        array('%name%' => $document->getName())
                    ),
                ),
                array(
                    'link' => $this->getUrl('fhm_admin_'.self::$route.'_update', array('id' => $id)),
                    'text' => $this->trans('.admin.update.breadcrumb'),
                    'current' => true,
                ),
            ),
        );
    }

    /**
     * @Route
     * (
     *      path="/detail/{id}",
     *      name="fhm_admin_media_detail",
     *      requirements={"id"="[a-z0-9]*"}
     * )
     * @Template("::FhmMedia/Admin/detail.html.twig")
     */
    public function detailAction($id)
    {
        return array_merge(
            parent::detailAction($id),
            array(
                'files' => $this->get('fhm_tools')->getParameters('files', 'fhm_media'),
            )
        );
    }

    /**
     * @Route
     * (
     *      path="/delete/{id}",
     *      name="fhm_admin_media_delete",
     *      requirements={"id"="[a-z0-9]*"}
     * )
     */
    public function deleteAction($id)
    {
        $document = $this->get('fhm_tools')->dmRepository(self::$repository)->find($id);
        // ERROR - Unknown
        if ($document == "") {
            throw $this->createNotFoundException($this->trans(self::$translation.'.error.unknown'));
        } // ERROR - Forbidden
        elseif ($document->getDelete() && !$this->getUser()->isSuperAdmin()) {
            throw new HttpException(403, $this->trans(self::$translation.'.error.forbidden'));
        }
        // Delete
        if ($document->getDelete()) {
            $this->get($this->get('fhm_tools')->getParameters('service', 'fhm_media'))->setDocument($document)->remove(
            );
            $this->get('fhm_tools')->dmRemove($document);
            // Message
            $this->get('session')->getFlashBag()->add(
                'notice',
                $this->trans(self::$translation.'.admin.delete.flash.ok')
            );
        } else {
            $document->setDelete(true);
            $this->get('fhm_tools')->dmPersist($document);
            // Message
            $this->get('session')->getFlashBag()->add(
                'notice',
                $this->trans(self::$translation.'.admin.delete.flash.ok')
            );
        }

        return $this->redirect($this->getUrl('fhm_admin_'.self::$route));
    }

    /**
     * @Route
     * (
     *      path="/undelete/{id}",
     *      name="fhm_admin_media_undelete",
     *      requirements={"id"="[a-z0-9]*"}
     * )
     */
    public function undeleteAction($id)
    {
        return parent::undeleteAction($id);
    }

    /**
     * @Route
     * (
     *      path="/activate/{id}",
     *      name="fhm_admin_media_activate",
     *      requirements={"id"="[a-z0-9]*"}
     * )
     */
    public function activateAction($id)
    {
        return parent::activateAction($id);
    }

    /**
     * @Route
     * (
     *      path="/deactivate/{id}",
     *      name="fhm_admin_media_deactivate",
     *      requirements={"id"="[a-z0-9]*"}
     * )
     */
    public function deactivateAction($id)
    {
        return parent::deactivateAction($id);
    }

    /**
     * @Route
     * (
     *      path="/import",
     *      name="fhm_admin_media_import"
     * )
     * @Template("::FhmMedia/Admin/import.html.twig")
     */
    public function importAction(Request $request)
    {
        return parent::importAction($request);
    }

    /**
     * @Route
     * (
     *      path="/export",
     *      name="fhm_admin_media_export"
     * )
     * @Template("::FhmMedia/Admin/export.html.twig")
     */
    public function exportAction(Request $request)
    {
        return parent::exportAction($request);
    }
}