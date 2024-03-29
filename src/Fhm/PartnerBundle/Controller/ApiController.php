<?php

namespace Fhm\PartnerBundle\Controller;

use Fhm\FhmBundle\Controller\RefApiController as FhmController;
use Fhm\FhmBundle\Form\Type\Admin\SearchType;
use Fhm\PartnerBundle\Document\Partner;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * @Route("/api/partner")
 * -------------------------------------
 * Class ApiController
 *
 * @package Fhm\PartnerBundle\Controller
 */
class ApiController extends FhmController
{
    /**
     * AdminController constructor.
     */
    public function __construct()
    {
        self::$repository  = "FhmPartnerBundle:Partner";
        self::$source      = "fhm";
        self::$domain      = "FhmPartnerBundle";
        self::$translation = "partner";
        self::$class       = Partner::class;
        self::$route       = "partner";
    }

    /**
     * @Route
     * (
     *      path="/",
     *      name="fhm_api_partner"
     * )
     * @Template("::FhmPartner/Api/index.html.twig")
     */
    public function indexAction()
    {
        return parent::indexAction();
    }

    /**
     * @Route
     * (
     *      path="/autocomplete/",
     *      name="fhm_api_partner_autocomplete"
     * )
     * @Template("::FhmPartner/Api/autocomplete.html.twig")
     */
    public function autocompleteAction(Request $request)
    {
        return parent::autocompleteAction($request);
    }

    /**
     * @Route
     * (
     *      path="/detail/{template}/{rows}/{pagination}/{id}",
     *      name="fhm_api_partner_detail",
     *      requirements={"id"=".+", "rows"="\d*", "pagination"="[0-1]"},
     *      defaults={"id"=null, "rows"=null, "pagination"=1}
     * )
     * @Template("::FhmPartner/Api/detail.html.twig")
     */
    public function detailAction($template, $id, $rows, $pagination)
    {
        if($id == 'demo')
        {
            return new Response(
                $this->renderView(
                    "::FhmPartner/Template/" . $template . ".html.twig",
                    array(
                        'object'  => null,
                        'objects' => null,
                        'demo'    => true
                    )
                )
            );
        }
        $object = "";
        // Partner
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
                    $this->trans('partner.group.error.unknown', array(), 'FhmPartnerBundle')
                );
            }
            // ERROR - Forbidden
            elseif(!$this->getUser()->hasRole('ROLE_ADMIN') && ($object->getDelete() || !$object->getActive()))
            {
                throw new HttpException(
                    403, $this->trans('partner.group.error.forbidden', array(), 'FhmPartnerBundle')
                );
            }
        }
        else
        {
            // Group
            if($id)
            {
                $object = $this->get('fhm_tools')->dmRepository("FhmPartnerBundle:PartnerGroup")->getById($id);
                $object = ($object) ? $object : $this->get('fhm_tools')->dmRepository()->getByAlias($id);
                $object = ($object) ? $object : $this->get('fhm_tools')->dmRepository()->getByName($id);
                // ERROR - unknown
                if($object == "")
                {
                    throw $this->createNotFoundException(
                        $this->trans('partner.group.error.unknown', array(), 'FhmPartnerBundle')
                    );
                } // ERROR - Forbidden
                elseif(!$this->getUser()->hasRole('ROLE_ADMIN') && ($object->getDelete() || !$object->getActive())
                )
                {
                    throw new HttpException(
                        403, $this->trans('partner.group.error.forbidden', array(), 'FhmPartnerBundle')
                    );
                }
            }
            // Partner
            $form = $this->createForm(SearchType::class, null);
            $form->setData($this->get('request_stack')->getCurrentRequest()->get($form->getName()));
            $dataSearch = $form->getData();
            $objects    = $object ? $this->get('fhm_tools')->dmRepository(self::$repository)->getPartnerByGroupIndex(
                $object,
                $dataSearch['search']
            ) : $this->get('fhm_tools')->dmRepository(self::$repository)->getFrontIndex($dataSearch['search']);
        }

        return new Response(
            $this->renderView(
                "::FhmPartner/Template/" . $template . ".html.twig",
                array(
                    'object'  => $object,
                    'objects' => $objects,
                    'form'    => $form ? $form->createView() : $form,
                )
            )
        );
    }
}