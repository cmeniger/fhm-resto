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
 */
class FrontController extends FhmController
{
    /**
     * AdminController constructor.
     */
    public function __construct()
    {
        self::$repository = "FhmUserBundle:User";
        self::$source = "fhm";
        self::$domain = "FhmUserBundle";
        self::$translation = "user";
        self::$document = new Newsletter();
        self::$class = get_class(self::$document);
        self::$route = 'user';
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