<?php
namespace Fhm\SliderBundle\Controller;

use Fhm\FhmBundle\Controller\RefApiController as FhmController;
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
        $object = $this->get('fhm_tools')->dmRepository(self::$repository)->getById($id);
        $object = ($object) ?: $this->get('fhm_tools')->dmRepository(self::$repository)->getByAlias($id);
        $object = ($object) ?: $this->get('fhm_tools')->dmRepository(self::$repository)->getByName($id);
        // ERROR - unknown
        if ($object == "") {
            throw $this->createNotFoundException(
                $this->trans('slider.item.error.unknown', array(), 'FhmSliderBundle')
            );
        } // ERROR - Forbidden
        elseif (!$this->getUser()->hasRole('ROLE_SUPER_ADMIN') && ($object->getDelete() || !$object->getActive())) {
            throw new HttpException(
                403, $this->trans('slider.item.error.forbidden', array(), 'FhmSliderBundle')
            );
        }

        return new Response(
            $this->renderView(
                "::FhmSlider/Template/".$template.".html.twig",
                array(
                    'document' => $object,
                    'items' => $this->get('fhm_tools')->dmRepository("FhmSliderBundle:SliderItem")->getByGroupAll(
                        $object
                    ),
                )
            )
        );
    }
}

