<?php
namespace Fhm\NewsBundle\Controller\Group;

use Fhm\FhmBundle\Controller\RefFrontController as FhmController;
use Fhm\FhmBundle\Form\Type\Admin\SearchType;
use Fhm\NewsBundle\Form\Type\Front\Group\CreateType;
use Fhm\NewsBundle\Form\Type\Front\Group\UpdateType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * @Route("/newsgroup")
 * ----------------------------------------
 * Class FrontController
 * @package Fhm\NewsBundle\Controller\Group
 */
class FrontController extends FhmController
{
    /**
     * FrontController constructor.
     */
    public function __construct()
    {
        self::$repository = "FhmNewsBundle:NewsGroup";
        self::$source = "fhm";
        self::$domain = "FhmNewsBundle";
        self::$translation = "News.group";
        self::$route = "news_group";
    }

    /**
     * @Route
     * (
     *      path="/",
     *      name="fhm_news_group"
     * )
     * @Template("::FhmNews/Front/Group/index.html.twig")
     */
    public function indexAction()
    {
        $response = parent::indexAction();
        foreach ($response['documents'] as $key => $document) {
            $response['documents'][$key]->allnews = $this->get('fhm_tools')->dmRepository(
                "FhmNewsBundle:News"
            )->getNewsByGroupAll($document);
        }

        return $response;
    }

    /**
     * @Route
     * (
     *      path="/detail/{id}",
     *      name="fhm_news_group_detail",
     *      requirements={"id"=".+"}
     * )
     * @Template("::FhmNews/Front/Group/detail.html.twig")
     */
    public function detailAction($id)
    {
        $response = parent::detailAction($id);
        $document = $response['document'];
        $form = $this->createForm(SearchType::class);
        $form->setData($this->get('request_stack')->getCurrentRequest()->get($form->getName()));
        $dataSearch = $form->getData();
        $documents = $this->get('fhm_tools')->dmRepository("FhmNewsBundle:News")->getNewsByGroupIndex(
            $document,
            $dataSearch['search']
        );

        return array_merge(
            $response,
            array(
                'documents' => $documents,
                'form' => $form->createView(),
            )
        );
    }

    /**
     * @Route
     * (
     *      path="/{id}",
     *      name="fhm_news_group_lite",
     *      requirements={"id"=".+"}
     * )
     * @Template("::FhmNews/Front/Group/detail.html.twig")
     */
    public function liteAction($id)
    {
        return $this->detailAction($id);
    }
}