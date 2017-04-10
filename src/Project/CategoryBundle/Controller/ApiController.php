<?php
namespace Project\CategoryBundle\Controller;

use Fhm\FhmBundle\Controller\RefApiController as FhmController;
use Fhm\CategoryBundle\Document\Category;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/api/category")
 */
class ApiController extends FhmController
{
    /**
     * Constructor
     */
    public function __construct()
    {
        self::$repository = "ProjectCategoryBundle:Category";
        self::$domain = "ProjectCategoryBundle";
        self::$translation = "category";
        self::$route = "category";
        self::$source = "project";
    }

    /**
     * @Route
     * (
     *      path="/",
     *      name="fhm_api_category"
     * )
     * @Template("::FhmCategory/Api/index.html.twig")
     */
    public function indexAction()
    {
        return parent::indexAction();
    }
}