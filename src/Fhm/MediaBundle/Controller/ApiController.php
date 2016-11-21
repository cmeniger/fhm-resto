<?php
namespace Fhm\MediaBundle\Controller;

use Fhm\FhmBundle\Controller\RefApiController as FhmController;
use Fhm\MediaBundle\Document\Media;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * @Route("/api/media")
 */
class ApiController extends FhmController
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
     *      name="fhm_api_media"
     * )
     * @Template("::FhmMedia/Api/index.html.twig")
     */
    public function indexAction()
    {
        return parent::indexAction();
    }

    /**
     * @Route
     * (
     *      path="/autocomplete/",
     *      name="fhm_api_media_autocomplete"
     * )
     * @Template("::FhmMedia/Api/autocomplete.html.twig")
     */
    public function autocompleteAction(Request $request)
    {
        return parent::autocompleteAction($request);
    }

    /**
     * @Route
     * (
     *      path="/uploaded",
     *      name="fhm_api_media_uploaded"
     * )
     */
    public function uploadedAction(Request $request)
    {
        $counter  = $request->get('counter');
        $response = new JsonResponse();
        $data     = array();
        // Message
        $this->get('session')->getFlashBag()->add('notice', $this->get('translator')->trans($this->translation[1] . '.admin.uploaded.flash.ok', array('%accepted%' => $counter['accepted'], '%rejected%' => $counter['rejected']), $this->translation[0]));

        return $response->setData($data);
    }

    /**
     * @Route
     * (
     *      path="/data/admin",
     *      name="fhm_api_media_data_admin"
     * )
     * @Template("::FhmMedia/Template/data.admin.html.twig")
     */
    public function dataAdminAction(Request $request)
    {
        $data       = $request->get('media');
        $instance   = $this->instanceData();
        $tagMains   = $this->dmRepository('FhmMediaBundle:MediaTag')->setParent(true)->getAllFiltered($instance->grouping->filtered);
        $tagSons    = (isset($data['tag'])) ? $this->dmRepository('FhmMediaBundle:MediaTag')->getSons($data['tag'], $instance->grouping->filtered) : '';
        $tag        = (isset($data['tag'])) ? $this->dmRepository('FhmMediaBundle:MediaTag')->find($data['tag']) : '';
        $pagination = (isset($data['pagination'])) ? $data['pagination'] : 1;
        $search     = (isset($data['search'])) ? $data['search'] : '';
        $documents  = $this->dmRepository()->setTag((isset($data['tag'])) ? $data['tag'] : '')->getAdminIndex($search, $pagination, $this->getParameters('admin', 'fhm_media'), $instance->grouping->filtered, $instance->user->super);

        return array(
            'documents'  => $documents,
            'pagination' => $this->setSection('Admin')->setPagination($this->getParameters('admin', 'fhm_media'))->getPagination($pagination, count($documents), $this->dmRepository()->setTag((isset($data['tag'])) ? $data['tag'] : '')->getAdminCount($search, $instance->grouping->filtered, $instance->user->super), 'pagination', array(), $this->generateUrl('fhm_api_media_data_admin')),
            'instance'   => $instance,
            'tag'        => $tag,
            'tagMains'   => $tagMains,
            'tagSons'    => $tagSons,
        );
    }

    /**
     * @Route
     * (
     *      path="/data/front",
     *      name="fhm_api_media_data_front"
     * )
     * @Template("::FhmMedia/Template/data.front.html.twig")
     */
    public function dataFrontAction(Request $request)
    {
        $data       = $request->get('media');
        $instance   = $this->instanceData();
        $tagMains   = $this->dmRepository('FhmMediaBundle:MediaTag')->setParent(true)->setPrivate(false)->getAllEnable($instance->grouping->used);
        $tagSons    = (isset($data['tag'])) ? $this->dmRepository('FhmMediaBundle:MediaTag')->setPrivate(false)->getSonsEnable($data['tag'], $instance->grouping->used) : '';
        $tag        = (isset($data['tag'])) ? $this->dmRepository('FhmMediaBundle:MediaTag')->find($data['tag']) : '';
        $pagination = (isset($data['pagination'])) ? $data['pagination'] : 1;
        $search     = (isset($data['search'])) ? $data['search'] : '';
        $documents  = $this->dmRepository()->setTag((isset($data['tag'])) ? $data['tag'] : '')->setPrivate(false)->getFrontIndex($search, $pagination, $this->getParameter('front', 'fhm_media'), $instance->grouping->used, $instance->user->super);

        return array(
            'documents'  => $documents,
            'pagination' => $this->setSection('Front')->setPagination($this->getParameters('front', 'fhm_media'))->getPagination($pagination, count($documents), $this->dmRepository()->setTag((isset($data['tag'])) ? $data['tag'] : '')->setPrivate(false)->getFrontCount($search, $instance->grouping->used, $instance->user->super), 'pagination', array(), $this->generateUrl('fhm_api_media_data_front')),
            'instance'   => $instance,
            'tag'        => $tag,
            'tagMains'   => $tagMains,
            'tagSons'    => $tagSons,
        );
    }

    /**
     * @Route
     * (
     *      path="/data/selector",
     *      name="fhm_api_media_data_selector"
     * )
     * @Template("::FhmMedia/Template/data.selector.html.twig")
     */
    public function dataSelectorAction(Request $request)
    {
        $data       = $request->get('media');
        $selector   = $request->get('selector');
        $root       = $this->get($this->getParameters('service', 'fhm_media'))->tagRoot($selector['root']);
        $instance   = $this->instanceData();
        $tagMains   = $this->dmRepository('FhmMediaBundle:MediaTag')->setParent(true)->setRoot($root)->setPrivate($selector['private'])->getAllEnable($instance->grouping->filtered);
        $tagSons    = (isset($data['tag'])) ? $this->dmRepository('FhmMediaBundle:MediaTag')->setPrivate($selector['private'])->getSonsEnable($data['tag'], $instance->grouping->filtered) : '';
        $tag        = (isset($data['tag'])) ? $this->dmRepository('FhmMediaBundle:MediaTag')->find($data['tag']) : '';
        $pagination = (isset($data['pagination'])) ? $data['pagination'] : 1;
        $search     = (isset($data['search'])) ? $data['search'] : '';
        $documents  = $this->dmRepository()->setTag((isset($data['tag']) && $data['tag']) ? $data['tag'] : $root)->setFilter($selector['filter'])->setParent(true)->setPrivate($selector['private'])->getFrontIndex($search, $pagination, $this->getParameter('front', 'fhm_media'), $instance->grouping->filtered, $instance->user->super);

        return array(
            'selectorIndex' => isset($selector['index']) ? $selector['index'] : null,
            'selected'      => isset($selector['selected']) ? $selector['selected'] : null,
            'modalNew'      => isset($selector['new']) ? $selector['new'] : null,
            'root'          => $root,
            'documents'     => $documents,
            'pagination'    => $this->setPagination($this->getParameters('front', 'fhm_media'))->getPagination($pagination, count($documents), $this->dmRepository()->setTag((isset($data['tag']) && $data['tag']) ? $data['tag'] : $root)->setParent(true)->setPrivate($selector['private'])->getFrontCount($search, $instance->grouping->filtered, $instance->user->super), 'pagination', array(), $this->generateUrl('fhm_api_media_data_selector')),
            'instance'      => $instance,
            'tag'           => $tag,
            'tagMains'      => $tagMains,
            'tagSons'       => $tagSons,
        );
    }

    /**
     * @Route
     * (
     *      path="/data/editor",
     *      name="fhm_api_media_data_editor"
     * )
     * @Template("::FhmMedia/Template/data.editor.html.twig")
     */
    public function dataEditorAction(Request $request)
    {
        $data       = $request->get('media');
        $selector   = $request->get('selector');
        $root       = $this->get($this->getParameters('service', 'fhm_media'))->tagRoot($selector['root']);
        $instance   = $this->instanceData();
        $tagMains   = $this->dmRepository('FhmMediaBundle:MediaTag')->setParent(true)->setRoot($root)->setPrivate($selector['private'])->getAllEnable($instance->grouping->filtered);
        $tagSons    = (isset($data['tag'])) ? $this->dmRepository('FhmMediaBundle:MediaTag')->setPrivate($selector['private'])->getSonsEnable($data['tag'], $instance->grouping->filtered) : '';
        $tag        = (isset($data['tag'])) ? $this->dmRepository('FhmMediaBundle:MediaTag')->find($data['tag']) : '';
        $pagination = (isset($data['pagination'])) ? $data['pagination'] : 1;
        $search     = (isset($data['search'])) ? $data['search'] : '';
        $documents  = $this->dmRepository()->setTag((isset($data['tag']) && $data['tag']) ? $data['tag'] : $root)->setFilter($selector['filter'])->setParent(true)->setPrivate($selector['private'])->getFrontIndex($search, $pagination, $this->getParameters('front', 'fhm_media'), $instance->grouping->filtered, $instance->user->super);

        return array(
            'selector'   => $selector,
            'search'     => $search,
            'root'       => $root,
            'documents'  => $documents,
            'pagination' => $this->setPagination($this->getParameters('front', 'fhm_media'))->getPagination($pagination, count($documents), $this->dmRepository()->setTag((isset($data['tag']) && $data['tag']) ? $data['tag'] : $root)->setParent(true)->setPrivate($selector['private'])->getFrontCount($search, $instance->grouping->filtered, $instance->user->super), 'pagination', array(), $this->generateUrl('fhm_api_media_data_selector')),
            'instance'   => $instance,
            'tag'        => $tag,
            'tagMains'   => $tagMains,
            'tagSons'    => $tagSons,
        );
    }

    /**
     * @Route
     * (
     *      path="/data/editor/new",
     *      name="fhm_api_media_data_editor_new"
     * )
     * @Template("::FhmMedia/Template/data.editor.new.html.twig")
     */
    public function dataEditorNewAction(Request $request)
    {
        $selector     = $request->get('selector');
        $root         = $this->get($this->getParameters('service', 'fhm_media'))->tagRoot($selector['root']);
        $document     = $this->document;
        $instance     = $this->instanceData();
        $classType    = $this->form->type->create;
        $classHandler = $this->form->handler->create;
        $form         = $this->createForm(new $classType($instance, $document, $root), $document);
        $handler      = new $classHandler($form, $request);
        $process      = $handler->process();
        if($process)
        {
            $data = $request->get($form->getName());
            // Tag
            if(isset($data['tag']) && $data['tag'])
            {
                $tagRoot = $this->dmRepository('FhmMediaBundle:MediaTag')->getById($root);
                $tag     = $this->dmRepository('FhmMediaBundle:MediaTag')->getByName($data['tag']);
                if($tag == "")
                {
                    $tag = new \Fhm\MediaBundle\Document\MediaTag();
                    $tag->setName($data['tag']);
                    $tag->setActive(true);
                    if($tagRoot)
                    {
                        $tag->setParent($tagRoot);
                    }
                }
                if(isset($data['parent']) && $data['parent'])
                {
                    $tag->setParent($this->dmRepository('FhmMediaBundle:MediaTag')->find($data['parent']));
                }
                $this->dmPersist($tag);
                $document->addTag($tag);
            }
            if($root)
            {
                $document->addTag($this->dmRepository('FhmMediaBundle:MediaTag')->getById($root));
            }
            // File
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
            $this->get($this->getParameters('service', 'fhm_media'))->setDocument($document)->setWatermark($request->get('watermark'))->execute();
            // Response
            $response = new JsonResponse();

            return $response->setData(array('id' => $document->getId()));
        }

        return array(
            'selector'   => $selector,
            'form'       => $form->createView(),
            'instance'   => $instance,
            'watermarks' => $this->getParameters('watermark', 'fhm_media') ? $this->getParameters('files', 'fhm_media') : ''
        );
    }

    /**
     * @Route
     * (
     *      path="/data/preview",
     *      name="fhm_api_media_data_preview"
     * )
     * @Template("::FhmMedia/Template/data.preview.html.twig")
     */
    public function dataPreviewAction(Request $request)
    {
        $document = $this->dmRepository()->find($request->get('id'));
        $instance = $this->instanceData($document);

        return array(
            'document' => $document,
            'instance' => $instance
        );
    }

    /**
     * @Route
     * (
     *      path="/data/zoom",
     *      name="fhm_api_media_data_zoom"
     * )
     * @Template("::FhmMedia/Template/data.zoom.html.twig")
     */
    public function dataZoomAction(Request $request)
    {
        $document = $this->dmRepository()->find($request->get('id'));
        $instance = $this->instanceData($document);

        return array(
            'document' => $document,
            'instance' => $instance
        );
    }

    /**
     * @Route
     * (
     *      path="/data/new",
     *      name="fhm_api_media_data_new"
     * )
     * @Template("::FhmMedia/Template/data.new.html.twig")
     */
    public function dataNewAction(Request $request)
    {
        $selector     = $request->get('selector');
        $root         = $this->get($this->getParameters('service', 'fhm_media'))->tagRoot($selector['root']);
        $document     = $this->document;
        $instance     = $this->instanceData();
        $classType    = $this->form->type->create;
        $classHandler = $this->form->handler->create;
        $form         = $this->createForm(new $classType($instance, $document, $root), $document);
        $handler      = new $classHandler($form, $request);
        $process      = $handler->process();
        if($process)
        {
            $data = $request->get($form->getName());
            // Tag
            if(isset($data['tag']) && $data['tag'])
            {
                $tagRoot = $this->dmRepository('FhmMediaBundle:MediaTag')->getById($root);
                $tag     = $this->dmRepository('FhmMediaBundle:MediaTag')->getByName($data['tag']);
                if($tag == "")
                {
                    $tag = new \Fhm\MediaBundle\Document\MediaTag();
                    $tag->setName($data['tag']);
                    $tag->setActive(true);
                    if($tagRoot)
                    {
                        $tag->setParent($tagRoot);
                    }
                }
                if(isset($data['parent']) && $data['parent'])
                {
                    $tag->setParent($this->dmRepository('FhmMediaBundle:MediaTag')->find($data['parent']));
                }
                $this->dmPersist($tag);
                $document->addTag($tag);
            }
            if($root)
            {
                $document->addTag($this->dmRepository('FhmMediaBundle:MediaTag')->getById($root));
            }
            // File
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
            $this->get($this->getParameters('service', 'fhm_media'))->setDocument($document)->setWatermark($request->get('watermark'))->execute();
            // Response
            $response = new JsonResponse();

            return $response->setData(array('id' => $document->getId()));
        }

        return array(
            'selectorModal'  => isset($selector['selector']) ? $selector['selector'] : null,
            'selectorIndex'  => isset($selector['index']) ? $selector['index'] : null,
            'selectorTarget' => isset($selector['target']) ? $selector['target'] : null,
            'selectorFilter' => isset($selector['filter']) ? $selector['filter'] : null,
            'selectorRoot'   => isset($selector['root']) ? $selector['root'] : null,
            'form'           => $form->createView(),
            'instance'       => $instance,
            'watermarks'     => $this->getParameters('watermark', 'fhm_media') ? $this->getParameters('files', 'fhm_media') : ''
        );
    }
}