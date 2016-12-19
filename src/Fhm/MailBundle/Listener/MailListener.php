<?php

namespace Fhm\MailBundle\Listener;

use Fhm\FhmBundle\Event\MailEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Class MailListener
 *
 * @package Fhm\MailBundle\Listener
 */
class MailListener implements EventSubscriberInterface
{
    protected $mailer;

    /**
     * MailListener constructor.
     *
     * @param \Fhm\MailBundle\Services\Mailer $mailer
     */
    public function __construct(\Fhm\MailBundle\Services\Mailer $mailer)
    {
        $this->mailer = $mailer;
    }

    /**
     * @param MailEvent $event
     */
    public function onMail(MailEvent $event)
    {
        $this->mailer->contactUser($event->getData());
    }

    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return array('fhm.fhm.mail'=> 'onMail');
    }
}