<?php
namespace Fhm\UserBundle\Mailer;

use FOS\UserBundle\Model\UserInterface;
use FOS\UserBundle\Mailer\TwigSwiftMailer as MailerFOS;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class TwigSwiftMailer extends MailerFOS
{
    protected $container;
    
    public function __construct(\Swift_Mailer $mailer, UrlGeneratorInterface $router, \Twig_Environment $twig, ContainerInterface $container, array $parameters)
    {
        parent::__construct($mailer, $router, $twig, $parameters);
        $this->container = $container;
    }

    /**
     * {@inheritdoc}
     */
    public function sendConfirmationEmailMessage(UserInterface $user)
    {
        $this->container->get('fhm_mail')->userRegister
        (
            array
            (
                'user'       => $user,
                'urlConfirm' => $this->router->generate('fos_user_registration_confirm', array('token' => $user->getConfirmationToken()), true),
                'template'   => 'register'
            )
        );
    }

    /**
     * {@inheritdoc}
     */
    public function sendResettingEmailMessage(UserInterface $user)
    {
        $this->container->get('fhm_mail')->userReset
        (
            array
            (
                'user'       => $user,
                'urlConfirm' => $this->router->generate('fos_user_resetting_reset', array('token' => $user->getConfirmationToken()), true),
                'template'   => 'reset'
            )
        );
    }
}
