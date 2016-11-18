<?php
namespace Fhm\FhmBundle\Event;

use Symfony\Component\EventDispatcher\Event;

class MailEvent extends Event
{
    protected $data;

    const MAILUSER      = 'fhm.fhm.mail';

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function getData()
    {
        return $this->data;
    }
}