<?php
namespace Fhm\PartnerBundle\Controller\Group;

use Fhm\FhmBundle\Controller\RefApiController as FhmController;
use Fhm\PartnerBundle\Document\PartnerGroup;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * @Route("/api/partner/group")
 * ------------------------------------------
 * Class ApiController
 * @package Fhm\PartnerBundle\Controller\Group
 */
class ApiController extends FhmController
{
    /**
     * ApiController constructor.
     */
    public function __construct()
    {
        self::$repository = "FhmPartnerBundle:PartnerGroup";
        self::$source = "fhm";
        self::$domain = "FhmPartnerBundle";
        self::$translation = "partner.group";
        self::$class = PartnerGroup::class;
        self::$route = "partner_group";
    }

    /**
     * @Route
     * (
     *      path="/",
     *      name="fhm_api_partner_group"
     * )
     * @Template("::FhmPartner/Api/Group/index.html.twig")
     */
    public function indexAction()
    {
        return parent::indexAction();
    }

    /**
     * @Route
     * (
     *      path="/autocomplete/",
     *      name="fhm_api_partner_group_autocomplete"
     * )
     * @Template("::FhmPartner/Api/Group/autocomplete.html.twig")
     */
    public function autocompleteAction(Request $request)
    {
        return parent::autocompleteAction($request);
    }
}