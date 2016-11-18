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
 */
class ApiController extends FhmController
{
    /**
     * Constructor
     */
    public function __construct()
    {
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
        $document = $this->dmRepository()->getById($id);
        $document = ($document) ? $document : $this->dmRepository()->getByAlias($id);
        $document = ($document) ? $document : $this->dmRepository()->getByName($id);
        $instance = $this->instanceData($document);
        // ERROR - unknown
        if($document == "")
        {
            throw $this->createNotFoundException($this->get('translator')->trans('slider.item.error.unknown', array(), 'FhmSliderBundle'));
        }
        // ERROR - Forbidden
        elseif(!$instance->user->admin && ($document->getDelete() || !$document->getActive()))
        {
            throw new HttpException(403, $this->get('translator')->trans('slider.item.error.forbidden', array(), 'FhmSliderBundle'));
        }

        return new Response(
            $this->renderView(
                "::FhmSlider/Template/" . $template . ".html.twig",
                array(
                    'document' => $document,
                    'items'    => $this->dmRepository("FhmSliderBundle:SliderItem")->getByGroupAll($document),
                    'instance' => $instance
                )
            )
        );
    }
}

