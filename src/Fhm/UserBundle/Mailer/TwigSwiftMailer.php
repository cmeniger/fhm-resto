<?php
namespace Fhm\UserBundle\Mailer;

use Fhm\MailBundle\Services\Mailer;
use FOS\UserBundle\Model\UserInterface;
use FOS\UserBundle\Mailer\TwigSwiftMailer as MailerFOS;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * Class TwigSwiftMailer
 * @package Fhm\UserBundle\Mailer
 */
class TwigSwiftMailer extends MailerFOS
{
    protected $fhm_mail;

    /**
     * TwigSwiftMailer constructor.
     * @param \Swift_Mailer $mailer
     * @param UrlGeneratorInterface $router
     * @param \Twig_Environment $twig
     * @param Mailer $fhmmailer
     * @param array $parameters
     */
    public function __construct(
        \Swift_Mailer $mailer,
        UrlGeneratorInterface $router,
        \Twig_Environment $twig,
        Mailer $fhmmailer,
        array $parameters
    ) {
        parent::__construct($mailer, $router, $twig, $parameters);
        $this->fhm_mail = $fhmmailer;
    }

    /**
     * {@inheritdoc}
     */
    public function sendConfirmationEmailMessage(UserInterface $user)
    {
        $this->fhm_mail->userRegister(
            array(
                'user' => $user,
                'urlConfirm' => $this->router->generate(
                    'fos_user_registration_confirm',
                    array('token' => $user->getConfirmationToken()),
                    true
                ),
                'template' => 'register',
            )
        );
    }

    /**
     * {@inheritdoc}
     */
    public function sendResettingEmailMessage(UserInterface $user)
    {
        $this->fhm_mail->userReset(
            array(
                'user' => $user,
                'urlConfirm' => $this->router->generate(
                    'fos_user_resetting_reset',
                    array('token' => $user->getConfirmationToken()),
                    true
                ),
                'template' => 'reset',
            )
        );
    }
}
