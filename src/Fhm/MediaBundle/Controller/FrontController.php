<?php
namespace Fhm\MediaBundle\Controller;

use Fhm\FhmBundle\Controller\RefFrontController as FhmController;
use Fhm\MediaBundle\Document\Media;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * @Route("/media")
 */
class FrontController extends FhmController
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
        if(!$this->routeExists('fhm_' . $this->route) || !$this->routeExists('fhm_' . $this->route . '_create'))
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
            $document->setActive(true);
            $this->dmPersist($document);
            $this->get($this->getParameters('service','fhm_media'))->setDocument($document)->setWatermark($request->get('watermark'))->execute();
        }

        return array(
            'form'        => $form->createView(),
            'instance'    => $this->instanceData(),
            'watermarks'  => $this->getParameters('watermark', 'fhm_media') ? $this->getParameters('files', 'fhm_media') : '',
            'breadcrumbs' => array(
                array(
                    'link' => $this->get('router')->generate('project_home'),
                    'text' => $this->get('translator')->trans('project.home.breadcrumb', array(), 'ProjectDefaultBundle'),
                ),
                array(
                    'link' => $this->get('router')->generate('fhm_' . $this->route),
                    'text' => $this->get('translator')->trans($this->translation[1] . '.front.index.breadcrumb', array(), $this->translation[0]),
                ),
                array(
                    'link'    => $this->get('router')->generate('fhm_' . $this->route . '_create'),
                    'text'    => $this->get('translator')->trans($this->translation[1] . '.front.create.breadcrumb', array(), $this->translation[0]),
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
        throw $this->createNotFoundException($this->get('translator')->trans('fhm.error.route', array(), 'FhmFhmBundle'));

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
        throw $this->createNotFoundException($this->get('translator')->trans('fhm.error.route', array(), 'FhmFhmBundle'));

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
        throw $this->createNotFoundException($this->get('translator')->trans('fhm.error.route', array(), 'FhmFhmBundle'));

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