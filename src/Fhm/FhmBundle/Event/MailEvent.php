<?php
namespace Fhm\FhmBundle\Event;

use Symfony\Component\EventDispatcher\Event;

/**
 * Class MailEvent
 * @package Fhm\FhmBundle\Event
 */
class MailEvent extends Event
{
    protected $data;

    const MAILUSER      = 'fhm.fhm.mail';

    /**
     * MailEvent constructor.
     * @param $data
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * @return mixed
     */
    public function getData()
    {
        return $this->data;
    }
}