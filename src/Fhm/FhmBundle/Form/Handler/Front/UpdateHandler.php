<?php

namespace Fhm\FhmBundle\Form\Handler\Front;

use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class UpdateHandler
 * @package Fhm\FhmBundle\Form\Handler\Front
 */
class UpdateHandler
{
    protected $request;
    protected $form;
    protected $mailer;

    /**
     * Initialize the handler with the form and the request
     *
     * @param Form    $form
     * @param Request $request
     */
    public function __construct(Form $form, Request $request)
    {
        $this->form    = $form;
        $this->request = $request;
    }

    /**
     * Process form
     *
     * @return boolean
     */
    public function process()
    {
        if ('POST' == $this->request->getMethod()) {
            $this->form->handleRequest($this->request);
            if ($this->form->isSubmitted() && $this->form->isValid()) {
                return true;
            }
        }

        return false;
    }
}