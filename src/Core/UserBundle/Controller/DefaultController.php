<?php

namespace Core\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 * @Route("/user")
 */
class DefaultController extends Controller
{
    /**
     * @Route
     * (
     *      path="/",
     *      name="fhm_user_index"
     * )
     */
    public function indexAction()
    {
        $transport = \Swift_SmtpTransport::newInstance($this->container->getParameter('mailer_host'), $this->container->getParameter('mailer_port'))
            ->setUsername($this->container->getParameter('mailer_user'))
            ->setPassword($this->container->getParameter('mailer_password'))
            ->setEncryption($this->container->getParameter('mailer_encryption'));
        $mailer    = \Swift_Mailer::newInstance($transport);
        $message = \Swift_Message::newInstance()
            ->setSubject('Hello Email')
            ->setFrom('azerty@azerty.com')
            ->setTo('test@test.com')
            ->setBody(
                'TEST'
            )
            /*
             * If you also want to include a plaintext version of the messageé
            ->addPart(
                $this->renderView(
                    'Emails/registration.txt.twig',
                    array('name' => $name)
                ),
                'text/plain'
            )
            */
        ;
        $mailer->send($message);
//        $transport =  $this->get('mailer')->getTransport();
//        if ($transport instanceof \Swift_Transport_SpoolTransport) {
//            $spool = $transport->getSpool();
//            $sent = $spool->flushQueue($this->container->get('swiftmailer.transport.real'));
//        }

//        return $this->render(...);
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
