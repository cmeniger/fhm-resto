<?php

namespace Fhm\FhmBundle\Form\Handler\Admin;

use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;

class ImportHandler
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
     * @return array|bool
     */
    public function process()
    {
        if ('POST' == $this->request->getMethod()) {
            $this->form->handleRequest($this->request);
            if ($this->form->isValid()) {
                $handle = fopen($this->form->get('file')->getData()->getRealPath(), 'r');
                $datas  = array();
                $final  = array();
                ini_set('auto_detect_line_endings', true);
                while (($data = fgetcsv($handle, 0, ';')) !== false) {
                    $datas[] = count($data) > 1 ? $data : explode(',', $data[0]);
                }
                ini_set('auto_detect_line_endings', false);
                // Format data
                $header = array_shift($datas);
                foreach ($datas as $data) {
                    $tab = array();
                    for ($i = 0; $i < count($data); $i++) {
                        $tab[$header[$i]] = $data[$i];
                    }
                    $final[] = $tab;
                }

                return $final;
            }
        }

        return false;
    }
}