<?php

namespace Fhm\TestimonyBundle\Controller;

use Fhm\FhmBundle\Controller\RefApiController as FhmController;
use Fhm\TestimonyBundle\Document\Testimony;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * @Route("/api/testimony")
 * ---------------------------------------
 * Class ApiController
 *
 * @package Fhm\TestimonyBundle\Controller
 */
class ApiController extends FhmController
{
    /**
     * ApiController constructor.
     */
    public function __construct()
    {
        self::$repository  = "FhmTestimonyBundle:Testimony";
        self::$source      = "fhm";
        self::$domain      = "FhmTestimonyBundle";
        self::$translation = "testimony";
        self::$class       = Testimony::class;
        self::$route       = "testimony";
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
     *      path="/detail/{template}/{id}",
     *      name="fhm_api_testimony_detail",
     *      requirements={"id"=".+"},
     *      defaults={"template"="default"}
     * )
     */
    public function detailAction($template, $id)
    {
        if($id == 'demo')
        {
            return new Response(
                $this->renderView(
                    "::FhmTestimony/Template/" . $template . ".html.twig",
                    array(
                        'object'  => null,
                        'objects' => null,
                        'demo'    => true
                    )
                )
            );
        }
        if($id != '')
        {
            $object = $this->get('fhm_tools')->dmRepository(self::$repository)->getById($id);
            $object = ($object) ?: $this->get('fhm_tools')->dmRepository(self::$repository)->getByAlias($id);
            $object = ($object) ?: $this->get('fhm_tools')->dmRepository(self::$repository)->getByName($id);
            // ERROR - unknown
            if($object == "")
            {
                throw $this->createNotFoundException(
                    $this->trans(self::$translation . '.error.unknown', array(), self::$domain)
                );
            }
            // ERROR - Forbidden
            elseif($object->getDelete() || !$object->getActive())
            {
                throw new HttpException(
                    403, $this->trans(self::$translation . '.error.forbidden', array(), self::$domain)
                );
            }
        }
        else{
            $object = null;
        }

        return new Response(
            $this->renderView(
                "::FhmTestimony/Template/" . $template . ".html.twig",
                array(
                    'object'  => $object,
                    'objects' => $this->get('fhm_tools')->dmRepository(self::$repository)->getAllEnable(),
                )
            )
        );
    }
}