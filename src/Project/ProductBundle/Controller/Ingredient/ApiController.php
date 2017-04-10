<?php
namespace Project\ProductBundle\Controller\Ingredient;

use Fhm\FhmBundle\Controller\RefApiController as FhmController;
use Project\ProductBundle\Document\Product;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * @Route("/api/productingredient")
 */
class ApiController extends FhmController
{
    /**
     * Constructor
     */
    public function __construct()
    {
        self::$repository = "ProjectProductBundle:ProductIngredient";
        self::$domain = "ProjectProductBundle";
        self::$translation = "ingredient";
        self::$route = "ingredient";
        self::$source = "project";
    }

    /**
     * @Route
     * (
     *      path="/",
     *      name="fhm_api_product_ingredient"
     * )
     * @Template("::ProjectProduct/Api/Ingredient/index.html.twig")
     */
    public function indexAction()
    {
        return parent::indexAction();
    }
}