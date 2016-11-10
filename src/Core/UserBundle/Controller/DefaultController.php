<?php

namespace Core\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 * @Route("/user")
 */
class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('CoreUserBundle:Default:index.html.twig');
    }


    /**
     * @Route
     * (
     *      path="/check/facebook",
     *      name="fhm_user_check_facebook"
     * )
     */
    public function checkFacebookAction()
    {
    }

    /**
     * @Route
     * (
     *      path="/check/twitter",
     *      name="fhm_user_check_twitter"
     * )
     */
    public function checkTwitterAction()
    {
    }

    /**
     * @Route
     * (
     *      path="/check/google",
     *      name="fhm_user_check_google"
     * )
     */
    public function checkGoogleAction()
    {
    }
}
