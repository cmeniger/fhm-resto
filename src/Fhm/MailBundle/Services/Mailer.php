<?php
namespace Fhm\MailBundle\Services;

use Symfony\Component\Templating\EngineInterface;

class Mailer
{
    protected $mailer;
    protected $templating;
    protected $fhm_tools;
    protected $container;

    public function __construct(\Fhm\FhmBundle\Services\Tools $tools, \Swift_Mailer $mailer, EngineInterface $templating)
    {
        $this->fhm_tools  = $tools;
        $this->mailer     = $mailer;
        $this->templating = $templating;
        $this->container  = $tools->getContainer();
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
        if($this->fhm_tools->getParameter('enable', 'fhm_mailer'))
        {
            $transport = \Swift_SmtpTransport::newInstance($this->container->getParameter('mailer_host'), $this->container->getParameter('mailer_port'))
                ->setUsername($this->container->getParameter('mailer_user'))
                ->setPassword($this->container->getParameter('mailer_password'))
                ->setEncryption($this->container->getParameter('mailer_encryption'));
            $mailer    = \Swift_Mailer::newInstance($transport);
            $message   = \Swift_Message::newInstance()
                ->setSubject($subject)
                ->setFrom($fromEmail)
                ->setTo($toEmail)
                ->setBody($body)
                ->setContentType('text/html');
            $mailer->send($message);
            // Save mail
            $document = new \Fhm\MailBundle\Document\Mail();
            $document
                ->setType('mail')
                ->setModel($model)
                ->setFrom(array_keys($fromEmail)[0])
                ->setTo($toEmail)
                ->setSubject($subject)
                ->setBody($body);
            $this->fhm_tools->dmPersist($document);
        }
        else
        {
            $this->container->get('session')->getFlashBag()->add('notice', 'mail.flash.disable', array(), 'FhmMailBundle');
        }
    }

    /**
     * @param $fromUser
     * @param $toUser
     * @param $subject
     * @param $body
     * @param $model
     */
    public function sendMessage($fromUser, $toUser, $subject, $body, $model)
    {
        $thread = $this->container->get('fos_message.composer')->newThread();
        $thread
            ->addRecipient($toUser)
            ->setSender($fromUser)
            ->setSubject($subject)
            ->setBody($body);
        $sender = $this->container->get('fos_message.sender');
        $sender->send($thread->getMessage());
        // Save mail
        $document = new \Fhm\MailBundle\Document\Mail();
        $document
            ->setType('message')
            ->setModel($model)
            ->setFrom($fromUser->getEmailCanonical())
            ->setTo($toUser->getEmailCanonical())
            ->setSubject($subject)
            ->setBody($body);
        $this->fhm_tools->dmPersist($document);
    }

    /**
     * Template mail
     */
    public function renderMail($data, $folder = '')
    {
        return $this->templating->render
        (
            '::FhmMail/Template/' . $folder . '/' . $data['template'] . '.html.twig',
            array_merge
            (
                $data,
                array
                (
                    'server_http_host' => $this->fhm_tools->getParameter('host', 'fhm_mailer'),
                    'version'          => 'mail'
                )
            )
        );
    }

    /**
     * Template message
     */
    public function renderMessage($data, $folder = '')
    {
        return $this->templating->render
        (
            '::FhmMail/' . $folder . '/' . $data['template'] . '.html.twig',
            array_merge
            (
                $data,
                array
                (
                    'server_http_host' => '',
                    'version'          => 'message'
                )
            )
        );
    }

    /**
     * Admin test
     */
    public function adminTest()
    {
        $dm      = $this->container->get('doctrine.odm.mongodb.document_manager');
        $noreply = $dm->getRepository("FhmUserBundle:User")->getUserByEmail($this->fhm_tools->getParameter('noreply', 'fhm_mailer'));
        // Email - Admin
        $this->sendMail
        (
            array($noreply->getEmail() => $this->fhm_tools->getParameter('sign', 'fhm_mailer')),
            $this->fhm_tools->getParameter('admin', 'fhm_mailer'),
            $this->fhm_tools->getParameter('project', 'fhm_mailer') . " email test",
            $this->renderMail(array('template' => 'test'), 'Admin'),
            'admin > test'
        );
    }

    /**
     * Admin message
     */
    public function adminMessage($data)
    {
        $dm               = $this->container->get('doctrine.odm.mongodb.document_manager');
        $data['template'] = 'message';
        // var_dump($data); exit;
        // Email
        $this->sendMail
        (
            array($this->fhm_tools->getParameter('contact', 'fhm_mailer') => $this->fhm_tools->getParameter('sign', 'fhm_mailer')),
            $data['to'],
            $data['object'],
            $this->renderMail($data, 'Admin'),
            'admin > message'
        );
    }

    /**
     * Utilisateur enregistré
     */
    public function userRegister($data)
    {
        $dm                = $this->container->get('doctrine.odm.mongodb.document_manager');
        $noreply           = $dm->getRepository("FhmUserBundle:User")->getUserByEmail($this->fhm_tools->getParameter('noreply', 'fhm_mailer'));
        $data["send_mail"] = (isset($data["send_mail"])) ? $data["send_mail"] : true;
        // Email - User
        if($data["send_mail"])
        {
            $this->sendMail
            (
                array($noreply->getEmail() => $this->fhm_tools->getParameter('sign', 'fhm_mailer')),
                $data['user']->getEmail(),
                "Bienvenue sur " . $this->fhm_tools->getParameter('project', 'fhm_mailer'),
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
        $dm                = $this->container->get('doctrine.odm.mongodb.document_manager');
        $noreply           = $dm->getRepository("FhmUserBundle:User")->getUserByEmail($this->fhm_tools->getParameter('noreply', 'fhm_mailer'));
        $data["send_mail"] = (isset($data["send_mail"])) ? $data["send_mail"] : true;
        // Email - User
        if($data["send_mail"])
        {
            $this->sendMail
            (
                array($noreply->getEmail() => $this->fhm_tools->getParameter('sign', 'fhm_mailer')),
                $data['user']->getEmail(),
                "Réinitialiser mon mot de passe " . $this->fhm_tools->getParameter('project', 'fhm_mailer'),
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
        $dm               = $this->container->get('doctrine.odm.mongodb.document_manager');
        $noreply          = $dm->getRepository("FhmUserBundle:User")->getUserByEmail($this->fhm_tools->getParameter('noreply', 'fhm_mailer'));
        $data['template'] = isset($data['template']) ? $data['template'] : "default";
        // Email - Contact
        $this->sendMail
        (
            array($noreply->getEmail() => $this->fhm_tools->getParameter('sign', 'fhm_mailer')),
            $this->fhm_tools->getParameter('contact', 'fhm_mailer'),
            "[CONTACT][" . $data['message']->getContact()->getName() . "] " . $this->fhm_tools->getParameter('project', 'fhm_mailer'),
            $this->renderMail($data, 'Contact'),
            'contact > ' . $data['template']
        );
        // Email - User
        $this->sendMail
        (
            array($noreply->getEmail() => $this->fhm_tools->getParameter('sign', 'fhm_mailer')),
            $data['message']->getEmail(),
            $this->fhm_tools->getParameter('project', 'fhm_mailer') . " contact",
            $this->renderMail($data, 'Contact'),
            'contact > ' . $data['template']
        );
    }
}