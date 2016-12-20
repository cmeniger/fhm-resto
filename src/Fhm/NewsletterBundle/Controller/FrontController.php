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
     * ApiController constructor.
     * @param string $repository
     * @param string $source
     * @param string $domain
     * @param string $translation
     * @param $document
     * @param string $route
     */
    public function __construct(
        $repository = "FhmNewsletterBundle:Newsletter",
        $source = "fhm",
        $domain = "FhmNewsletterBundle",
        $translation = "newsletter",
        $document = Newsletter::class,
        $route = 'newsletter'
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