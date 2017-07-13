<?php

namespace Fhm\MailBundle\Services;

use Symfony\Component\Templating\EngineInterface;

/**
 * Class Mailer
 *
 * @package Fhm\MailBundle\Services
 */
class Mailer
{
    protected $tools;
    protected $manager;
    protected $email_admin;
    protected $email_noreply;
    protected $email_contact;
    protected $email_sign;

    /**
     * Mailer constructor.
     *
     * @param \Fhm\FhmBundle\Services\Tools           $tools
     * @param \Fhm\FhmBundle\Manager\FhmObjectManager $manager
     */
    public function __construct(\Fhm\FhmBundle\Services\Tools $tools, \Fhm\FhmBundle\Manager\FhmObjectManager $manager)
    {
        $this->tools         = $tools;
        $this->manager       = $manager;
        $this->email_admin   = $this->tools->getParameters('admin', 'fhm_mailer');
        $this->email_noreply = $this->tools->getParameters('noreply', 'fhm_mailer');
        $this->email_contact = $this->tools->getParameters('contact', 'fhm_mailer');
        $this->email_sign    = $this->tools->getParameters('sign', 'fhm_mailer');
    }

    /**
     * @param $fromEmail
     * @param $toEmail
     * @param $subject
     * @param $body
     * @param $model
     */
    public function sendMail($fromEmail, $toEmail, $subject, $body, $model)
    {
        if($this->tools->getParameters('enable', 'fhm_mailer'))
        {
            $transport = \Swift_SmtpTransport::newInstance($this->tools->getParameters(null, 'mailer_host'), $this->tools->getParameters(null, 'mailer_port'))
                ->setUsername($this->tools->getParameters(null, 'mailer_user'))
                ->setPassword($this->tools->getParameters(null, 'mailer_password'))
                ->setEncryption($this->tools->getParameters(null, 'mailer_encryption'));
            $mailer    = \Swift_Mailer::newInstance($transport);
            $message   = \Swift_Message::newInstance()->setSubject($subject)->setFrom($fromEmail)->setTo($toEmail)->setBody($body)->setContentType('text/html');
            $mailer->send($message);
            // Save mail
            $mailClass = $this->manager->getCurrentModelName('FhmMailBundle:Mail');
            $object    = new $mailClass;
            $object
                ->setType('mail')
                ->setModel($model)
                ->setFrom(array_keys($fromEmail)[0])
                ->setTo($toEmail)
                ->setSubject($subject)
                ->setBody($body);
            $this->tools->dmPersist($object);
        }
        else
        {
            $this->tools->getSession()->getFlashBag()->add(
                'notice',
                'mail.flash.disable',
                array(),
                'FhmMailBundle'
            );
        }
    }

    /**
     * @param        $data
     * @param string $folder
     *
     * @return mixed
     */
    public function renderMail($data, $folder = '')
    {
        return $this->tools->getContainer()->get('templating')->render(
            '::FhmMail/Template/' . $folder . '/' . $data['template'] . '.html.twig',
            array_merge(
                $data,
                array(
                    'server_http_host' => $this->tools->getParameters('host', 'fhm_mailer'),
                    'version'          => 'mail',
                    'site'             => "",
                )
            )
        );
    }

    /**
     * Admin Test
     */
    public function adminTest()
    {
        // Email - Admin
        $this->sendMail(
            array($this->email_noreply => $this->email_sign),
            $this->email_contact,
            $this->tools->trans('project.email.admin.test.subject', array(), 'ProjectDefaultBundle'),
            $this->renderMail(array('template' => 'test'), 'Admin'),
            'admin > test'
        );
    }

    /**
     * Admin message
     */
    public function adminMessage($data)
    {
        $data['template'] = 'message';
        $this->sendMail(
            array($this->email_noreply => $this->email_sign),
            $data['to'],
            $data['object'],
            $this->renderMail($data, 'Admin'),
            'admin > message'
        );
    }

    /**
     * Utilisateur enregistré
     *
     * @param $data
     */
    public function userRegister($data)
    {
        $data["send_mail"] = (isset($data["send_mail"])) ? $data["send_mail"] : true;
        if($data["send_mail"])
        {
            $this->sendMail(
                array($this->email_noreply => $this->email_sign),
                $data['user']->getEmail(),
                $this->tools->trans('project.email.user.register.subject', array(), 'ProjectDefaultBundle'),
                $this->renderMail($data, 'User'),
                'user > register'
            );
        }
    }

    /**
     * Utilisateur reset password
     */
    public function userReset($data)
    {
        $data["send_mail"] = (isset($data["send_mail"])) ? $data["send_mail"] : true;
        // Email - User
        if($data["send_mail"])
        {
            $this->sendMail(
                array($this->email_noreply => $this->email_sign),
                $data['user']->getEmail(),
                $this->tools->trans('project.email.user.register.subject', array(), 'ProjectDefaultBundle'),
                $this->renderMail($data, 'User'),
                'user > reset'
            );
        }
    }

    /**
     * Formulaire de contact
     */
    public function contact($data)
    {
        $data['template'] = isset($data['template']) ? $data['template'] : "default";
        // Email - Contact
        $this->sendMail(
            array($this->email_noreply => $this->email_sign),
            $this->email_contact,
            $this->tools->trans('project.email.contact.admin.subject', array(), 'ProjectDefaultBundle'),
            $this->renderMail($data, 'Contact'),
            'contact > ' . $data['template']
        );
        // Email - User
        $this->sendMail(
            array($this->email_noreply => $this->email_sign),
            $data['message']->getEmail(),
            $this->tools->trans('project.email.contact.user.subject', array(), 'ProjectDefaultBundle'),
            $this->renderMail($data, 'Contact'),
            'contact > ' . $data['template']
        );
    }
}