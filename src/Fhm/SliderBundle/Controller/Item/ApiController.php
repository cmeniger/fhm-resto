<?php
namespace Fhm\SliderBundle\Controller\Item;

use Fhm\FhmBundle\Controller\RefApiController as FhmController;
use Fhm\SliderBundle\Document\Slider;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * @Route("/api/slider/item", service="fhm_slider_controller_item_api")
 */
class ApiController extends FhmController
{
    /**
     * ApiController constructor.
     *
     * @param \Fhm\FhmBundle\Services\Tools $tools
     */
    public function __construct(\Fhm\FhmBundle\Services\Tools $tools)
    {
        $this->setFhmTools($tools);
        parent::__construct('Fhm', 'Slider', 'slider_item', 'SliderItem');
        $this->translation = array('FhmSliderBundle', 'slider.item');
    }

    /**
     * @Route
     * (
     *      path="/",
     *      name="fhm_api_slider_item"
     * )
     * @Template("::FhmSlider/Api/Item/index.html.twig")
     */
    public function indexAction()
    {
        return parent::indexAction();
    }

    /**
     * @Route
     * (
     *      path="/autocomplete/",
     *      name="fhm_api_slider_item_autocomplete"
     * )
     * @Template("::FhmSlider/Api/Item/autocomplete.html.twig")
     */
    public function autocompleteAction(Request $request)
    {
        return parent::autocompleteAction($request);
    }
}