<?php
namespace Fhm\MediaBundle\Controller;

use Fhm\FhmBundle\Controller\RefAdminController as FhmController;
use Fhm\MediaBundle\Document\Media;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * @Route("/admin/media")
 */
class AdminController extends FhmController
{
    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct('Fhm', 'Media', 'media');
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
        // ERROR - Unknown route
        if(!$this->routeExists('fhm_admin_' . $this->route) || !$this->routeExists('fhm_admin_' . $this->route . '_create'))
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
            // Tag
            if(isset($data['tag']) && $data['tag'])
            {
                $tag = $this->dmRepository('FhmMediaBundle:MediaTag')->getByName($data['tag']);
                if($tag == "")
                {
                    $tag = new \Fhm\MediaBundle\Document\MediaTag();
                    $tag->setName($data['tag']);
                    $tag->setActive(true);
                }
                if(isset($data['parent']) && $data['parent'])
                {
                    $tag->setParent($this->dmRepository('FhmMediaBundle:MediaTag')->find($data['parent']));
                }
                $this->dmPersist($tag);
                $document->addTag($tag);
            }
            $fileData = array
            (
                'tmp_name' => isset($_FILES['file']) ? $_FILES['file']['tmp_name'] : $_FILES[$form->getName()]['tmp_name']['file'],
                'name'     => isset($_FILES['file']) ? $_FILES['file']['name'] : $_FILES[$form->getName()]['name']['file'],
                'type'     => isset($_FILES['file']) ? $_FILES['file']['type'] : $_FILES[$form->getName()]['type']['file']
            );
            $file     = new UploadedFile($fileData['tmp_name'], $fileData['name'], $fileData['type']);
            $tab      = explode('.', $fileData['name']);
            $name     = $data['name'] ? $this->getUnique(null, $data['name'], true) : $tab[0];
            // Persist
            $document->setName($name);
            $document->setFile($file);
            $document->setUserCreate($this->getUser());
            $document->setAlias($this->getAlias($document->getId(), $document->getName()));
            $document->setWatermark((array) $request->get('watermark'));
            $this->dmPersist($document);
            $this->get($this->getParameters('service','fhm_media'))->setDocument($document)->setWatermark($request->get('watermark'))->execute();
        }

        return array(
            'form'        => $form->createView(),
            'instance'    => $instance,
            'watermarks'  => $this->getParameters('watermark', 'fhm_media') ? $this->getParameters('files', 'fhm_media') : '',
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
                    'link' => $this->get('router')->generate('fhm_admin_' . $this->route),
                    'text' => $this->get('translator')->trans($this->translation[1] . '.admin.index.breadcrumb', array(), $this->translation[0]),
                ),
                array(
                    'link'    => $this->get('router')->generate('fhm_admin_' . $this->route . '_create'),
                    'text'    => $this->get('translator')->trans($this->translation[1] . '.admin.create.breadcrumb', array(), $this->translation[0]),
                    'current' => true
                )
            )
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
        // ERROR - Unknown route
        if(!$this->routeExists('fhm_admin_' . $this->route) || !$this->routeExists('fhm_admin_' . $this->route . '_update'))
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
        $process = $handler->process();
        if($process)
        {
            $data = $request->get($form->getName());
            // Tag
            if(isset($data['tag']) && $data['tag'])
            {
                $tag = new \Fhm\MediaBundle\Document\MediaTag();
                $tag->setName($data['tag']);
                $tag->setActive(true);
                if(isset($data['parent']) && $data['parent'])
                {
                    $tag->setParent($this->dmRepository('FhmMediaBundle:MediaTag')->find($data['parent']));
                }
                $this->dmPersist($tag);
                $document->addTag($tag);
            }
            // Persist
            $document->setUserUpdate($this->getUser());
            $document->setAlias($this->getAlias($document->getId(), $document->getName()));
            $this->dmPersist($document);
            if($document->getFile())
            {
                $this->get($this->getParameters('service','fhm_media'))->setDocument($document)->setWatermark($request->get('watermark'))->execute();
            }
            elseif($request->get('generate') || $document->getWatermark() != $request->get('watermark'))
            {
                $this->get($this->getParameters('service','fhm_media'))->setDocument($document)->setWatermark($request->get('watermark'))->generateImage();
                $document->setWatermark((array) $request->get('watermark'));
                $this->dmPersist($document);
            }
            // Message
            $this->get('session')->getFlashBag()->add('notice', $this->get('translator')->trans($this->translation[1] . '.admin.update.flash.ok', array(), $this->translation[0]));
            // Redirect
            $redirect = $this->redirect($this->generateUrl('fhm_admin_' . $this->route));
            $redirect = isset($data['submitSave']) ? $this->redirect($this->generateUrl('fhm_admin_' . $this->route . '_update', array('id' => $document->getId()))) : $redirect;
            $redirect = isset($data['submitDuplicate']) ? $this->redirect($this->generateUrl('fhm_admin_' . $this->route . '_duplicate', array('id' => $document->getId()))) : $redirect;
            $redirect = isset($data['submitNew']) ? $this->redirect($this->generateUrl('fhm_admin_' . $this->route . '_create')) : $redirect;
            $redirect = isset($data['submitConfig']) ? $this->redirect($this->generateUrl('fhm_admin_' . $this->route . '_detail', array('id' => $document->getId()))) : $redirect;

            return $redirect;
        }

        return array(
            'document'    => $document,
            'form'        => $form->createView(),
            'instance'    => $instance,
            'watermarks'  => $this->getParameters('watermark', 'fhm_media') ? $this->getParameters('files', 'fhm_media') : '',
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
                    'link' => $this->get('router')->generate('fhm_admin_' . $this->route),
                    'text' => $this->get('translator')->trans($this->translation[1] . '.admin.index.breadcrumb', array(), $this->translation[0]),
                ),
                array(
                    'link' => $this->get('router')->generate('fhm_admin_' . $this->route . '_detail', array('id' => $id)),
                    'text' => $this->get('translator')->trans($this->translation[1] . '.admin.detail.breadcrumb', array('%name%' => $document->getName()), $this->translation[0]),
                ),
                array(
                    'link'    => $this->get('router')->generate('fhm_admin_' . $this->route . '_update', array('id' => $id)),
                    'text'    => $this->get('translator')->trans($this->translation[1] . '.admin.update.breadcrumb', array(), $this->translation[0]),
                    'current' => true
                )
            )
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
                'files' => $this->getParameters('files', 'fhm_media')
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
        // ERROR - Unknown route
        if(!$this->routeExists('fhm_admin_' . $this->route))
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
            $this->get($this->getParameters('service','fhm_media'))->setDocument($document)->remove();
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

        return $this->redirect($this->generateUrl('fhm_admin_' . $this->route));
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

    /**
     * @Route
     * (
     *      path="/grouping",
     *      name="fhm_admin_media_grouping"
     * )
     */
    public function groupingAction(Request $request)
    {
        return parent::groupingAction($request);
    }
}