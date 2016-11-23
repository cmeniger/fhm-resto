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
 * @Route("/api/slider", service="fhm_slider_controller_api")
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
        parent::__construct('Fhm', 'Slider', 'slider');
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
        $document = $this->fhm_tools->dmRepository()->getById($id);
        $document = ($document) ? $document : $this->fhm_tools->dmRepository()->getByAlias($id);
        $document = ($document) ? $document : $this->fhm_tools->dmRepository()->getByName($id);
        $instance = $this->fhm_tools->instanceData($document);
        // ERROR - unknown
        if($document == "")
        {
            throw $this->createNotFoundException($this->fhm_tools->trans('slider.item.error.unknown', array(), 'FhmSliderBundle'));
        }
        // ERROR - Forbidden
        elseif(!$instance->user->admin && ($document->getDelete() || !$document->getActive()))
        {
            throw new HttpException(403, $this->fhm_tools->trans('slider.item.error.forbidden', array(), 'FhmSliderBundle'));
        }

        return new Response(
            $this->renderView(
                "::FhmSlider/Template/" . $template . ".html.twig",
                array(
                    'document' => $document,
                    'items'    => $this->fhm_tools->dmRepository("FhmSliderBundle:SliderItem")->getByGroupAll($document),
                    'instance' => $instance
                )
            )
        );
    }
}

