<?php
namespace Fhm\NewsletterBundle\Controller;

use Fhm\FhmBundle\Controller\RefFrontController as FhmController;
use Fhm\NewsletterBundle\Document\Newsletter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * @Route("/newsletter")
 * ---------------------------------------
 * Class FrontController
 * @package Fhm\NewsletterBundle\Controller
 */
class FrontController extends FhmController
{
    /**
     * FrontController constructor.
     */
    public function __construct()
    {
        self::$repository = "FhmNewsletterBundle:Newsletter";
        self::$source = "fhm";
        self::$domain = "FhmNewsletterBundle";
        self::$translation = "newsletter";
        self::$class = Newsletter::class;
        self::$route = "newsletter";
    }

    /**
     * @Route
     * (
     *      path="/{id}",
     *      name="fhm_newsletter_lite",
     *      requirements={"id"=".+"}
     * )
     * @Template("::FhmNewsletter/Front/detail.html.twig")
     */
    public function liteAction($id)
    {
        return $this->detailAction($id);
    }
}