<?php
namespace Fhm\MediaBundle\Controller;

use Fhm\FhmBundle\Controller\RefFrontController as FhmController;
use Fhm\MediaBundle\Document\Media;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * @Route("/media", service="fhm_media_controller_front")
 */
class FrontController extends FhmController
{
    /**
     * FrontController constructor.
     *
     * @param \Fhm\FhmBundle\Services\Tools $tools
     */
    public function __construct(\Fhm\FhmBundle\Services\Tools $tools)
    {
        $this->setFhmTools($tools);
        parent::__construct('Fhm', 'Media', 'media');
    }

    /**
     * @Route
     * (
     *      path="/",
     *      name="fhm_media"
     * )
     * @Template("::FhmMedia/Front/index.html.twig")
     */
    public function indexAction()
    {
        return parent::indexAction();
    }

    /**
     * @Route
     * (
     *      path="/create",
     *      name="fhm_media_create"
     * )
     * @Template("::FhmMedia/Front/create.html.twig")
     */


    /**
     * @Route
     * (
     *      path="/create",
     *      name="fhm_media_create"
     * )
     * @Template("::FhmMedia/Front/create.html.twig")
     */
    public function createAction(Request $request)
    {
        // ERROR - Unknown route
        if(!$this->fhm_tools->routeExists('fhm_' . $this->route) || !$this->fhm_tools->routeExists('fhm_' . $this->route . '_create'))
        {
            throw $this->createNotFoundException($this->fhm_tools->trans('fhm.error.route', array(), 'FhmFhmBundle'));
        }
        $document     = $this->document;
        $instance     = $this->fhm_tools->instanceData();
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
                $tag = $this->fhm_tools->dmRepository('FhmMediaBundle:MediaTag')->getByName($data['tag']);
                if($tag == "")
                {
                    $tag = new \Fhm\MediaBundle\Document\MediaTag();
                    $tag->setName($data['tag']);
                    $tag->setActive(true);
                }
                if(isset($data['parent']) && $data['parent'])
                {
                    $tag->setParent($this->fhm_tools->dmRepository('FhmMediaBundle:MediaTag')->find($data['parent']));
                }
                $this->fhm_tools->dmPersist($tag);
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
            $name     = $data['name'] ? $this->fhm_tools->getUnique(null, $data['name'], true) : $tab[0];
            // Persist
            $document->setName($name);
            $document->setFile($file);
            $document->setUserCreate($this->getUser());
            $document->setAlias($this->fhm_tools->getAlias($document->getId(), $document->getName()));
            $document->setWatermark((array) $request->get('watermark'));
            $document->setActive(true);
            $this->fhm_tools->dmPersist($document);
            $this->get($this->fhm_tools->getParameter('service','fhm_media'))->setDocument($document)->setWatermark($request->get('watermark'))->execute();
        }

        return array(
            'form'        => $form->createView(),
            'instance'    => $this->fhm_tools->instanceData(),
            'watermarks'  => $this->fhm_tools->getParameter('watermark', 'fhm_media') ? $this->fhm_tools->getParameter('files', 'fhm_media') : '',
            'breadcrumbs' => array(
                array(
                    'link' => $this->fhm_tools->getUrl('project_home'),
                    'text' => $this->fhm_tools->trans('project.home.breadcrumb', array(), 'ProjectDefaultBundle'),
                ),
                array(
                    'link' => $this->fhm_tools->getUrl('fhm_' . $this->route),
                    'text' => $this->fhm_tools->trans('.front.index.breadcrumb'),
                ),
                array(
                    'link'    => $this->fhm_tools->getUrl('fhm_' . $this->route . '_create'),
                    'text'    => $this->fhm_tools->trans('.front.create.breadcrumb'),
                    'current' => true
                )
            )
        );
    }

    /**
    * @Route
    * (
    *      path="/duplicate/{id}",
    *      name="fhm_media_duplicate",
    *      requirements={"id"="[a-z0-9]*"}
    * )
    * @Template("::FhmMedia/Front/create.html.twig")
    */
    public function duplicateAction(Request $request, $id)
    {
        // For activate this route, delete next line
        throw $this->createNotFoundException($this->fhm_tools->trans('fhm.error.route', array(), 'FhmFhmBundle'));

        return parent::duplicateAction($request, $id);
    }

    /**
    * @Route
    * (
    *      path="/update/{id}",
    *      name="fhm_media_update",
    *      requirements={"id"="[a-z0-9]*"}
    * )
    * @Template("::FhmMedia/Front/update.html.twig")
    */
    public function updateAction(Request $request, $id)
    {
        // For activate this route, delete next line
        throw $this->createNotFoundException($this->fhm_tools->trans('fhm.error.route', array(), 'FhmFhmBundle'));

        return parent::updateAction($request, $id);
    }

    /**
    * @Route
    * (
    *      path="/detail/{id}",
    *      name="fhm_media_detail",
    *      requirements={"id"=".+"}
    * )
    * @Template("::FhmMedia/Front/detail.html.twig")
    */
    public function detailAction($id)
    {
        return parent::detailAction($id);
    }

    /**
    * @Route
    * (
    *      path="/delete/{id}",
    *      name="fhm_media_delete",
    *      requirements={"id"="[a-z0-9]*"}
    * )
    */
    public function deleteAction($id)
    {
        // For activate this route, delete next line
        throw $this->createNotFoundException($this->fhm_tools->trans('fhm.error.route', array(), 'FhmFhmBundle'));

        return parent::deleteAction($id);
    }

    /**
    * @Route
    * (
    *      path="/{id}",
    *      name="fhm_media_lite",
    *      requirements={"id"=".+"}
    * )
    * @Template("::FhmMedia/Front/detail.html.twig")
    */
    public function liteAction($id)
    {
        return $this->detailAction($id);
    }
}