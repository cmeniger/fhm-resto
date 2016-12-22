<?php
namespace Fhm\MailBundle\Controller;

use Fhm\FhmBundle\Controller\RefFrontController as FhmController;
use Fhm\MailBundle\Document\Mail;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * @Route("/mail")
 * ----------------------------------
 * Class FrontController
 * @package Fhm\MailBundle\Controller
 */
class FrontController extends FhmController
{
    /**
     * FrontController constructor.
     */
    public function __construct()
    {
        self::$repository = "FhmMailBundle:Mail";
        self::$source = "fhm";
        self::$domain = "FhmMailBundle";
        self::$translation = "mail";
        self::$class = Mail::class;
        self::$route = "mail";
    }

    /**
     * @Route
     * (
     *      path="/{id}",
     *      name="fhm_mail_lite",
     *      requirements={"id"=".+"}
     * )
     * @Template("::FhmMail/Front/detail.html.twig")
     */
    public function liteAction($id)
    {
        return $this->detailAction($id);
    }
}