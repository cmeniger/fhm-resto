<?php
namespace Fhm\SliderBundle\Controller;

use Fhm\FhmBundle\Controller\RefApiController as FhmController;
use Fhm\SliderBundle\Document\Slider;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * @Route("/api/slider")
 * -------------------------------------
 * Class ApiController
 * @package Fhm\SliderBundle\Controller
 */
class ApiController extends FhmController
{
    /**
     * FrontController constructor.
     */
    public function __construct()
    {
        self::$repository = "FhmSliderBundle:Slider";
        self::$source = "fhm";
        self::$domain = "FhmSliderBundle";
        self::$translation = "slider";
        self::$class = Slider::class;
        self::$route = "slider";
    }

    /**
     * @Route
     * (
     *      path="/",
     *      name="fhm_api_slider"
     * )
     * @Template("::FhmSlider/Api/index.html.twig")
     */
    public function indexAction()
    {
        return parent::indexAction();
    }

    /**
     * @Route
     * (
     *      path="/autocomplete/",
     *      name="fhm_api_slider_autocomplete"
     * )
     * @Template("::FhmSlider/Api/autocomplete.html.twig")
     */
    public function autocompleteAction(Request $request)
    {
        return parent::autocompleteAction($request);
    }

    /**
     * @Route
     * (
     *      path="/detail/{template}/{id}",
     *      name="fhm_api_slider_detail",
     *      requirements={"id"=".+"},
     *      defaults={"template"="slider1"}
     * )
     */
    public function detailAction($template, $id)
    {
        $document = $this->get('fhm_tools')->dmRepository(self::$repository)->getById($id);
        $document = ($document) ? $document : $this->get('fhm_tools')->dmRepository(self::$repository)->getByAlias($id);
        $document = ($document) ? $document : $this->get('fhm_tools')->dmRepository(self::$repository)->getByName($id);
        // ERROR - unknown
        if ($document == "") {
            throw $this->createNotFoundException(
                $this->trans('slider.item.error.unknown', array(), 'FhmSliderBundle')
            );
        } // ERROR - Forbidden
        elseif (!$this->getUser()->hasRole('ROLE_SUPER_ADMIN') && ($document->getDelete() || !$document->getActive())) {
            throw new HttpException(
                403, $this->trans('slider.item.error.forbidden', array(), 'FhmSliderBundle')
            );
        }

        return new Response(
            $this->renderView(
                "::FhmSlider/Template/".$template.".html.twig",
                array(
                    'document' => $document,
                    'items' => $this->get('fhm_tools')->dmRepository("FhmSliderBundle:SliderItem")->getByGroupAll(
                        $document
                    ),
                )
            )
        );
    }
}

