<?php

namespace Core\MailBundle\Listener;

use Core\FhmBundle\Event\MailEvent;

use Symfony\Component\DependencyInjection\ContainerInterface;

class MailListener
{
    protected $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function onMail(MailEvent $event)
    {
        $this->container->get('fhm_mail')->contactUser($event->getData());
    }
}