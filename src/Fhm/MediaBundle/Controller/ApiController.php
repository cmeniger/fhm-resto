<?php
namespace Fhm\MediaBundle\Controller;

use Fhm\FhmBundle\Controller\RefApiController as FhmController;
use Fhm\FhmBundle\Form\Handler\Admin\UpdateHandler;
use Fhm\MediaBundle\Form\Handler\Api\CreateHandler;
use Fhm\MediaBundle\Form\Type\Admin\Tag\UpdateType;
use Fhm\MediaBundle\Form\Type\Api\CreateType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * @Route("/api/media")
 * -----------------------------------
 * Class ApiController
 * @package Fhm\MediaBundle\Controller
 */
class ApiController extends FhmController
{
    /**
     * ApiController constructor.
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
        $counter = $request->get('counter');

        $response = new JsonResponse();
        $data = array();
        // Message
        $this->get('session')->getFlashBag()->add(
            'notice',
            $this->trans(
                self::$translation.'.admin.uploaded.flash.ok',
                array('%accepted%' => $counter['accepted'], '%rejected%' => $counter['rejected'])
            )
        );

        return $response->setData($data);
    }

    /**
     * @Route
     * (
     *      path="/data/admin/{page}",
     *      requirements={"page": "\d+"},
     *      name="fhm_api_media_data_admin"
     * )
     */
    public function dataAdminAction(Request $request, $page = 1)
    {
        $data = $request->get('media');
        $tagMains = $this->get('fhm_tools')->dmRepository('FhmMediaBundle:MediaTag')->setParent(true)->getAllFiltered();
        $tagSons = (isset($data['tag'])) ? $this->get('fhm_tools')->dmRepository('FhmMediaBundle:MediaTag')->getSons(
            $data['tag']
        ) : '';
        $tag = (isset($data['tag'])) ? $this->get('fhm_tools')->dmRepository('FhmMediaBundle:MediaTag')->find(
            $data['tag']
        ) : '';
        $search = (isset($data['search'])) ? $data['search'] : '';
        $query = $this->get('fhm_tools')->dmRepository(self::$repository)->setTag(
            (isset($data['tag'])) ? $data['tag'] : ''
        )->getAdminIndex(
            $search,
            $this->getUser()->hasRole('ROLE_SUPER_ADMIN')
        );

        $pagination = $this->get('knp_paginator')->paginate(
            $query,
            $request->query->getInt('page', $page),
            $this->getParameters('pagination', 'fhm_fhm')
        );
        return $this->render(
            "::FhmMedia/Template/data.admin.html.twig",
            array(
                'pagination' => $pagination,
                'tag' => $tag,
                'tagMains' => $tagMains,
                'tagSons' => $tagSons,
            )
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
        $data = $request->get('media');
        $tagMains = $this->get('fhm_tools')->dmRepository('FhmMediaBundle:MediaTag')->setParent(true)->setPrivate(
            false
        )->getAllEnable();
        $tagSons = (isset($data['tag'])) ? $this->get('fhm_tools')->dmRepository('FhmMediaBundle:MediaTag')->setPrivate(
            false
        )->getSonsEnable($data['tag']) : '';
        $tag = (isset($data['tag'])) ? $this->get('fhm_tools')->dmRepository('FhmMediaBundle:MediaTag')->find(
            $data['tag']
        ) : '';
        $search = (isset($data['search'])) ? $data['search'] : '';
        $objects = $this->get('fhm_tools')->dmRepository(self::$repository)->setTag(
            (isset($data['tag'])) ? $data['tag'] : ''
        )->setPrivate(
            false
        )->getFrontIndex(
            $search
        );

        return array(
            'objects' => $objects,
            'tag' => $tag,
            'tagMains' => $tagMains,
            'tagSons' => $tagSons,
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
        $data = $request->get('media');
        $selector = $request->get('selector');
        $root = $this->get($this->get('fhm_tools')->getParameter('service', 'fhm_media'))->tagRoot($selector['root']);
        $tagMains = $this->get('fhm_tools')->dmRepository('FhmMediaBundle:MediaTag')->setParent(true)->setRoot(
            $root
        )->setPrivate($selector['private'])->getAllEnable();
        $tagSons = (isset($data['tag'])) ? $this->get('fhm_tools')->dmRepository('FhmMediaBundle:MediaTag')->setPrivate(
            $selector['private']
        )->getSonsEnable($data['tag']) : '';
        $tag = (isset($data['tag'])) ? $this->get('fhm_tools')->dmRepository('FhmMediaBundle:MediaTag')->find(
            $data['tag']
        ) : '';
        $search = (isset($data['search'])) ? $data['search'] : '';
        $objects = $this->get('fhm_tools')->dmRepository(self::$repository)->setTag(
            (isset($data['tag']) && $data['tag']) ? $data['tag'] : $root
        )->setFilter($selector['filter'])->setParent(true)->setPrivate($selector['private'])->getFrontIndex(
            $search
        );

        return array(
            'selectorIndex' => isset($selector['index']) ? $selector['index'] : null,
            'selected' => isset($selector['selected']) ? $selector['selected'] : null,
            'modalNew' => isset($selector['new']) ? $selector['new'] : null,
            'root' => $root,
            'objects' => $objects,
            'tag' => $tag,
            'tagMains' => $tagMains,
            'tagSons' => $tagSons,
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
        $data = $request->get('media');
        $selector = $request->get('selector');
        $root = $this->get($this->get('fhm_tools')->getParameters('service', 'fhm_media'))->tagRoot($selector['root']);
        $tagMains = $this->get('fhm_tools')->dmRepository('FhmMediaBundle:MediaTag')->setParent(true)->setRoot(
            $root
        )->setPrivate($selector['private'])->getAllEnable();
        $tagSons = (isset($data['tag'])) ? $this->get('fhm_tools')->dmRepository('FhmMediaBundle:MediaTag')->setPrivate(
            $selector['private']
        )->getSonsEnable($data['tag']) : '';
        $tag = (isset($data['tag'])) ? $this->get('fhm_tools')->dmRepository('FhmMediaBundle:MediaTag')->find(
            $data['tag']
        ) : '';
        $search = (isset($data['search'])) ? $data['search'] : '';
        $objects = $this->get('fhm_tools')->dmRepository(self::$repository)->setTag(
            (isset($data['tag']) && $data['tag']) ? $data['tag'] : $root
        )->setFilter($selector['filter'])->setParent(true)->setPrivate($selector['private'])->getFrontIndex(
            $search
        );

        return array(
            'selector' => $selector,
            'search' => $search,
            'root' => $root,
            'objects' => $objects,
            'tag' => $tag,
            'tagMains' => $tagMains,
            'tagSons' => $tagSons,
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
        $selector = $request->get('selector');
        $root = $this->get($this->get('fhm_tools')->getParameters('service', 'fhm_media'))->tagRoot($selector['root']);
        $object = new self::$class;
        $form = $this->createForm(self::$form->createType, $object);
        $handler = new self::$form->createHandler($form, $request);
        $process = $handler->process();
        if ($process) {
            $data = $request->get($form->getName());
            // Tag
            if (isset($data['tag']) && $data['tag']) {
                $tagRoot = $this->get('fhm_tools')->dmRepository('FhmMediaBundle:MediaTag')->getById($root);
                $tag = $this->get('fhm_tools')->dmRepository('FhmMediaBundle:MediaTag')->getByName($data['tag']);
                if ($tag == "") {
                    $tagClassName = $this->get('fhm.object.manager')->getCurrentModelName('FhmMediaBundle:MediaTag');
                    $tag = new $tagClassName;
                    $tag->setName($data['tag']);
                    $tag->setActive(true);
                    if ($tagRoot) {
                        $tag->setParent($tagRoot);
                    }
                }
                if (isset($data['parent']) && $data['parent']) {
                    $tag->setParent(
                        $this->get('fhm_tools')->dmRepository('FhmMediaBundle:MediaTag')->find($data['parent'])
                    );
                }
                $this->get('fhm_tools')->dmPersist($tag);
                $object->addTag($tag);
            }
            if ($root) {
                $object->addTag($this->get('fhm_tools')->dmRepository('FhmMediaBundle:MediaTag')->getById($root));
            }
            // File
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
            $object->setName($name);
            $object->setFile($file);
            $object->setUserCreate($this->getUser());
            $object->setAlias($this->get('fhm_tools')->getAlias($object->getId(), $object->getName()));
            $object->setWatermark((array)$request->get('watermark'));
            $object->setActive(true);
            $this->get('fhm_tools')->dmPersist($object);
            $this->get($this->get('fhm_tools')->getParameters('service', 'fhm_media'))->setobject(
                $object
            )->setWatermark(
                $request->get('watermark')
            )->execute();
            // Response
            $response = new JsonResponse();

            return $response->setData(array('id' => $object->getId()));
        }

        return array(
            'selector' => $selector,
            'form' => $form->createView(),
            'watermarks' => $this->get('fhm_tools')->getParameters('watermark', 'fhm_media') ? $this->get(
                'fhm_tools'
            )->getParameters(
                'files',
                'fhm_media'
            ) : '',
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
        return ['object' => $this->get('fhm_tools')->dmRepository(self::$repository)->find($request->get('id'))];
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
        return ['object' => $this->get('fhm_tools')->dmRepository(self::$repository)->find($request->get('id'))];
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
        $selector = $request->get('selector');
        $root = $this->get($this->get('fhm_tools')->getParameters('service', 'fhm_media'))->tagRoot($selector['root']);
        $object = new self::$class;
        $form = $this->createForm(self::$form->createType, $object);
        $handler = new self::$form->createHandler($form, $request);
        $process = $handler->process();
        if ($process) {
            $data = $request->get($form->getName());
            // Tag
            if (isset($data['tag']) && $data['tag']) {
                $tagRoot = $this->get('fhm_tools')->dmRepository('FhmMediaBundle:MediaTag')->getById($root);
                $tag = $this->get('fhm_tools')->dmRepository('FhmMediaBundle:MediaTag')->getByName($data['tag']);
                if ($tag == "") {
                    $tagClassName = $this->get('fhm.object.manager')->getCurrentModelName('FhmMediaBundle:MediaTag');
                    $tag = new $tagClassName;
                    $tag->setName($data['tag']);
                    $tag->setActive(true);
                    if ($tagRoot) {
                        $tag->setParent($tagRoot);
                    }
                }
                if (isset($data['parent']) && $data['parent']) {
                    $tag->setParent(
                        $this->get('fhm_tools')->dmRepository('FhmMediaBundle:MediaTag')->find($data['parent'])
                    );
                }
                $this->get('fhm_tools')->dmPersist($tag);
                $object->addTag($tag);
            }
            if ($root) {
                $object->addTag($this->get('fhm_tools')->dmRepository('FhmMediaBundle:MediaTag')->getById($root));
            }
            // File
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
            $object->setName($name);
            $object->setFile($file);
            $object->setUserCreate($this->getUser());
            $object->setAlias($this->get('fhm_tools')->getAlias($object->getId(), $object->getName()));
            $object->setWatermark((array)$request->get('watermark'));
            $object->setActive(true);
            $this->get('fhm_tools')->dmPersist($object);
            $this->get($this->get('fhm_tools')->getParameters('service', 'fhm_media'))->setobject(
                $object
            )->setWatermark(
                $request->get('watermark')
            )->execute();
            // Response
            $response = new JsonResponse();

            return $response->setData(array('id' => $object->getId()));
        }

        return array(
            'selectorModal' => isset($selector['selector']) ? $selector['selector'] : null,
            'selectorIndex' => isset($selector['index']) ? $selector['index'] : null,
            'selectorTarget' => isset($selector['target']) ? $selector['target'] : null,
            'selectorFilter' => isset($selector['filter']) ? $selector['filter'] : null,
            'selectorRoot' => isset($selector['root']) ? $selector['root'] : null,
            'form' => $form->createView(),
            'watermarks' => $this->get('fhm_tools')->getParameters('watermark', 'fhm_media') ? $this->get(
                'fhm_tools'
            )->getParameters(
                'files',
                'fhm_media'
            ) : '',
        );
    }
}