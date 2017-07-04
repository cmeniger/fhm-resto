<?php

namespace Fhm\NewsBundle\Controller;

use Fhm\FhmBundle\Controller\RefApiController as FhmController;
use Fhm\FhmBundle\Form\Type\Admin\SearchType;
use Fhm\NewsBundle\Document\News;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * @Route("/api/news")
 * -----------------------------------
 * Class ApiController
 *
 * @package Fhm\NewsBundle\Controller
 */
class ApiController extends FhmController
{
    /**
     * AdminController constructor.
     */
    public function __construct()
    {
        self::$repository  = "FhmNewsBundle:News";
        self::$source      = "fhm";
        self::$domain      = "FhmNewsBundle";
        self::$translation = "news";
        self::$class       = News::class;
        self::$route       = "news";
    }

    /**
     * @Route
     * (
     *      path="/",
     *      name="fhm_api_news"
     * )
     * @Template("::FhmNews/Api/index.html.twig")
     */
    public function indexAction()
    {
        return parent::indexAction();
    }

    /**
     * @Route
     * (
     *      path="/autocomplete/",
     *      name="fhm_api_news_autocomplete"
     * )
     * @Template("::FhmNews/Api/autocomplete.html.twig")
     */
    public function autocompleteAction(Request $request)
    {
        return parent::autocompleteAction($request);
    }

    /**
     * @Route
     * (
     *      path="/detail/{template}/{rows}/{pagination}/{id}",
     *      name="fhm_api_news_detail",
     *      requirements={"id"=".+", "rows"="\d*", "pagination"="[0-1]"},
     *      defaults={"id"=null, "rows"=null, "pagination"=1}
     * )
     * @Template("::FhmNews/Api/detail.html.twig")
     */
    public function detailAction($template, $id, $rows, $pagination)
    {
        if($id == 'demo')
        {
            return new Response(
                $this->renderView(
                    "::FhmNews/Template/" . $template . ".html.twig",
                    array(
                        'object'  => null,
                        'objects' => null,
                        'demo'    => true
                    )
                )
            );
        }
        $object = "";
        // News
        if($id && $template == 'full')
        {
            $object  = $this->get('fhm_tools')->dmRepository(self::$repository)->getById($id);
            $object  = ($object) ? $object : $this->get('fhm_tools')->dmRepository(self::$repository)->getByAlias($id);
            $object  = ($object) ? $object : $this->get('fhm_tools')->dmRepository(self::$repository)->getByName($id);
            $objects = '';
            $form    = '';
            // ERROR - unknown
            if($object == "")
            {
                throw $this->createNotFoundException(
                    $this->trans('news.group.error.unknown', array(), 'FhmNewsBundle')
                );
            } // ERROR - Forbidden
            elseif(!$this->get('security.authorization_checker')->isGranted('ROLE_ADMIN') && ($object->getDelete() || !$object->getActive())
            )
            {
                throw new HttpException(
                    403, $this->trans('news.group.error.forbidden', array(), 'FhmNewsBundle')
                );
            }
        }
        else
        {
            // Group
            if($id)
            {
                $object = $this->get('fhm_tools')->dmRepository("FhmNewsBundle:NewsGroup")->getById($id);
                $object = ($object) ? $object : $this->get('fhm_tools')->dmRepository("FhmNewsBundle:NewsGroup")->getByAlias($id);
                $object = ($object) ? $object : $this->get('fhm_tools')->dmRepository("FhmNewsBundle:NewsGroup")->getByName($id);
                // ERROR - unknown
                if($object == "")
                {
                    throw $this->createNotFoundException(
                        $this->trans('news.group.error.unknown', array(), 'FhmNewsBundle')
                    );
                }
                // ERROR - Forbidden
                elseif(!$this->get('security.authorization_checker')->isGranted('ROLE_SUPER_ADMIN') && ($object->getDelete() || !$object->getActive()))
                {
                    throw new HttpException(
                        403, $this->trans('news.group.error.forbidden', array(), 'FhmNewsBundle')
                    );
                }
            }
            // News
            $form = $this->createForm(SearchType::class);
            $form->setData($this->get('request_stack')->getCurrentRequest()->get($form->getName()));
            $dataSearch = $form->getData();
            $objects    = $this->get('fhm_tools')->dmRepository(self::$repository)->getNewsByGroupIndex($object, $dataSearch['search'], 1, $rows);
        }

        return new Response(
            $this->renderView(
                "::FhmNews/Template/" . $template . ".html.twig",
                array(
                    'object'  => $object,
                    'objects' => $objects,
                    'form'    => $form ? $form->createView() : $form,
                )
            )
        );
    }
}