<?php

namespace Fhm\MailBundle\Listener;

use Fhm\FhmBundle\Event\MailEvent;

/**
 * Class MailListener
 *
 * @package Fhm\MailBundle\Listener
 */
class MailListener
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
}