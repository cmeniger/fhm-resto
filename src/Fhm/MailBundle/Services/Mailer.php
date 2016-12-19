<?php
namespace Fhm\MailBundle\Services;

use Symfony\Component\Templating\EngineInterface;

/**
 * Class Mailer
 * @package Fhm\MailBundle\Services
 */
class Mailer
{
    protected $fhm_tools;

    /**
     * Mailer constructor.
     * @param \Fhm\FhmBundle\Services\Tools $tools
     */
    public function __construct(\Fhm\FhmBundle\Services\Tools $tools)
    {
        $this->fhm_tools = $tools;
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
        if ($this->fhm_tools->getParameters('enable', 'fhm_mailer')) {
            $transport = \Swift_SmtpTransport::newInstance(
                $this->fhm_tools->getParameters(null, 'mailer_host'),
                $this->fhm_tools->getParameters(null, 'mailer_port')
            )->setUsername($this->fhm_tools->getParameters(null, 'mailer_user'))->setPassword(
                $this->fhm_tools->getParameters(null, 'mailer_password')
            )->setEncryption($this->fhm_tools->getParameters(null, 'mailer_encryption'));
            $mailer = \Swift_Mailer::newInstance($transport);
            $message = \Swift_Message::newInstance()->setSubject($subject)->setFrom($fromEmail)->setTo(
                $toEmail
            )->setBody($body)->setContentType('text/html');
            $mailer->send($message);
            // Save mail
            $document = new \Fhm\MailBundle\Document\Mail();
            $document->setType('mail')->setModel($model)->setFrom(array_keys($fromEmail)[0])->setTo(
                $toEmail
            )->setSubject($subject)->setBody($body);
            $this->fhm_tools->dmPersist($document);
        } else {
            $this->fhm_tools->getSession()->getFlashBag()->add(
                'notice',
                'mail.flash.disable',
                array(),
                'FhmMailBundle'
            );
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
        $thread = $this->fhm_tools->getContainer()->get('fos_message.composer')->newThread();
        $thread->addRecipient($toUser)->setSender($fromUser)->setSubject($subject)->setBody($body);
        $sender = $this->fhm_tools->getContainer()->get('fos_message.sender');
        $sender->send($thread->getMessage());
        // Save mail
        $document = new \Fhm\MailBundle\Document\Mail();
        $document->setType('message')->setModel($model)->setFrom($fromUser->getEmailCanonical())->setTo(
            $toUser->getEmailCanonical()
        )->setSubject($subject)->setBody($body);
        $this->fhm_tools->dmPersist($document);
    }

    /**
     * @param $data
     * @param string $folder
     * @return mixed
     */
    public function renderMail($data, $folder = '')
    {
        return $this->fhm_tools->getContainer()->get('templating')->render(
            '::FhmMail/Template/'.$folder.'/'.$data['template'].'.html.twig',
            array_merge(
                $data,
                array(
                    'server_http_host' => $this->fhm_tools->getParameters('host', 'fhm_mailer'),
                    'version' => 'mail',
                )
            )
        );
    }

    /**
     * @param $data
     * @param string $folder
     * @return mixed
     */
    public function renderMessage($data, $folder = '')
    {
        return $this->fhm_tools->getContainer()->get('templating')->render(
            '::FhmMail/'.$folder.'/'.$data['template'].'.html.twig',
            array_merge(
                $data,
                array(
                    'server_http_host' => '',
                    'version' => 'message',
                )
            )
        );
    }

    /**
     * Admin Test
     */
    public function adminTest()
    {
        $noreply = $this->fhm_tools->dmRepository("FhmUserBundle:User")->getUserByEmail(
            $this->fhm_tools->getParameters('noreply', 'fhm_mailer')
        );
        // Email - Admin
        $this->sendMail(
            array($noreply->getEmail() => $this->fhm_tools->getParameters('sign', 'fhm_mailer')),
            $this->fhm_tools->getParameters('admin', 'fhm_mailer'),
            $this->fhm_tools->getParameters('project', 'fhm_mailer')." email test",
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
            array(
                $this->fhm_tools->getParameters('contact', 'fhm_mailer') => $this->fhm_tools->getParameters(
                    'sign',
                    'fhm_mailer'
                ),
            ),
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
        $noreply = $this->fhm_tools->dmRepository("FhmUserBundle:User")->getUserByEmail(
            $this->fhm_tools->getParameters('noreply', 'fhm_mailer')
        );
        $data["send_mail"] = (isset($data["send_mail"])) ? $data["send_mail"] : true;
        // Email - User
        if ($data["send_mail"]) {
            $this->sendMail(
                array($noreply->getEmail() => $this->fhm_tools->getParameters('sign', 'fhm_mailer')),
                $data['user']->getEmail(),
                "Bienvenue sur ".$this->fhm_tools->getParameters('project', 'fhm_mailer'),
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
        $noreply = $this->fhm_tools->dmRepository("FhmUserBundle:User")->getUserByEmail(
            $this->fhm_tools->getParameters('noreply', 'fhm_mailer')
        );
        $data["send_mail"] = (isset($data["send_mail"])) ? $data["send_mail"] : true;
        // Email - User
        if ($data["send_mail"]) {
            $this->sendMail(
                array($noreply->getEmail() => $this->fhm_tools->getParameters('sign', 'fhm_mailer')),
                $data['user']->getEmail(),
                "Réinitialiser mon mot de passe ".$this->fhm_tools->getParameters('project', 'fhm_mailer'),
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
        $noreply = $this->fhm_tools->dmRepository("FhmUserBundle:User")->getUserByEmail(
            $this->fhm_tools->getParameters('noreply', 'fhm_mailer')
        );
        $data['template'] = isset($data['template']) ? $data['template'] : "default";
        // Email - Contact
        $this->sendMail(
            array($noreply->getEmail() => $this->fhm_tools->getParameters('sign', 'fhm_mailer')),
            $this->fhm_tools->getParameters('contact', 'fhm_mailer'),
            "[CONTACT][".$data['message']->getContact()->getName()."] ".$this->fhm_tools->getParameters(
                'project',
                'fhm_mailer'
            ),
            $this->renderMail($data, 'Contact'),
            'contact > '.$data['template']
        );
        // Email - User
        $this->sendMail(
            array($noreply->getEmail() => $this->fhm_tools->getParameters('sign', 'fhm_mailer')),
            $data['message']->getEmail(),
            $this->fhm_tools->getParameters('project', 'fhm_mailer')." contact",
            $this->renderMail($data, 'Contact'),
            'contact > '.$data['template']
        );
    }
}