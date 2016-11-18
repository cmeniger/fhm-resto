<?php
namespace Fhm\PartnerBundle\Controller\Group;

use Fhm\FhmBundle\Controller\RefApiController as FhmController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * @Route("/api/partner/group")
 */
class ApiController extends FhmController
{
    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct('Fhm', 'Partner', 'partner_group', 'PartnerGroup');
        $this->translation = array('FhmPartnerBundle', 'partner.group');
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