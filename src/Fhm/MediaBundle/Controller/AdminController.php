<?php
namespace Fhm\MediaBundle\Controller;

use Fhm\FhmBundle\Controller\RefAdminController as FhmController;
use Fhm\FhmBundle\Form\Handler\Admin\CreateHandler;
use Fhm\FhmBundle\Form\Handler\Admin\UpdateHandler;
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
        self::$class = $this->get('fhm.object.manager')->getCurrentModelName('FhmMediaBundle:Media');
        $object = new self::$class;
        $form = $this->createForm(
            self::$form->createType,
            $object,
            array(
                'user_admin' => $this->getUser()->hasRole('ROLE_SUPER_ADMIN'),
                'data_class' => self::$class,
                'object_manager' => $this->get('fhm.object.manager'),
            )
        );
        $handler = new self::$form->createHandler($form, $request);
        $process = $handler->process();
        if ($process) {
            $data = $request->get($form->getName());
            if (isset($data['tag']) && $data['tag']) {
                $tagClasName = $this->get('fhm.object.manager')->getCurrentModelName('FhmMediaBundle:MediaTag');
                $tag = $this->get('fhm.object.manager')->getCurrentRepository('FhmMediaBundle:MediaTag')->findOneBy(
                    ['name' => $data['tag']]
                );
                if (!$tag) {
                    $tag = new $tagClasName;
                    $tag->setName($data['tag']);
                    $tag->setActive(true);
                    if (isset($data['parent']) && $data['parent']) {
                        $tag->setParent(
                            $this->get('fhm_tools')->dmRepository('FhmMediaBundle:MediaTag')->find($data['parent'])
                        );
                    }
                }
                $object->addTag($tag);
            }
            $file = $request->files->get('file');
            $object->setFile($file);
            $tab = explode('.', $file->getClientOriginalName());
            $name = $data['name'] ? $this->get('fhm_tools')->getUnique(
                $object->getId(),
                $data['name'],
                true,
                self::$repository
            ) : $tab[0];
            $object->setAlias($object->getName());
            // Persist
            $object->setName($name);
            $object->setWatermark((array)$request->get('watermark'));
            $this->get('fhm_tools')->dmPersist($object);
            $this->get('fhm_media_service')->setDocument($object)->setWatermark($request->get('watermark'))->execute();
        }

        return array(
            'form' => $form->createView(),
            'watermarks' => $this->get('fhm_tools')->getParameters('watermark', 'fhm_media') ? $this->get(
                'fhm_tools'
            )->getParameters(
                'files',
                'fhm_media'
            ) : '',
            'breadcrumbs' => $this->get('fhm_tools')->generateBreadcrumbs(
                array(
                    'domain' => self::$domain,
                    '_route' => $this->get('request_stack')->getCurrentRequest()->get('_route'),
                )
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
        $object = $this->get('fhm_tools')->dmRepository(self::$repository)->find($id);
        // ERROR - unknown
        if ($object == "") {
            throw $this->createNotFoundException($this->trans(self::$translation.'.error.unknown'));
        }
        if (!$this->getUser()->hasRole('ROLE_ADMIN')) {
            throw new HttpException(403, $this->trans(self::$translation.'.error.forbidden'));
        }
        if (!$this->getUser()->hasRole('ROLE_SUPER_ADMIN') && $object->getDelete()) {
            throw new HttpException(403, $this->trans(self::$translation.'.error.forbidden'));
        }
        self::$class = $this->get('fhm.object.manager')->getCurrentModelName('FhmMediaBundle:Media');
        $form = $this->createForm(
            self::$form->updateType,
            $object,
            array(
                'user_admin' => $this->getUser()->hasRole('ROLE_SUPER_ADMIN'),
                'data_class' => self::$class,
                'object_manager' => $this->get('fhm.object.manager'),
            )
        );
        $handler = new self::$form->updateHandler($form, $request);
        $process = $handler->process();
        if ($process) {
            $data = $request->get($form->getName());
            // Tag
            if (isset($data['tag']) && $data['tag']) {
                $tagClasName = $this->get('fhm.object.manager')->getCurrentModelName('FhmMediaBundle:MediaTag');
                $tag = $this->get('fhm.object.manager')->getCurrentRepository('FhmMediaBundle:MediaTag')->findOneBy(
                    ['name' => $data['tag']]
                );
                if (!$tag) {
                    $tag = new $tagClasName;
                    $tag->setName($data['tag']);
                    $tag->setActive(true);
                    if (isset($data['parent']) && $data['parent']) {
                        $tag->setParent(
                            $this->get('fhm_tools')->dmRepository('FhmMediaBundle:MediaTag')->find($data['parent'])
                        );
                    }
                }
                $object->addTag($tag);
            }
            // Persist
            $process = $this->get('fhm_media_service')->setModel($object);
            if ($request->get('generate') || $object->getWatermark() != $request->get('watermark')) {
                $process->generateImage();
                $object->setWatermark((array)$request->get('watermark'));
            } else {
                $process->setWatermark($request->get('watermark'))->execute();
            }
            $this->get('fhm_tools')->dmPersist($object);
            $this->get('session')->getFlashBag()->add(
                'notice',
                $this->trans(self::$translation.'.admin.update.flash.ok')
            );

            return $this->redirectUrl($data, $object);
        }

        return array(
            'object' => $object,
            'form' => $form->createView(),
            'watermarks' => $this->get('fhm_tools')->getParameters('watermark', 'fhm_media') ? $this->get(
                'fhm_tools'
            )->getParameters(
                'files',
                'fhm_media'
            ) : '',
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
        $object = $this->get('fhm_tools')->dmRepository(self::$repository)->find($id);
        // ERROR - Unknown
        if ($object == "") {
            throw $this->createNotFoundException($this->trans(self::$translation.'.error.unknown'));
        } // ERROR - Forbidden
        elseif ($object->getDelete() && !$this->getUser()->isSuperAdmin()) {
            throw new HttpException(403, $this->trans(self::$translation.'.error.forbidden'));
        }
        // Delete
        if ($object->getDelete()) {
            $this->get($this->get('fhm_tools')->getParameters('service', 'fhm_media'))->setDocument($object)->remove();
            $this->get('fhm_tools')->dmRemove($object);
            // Message
            $this->get('session')->getFlashBag()->add(
                'notice',
                $this->trans(self::$translation.'.admin.delete.flash.ok')
            );
        } else {
            $object->setDelete(true);
            $this->get('fhm_tools')->dmPersist($object);
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