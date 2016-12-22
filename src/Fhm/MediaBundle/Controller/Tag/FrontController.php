<?php
namespace Fhm\MediaBundle\Controller\Tag;

use Fhm\FhmBundle\Controller\RefFrontController as FhmController;
use Fhm\MediaBundle\Document\Media;
use Fhm\MediaBundle\Document\MediaTag;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * @Route("/mediatag")
 * ---------------------------------------
 * Class FrontController
 * @package Fhm\MediaBundle\Controller\Tag
 */
class FrontController extends FhmController
{
    /**
     * FrontController constructor.
     */
    public function __construct()
    {
        self::$repository = "FhmMediaBundle:MediaTag";
        self::$source = "fhm";
        self::$domain = "FhmMediaBundle";
        self::$translation = "media.tag";
        self::$class = MediaTag::class;
        self::$route = "media_tag";
    }

    /**
     * @Route
     * (
     *      path="/",
     *      name="fhm_media_tag"
     * )
     * @Template("::FhmMedia/Front/Tag/index.html.twig")
     */
    public function indexAction()
    {
        return parent::indexAction();
    }

    /**
    * @Route
    * (
    *      path="/detail/{id}",
    *      name="fhm_media_tag_detail",
    *      requirements={"id"=".+"}
    * )
    * @Template("::FhmMedia/Front/Tag/detail.html.twig")
    */
    public function detailAction($id)
    {
        return parent::detailAction($id);
    }

    /**
    * @Route
    * (
    *      path="/{id}",
    *      name="fhm_media_tag_lite",
    *      requirements={"id"=".+"}
    * )
    * @Template("::FhmMedia/Front/Tag/detail.html.twig")
    */
    public function liteAction($id)
    {
        return $this->detailAction($id);
    }
}