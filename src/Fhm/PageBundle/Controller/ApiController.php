<?php
namespace Fhm\PageBundle\Controller;

use Fhm\FhmBundle\Controller\RefApiController as FhmController;
use Fhm\PageBundle\Document\Page;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * @Route("/api/page")
 */
class ApiController extends FhmController
{
    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct('Fhm', 'Page', 'page');
    }

    /**
     * @Route
     * (
     *      path="/",
     *      name="fhm_api_page"
     * )
     * @Template("::FhmPage/Api/index.html.twig")
     */
    public function indexAction()
    {
        return parent::indexAction();
    }

    /**
     * @Route
     * (
     *      path="/autocomplete/",
     *      name="fhm_api_page_autocomplete"
     * )
     * @Template("::FhmPage/Api/autocomplete.html.twig")
     */
    public function autocompleteAction(Request $request)
    {
        return parent::autocompleteAction($request);
    }
}