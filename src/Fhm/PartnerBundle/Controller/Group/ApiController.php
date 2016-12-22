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
     * AdminController constructor.
     * @param string $repository
     * @param string $source
     * @param string $domain
     * @param string $translation
     * @param string $document
     * @param string $route
     */
    public function __construct(
        $repository = "FhmPartnerBundle:PartnerGroup",
        $source = "fhm",
        $domain = "FhmPartnerBundle",
        $translation = "partner.group",
        $document = PartnerGroup::class,
        $route = 'partner_group'
    ) {
        self::$repository = $repository;
        self::$source = $source;
        self::$domain = $domain;
        self::$translation = $translation;
        self::$document = new $document();
        self::$class = get_class(self::$document);
        self::$route = $route;
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