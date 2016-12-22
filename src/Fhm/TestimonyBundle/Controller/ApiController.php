<?php
namespace Fhm\TestimonyBundle\Controller;

use Fhm\FhmBundle\Controller\RefApiController as FhmController;
use Fhm\FhmBundle\Form\Type\Admin\SearchType;
use Fhm\TestimonyBundle\Document\Testimony;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/api/testimony")
 * ---------------------------------------
 * Class ApiController
 * @package Fhm\TestimonyBundle\Controller
 */
class ApiController extends FhmController
{
    /**
     * ApiController constructor.
     */
    public function __construct()
    {
        self::$repository = "FhmTestimonyBundle:Testimony";
        self::$source = "fhm";
        self::$domain = "FhmTestimonyBundle";
        self::$translation = "testimony";
        self::$class = Testimony::class;
        self::$route = "testimony";
    }

    /**
     * @Route
     * (
     *      path="/",
     *      name="fhm_api_testimony"
     * )
     * @Template("::FhmTestimony/Api/index.html.twig")
     */
    public function indexAction()
    {
        return parent::indexAction();
    }

    /**
     * @Route
     * (
     *      path="/detail/{template}/{rows}/{pagination}",
     *      name="fhm_api_testimony_detail",
     *      requirements={"rows"="\d*", "pagination"="[0-1]"},
     *      defaults={"rows"=null, "pagination"=1}
     * )
     */
    public function detailAction($template, $rows, $pagination)
    {
        $document = "";
        $form = $this->createForm(SearchType::class);
        $form->setData($this->get('request_stack')->get($form->getName()));
        $dataSearch = $form->getData();
        $documents = $this->get('fhm_tools')->dmRepository(self::$repository)->getFrontIndex(
            $dataSearch['search']
        );
        return new Response(
            $this->renderView(
                "::FhmTestimony/Template/".$template.".html.twig",
                array(
                    'document' => $document,
                    'documents' => $documents,
                    'form' => $form ? $form->createView() : $form,
                )
            )
        );
    }
}