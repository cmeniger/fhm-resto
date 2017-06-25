<?php
namespace Fhm\MediaBundle\Controller;

use Fhm\FhmBundle\Controller\RefFrontController as FhmController;
use Fhm\FhmBundle\Form\Handler\Front\CreateHandler;
use Fhm\MediaBundle\Document\Media;
use Fhm\MediaBundle\Form\Type\Admin\Tag\UpdateType;
use Fhm\MediaBundle\Form\Type\Front\CreateType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * @Route("/media")
 * -----------------------------------
 * Class FrontController
 * @package Fhm\MediaBundle\Controller
 */
class FrontController extends FhmController
{
    /**
     * FrontController constructor.
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
    public function createAction(Request $request)
    {
        $object = new self::$class;
        $form = $this->createForm(self::$form->createType, $object);
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
                $object->addTag($tag);
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
            $object->setName($name);
            $object->setFile($file);
            $object->setUserCreate($this->getUser());
            $object->setAlias($this->fhm_tools->getAlias($object->getId(), $object->getName()));
            $object->setWatermark((array)$request->get('watermark'));
            $object->setActive(true);
            $this->get('fhm_tools')->dmPersist($object);
            $this->get($this->get('fhm_tools')->getParameters('service', 'fhm_media'))->setModel(
                $object
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
                    'link' => $this->getUrl('fhm_'.$this->route),
                    'text' => $this->trans('.front.index.breadcrumb'),
                ),
                array(
                    'link' => $this->getUrl('fhm_'.$this->route.'_create'),
                    'text' => $this->trans('.front.create.breadcrumb'),
                    'current' => true,
                ),
            ),
        );
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