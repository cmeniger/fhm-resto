<?php
namespace Fhm\UserBundle\Form\Handler\Admin;

use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class PasswordHandler
 * @package Fhm\UserBundle\Form\Handler\Admin
 */
class PasswordHandler
{
    protected $request;
    protected $form;
    protected $mailer;

    /**
     * Initialize the handler with the form and the request
     *
     * @param Form $form
     * @param Request $request
     */
    public function __construct(Form $form, Request $request)
    {
        $this->form = $form;
        $this->request = $request;
    }

    /**
     * Process form
     * @return boolean
     */
    public function process()
    {
        if ('POST' == $this->request->getMethod()) {
            $this->form->handleRequest($this->request);
            if ($this->form->isValid()) {
                return true;
            }
        }

        return false;
    }
}