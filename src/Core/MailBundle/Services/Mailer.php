<?php
namespace Core\MailBundle\Services;

use Core\FhmBundle\Controller\FhmController;
use Symfony\Component\Templating\EngineInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class Mailer extends ContainerInterface
{
    protected $mailer;
    protected $templating;

    public function __construct(\Swift_Mailer $mailer, EngineInterface $templating)
    {
        $this->mailer     = $mailer;
        $this->templating = $templating;
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
        if($this->getParameter('enable', 'fhm_mailer'))
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
            $this->dmPersist($document);
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
        $this->dmPersist($document);
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
                    'server_http_host' => $this->getParameter('host', 'fhm_mailer'),
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
        $noreply = $dm->getRepository("FhmUserBundle:User")->getUserByEmail($this->getParameter('noreply', 'fhm_mailer'));
        // Email - Admin
        $this->sendMail
        (
            array($noreply->getEmail() => $this->getParameter('sign', 'fhm_mailer')),
            $this->getParameter('admin', 'fhm_mailer'),
            $this->getParameter('project', 'fhm_mailer') . " email test",
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
            array($this->getParameter('contact', 'fhm_mailer') => $this->getParameter('sign', 'fhm_mailer')),
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
        $noreply           = $dm->getRepository("FhmUserBundle:User")->getUserByEmail($this->getParameter('noreply', 'fhm_mailer'));
        $data["send_mail"] = (isset($data["send_mail"])) ? $data["send_mail"] : true;
        // Email - User
        if($data["send_mail"])
        {
            $this->sendMail
            (
                array($noreply->getEmail() => $this->getParameter('sign', 'fhm_mailer')),
                $data['user']->getEmail(),
                "Bienvenue sur " . $this->getParameter('project', 'fhm_mailer'),
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
        $noreply           = $dm->getRepository("FhmUserBundle:User")->getUserByEmail($this->getParameter('noreply', 'fhm_mailer'));
        $data["send_mail"] = (isset($data["send_mail"])) ? $data["send_mail"] : true;
        // Email - User
        if($data["send_mail"])
        {
            $this->sendMail
            (
                array($noreply->getEmail() => $this->getParameter('sign', 'fhm_mailer')),
                $data['user']->getEmail(),
                "Réinitialiser mon mot de passe " . $this->getParameter('project', 'fhm_mailer'),
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
        $noreply          = $dm->getRepository("FhmUserBundle:User")->getUserByEmail($this->getParameter('noreply', 'fhm_mailer'));
        $data['template'] = isset($data['template']) ? $data['template'] : "default";
        // Email - Contact
        $this->sendMail
        (
            array($noreply->getEmail() => $this->getParameter('sign', 'fhm_mailer')),
            $this->getParameter('contact', 'fhm_mailer'),
            "[CONTACT][" . $data['message']->getContact()->getName() . "] " . $this->getParameter('project', 'fhm_mailer'),
            $this->renderMail($data, 'Contact'),
            'contact > ' . $data['template']
        );
        // Email - User
        $this->sendMail
        (
            array($noreply->getEmail() => $this->getParameter('sign', 'fhm_mailer')),
            $data['message']->getEmail(),
            $this->getParameter('project', 'fhm_mailer') . " contact",
            $this->renderMail($data, 'Contact'),
            'contact > ' . $data['template']
        );
    }

    /**
     * Place - Moderation - Create
     */
    public function placeModerationCreate($data)
    {
        $dm               = $this->container->get('doctrine.odm.mongodb.document_manager');
        $noreply          = $dm->getRepository("FhmUserBundle:User")->getUserByEmail($this->getParameter('noreply', 'fhm_mailer'));
        $data['template'] = isset($data['template']) ? $data['template'] : "moderation.create";
        // Email - Admin
        $this->sendMail
        (
            array($noreply->getEmail() => $this->getParameter('sign', 'fhm_mailer')),
            $this->getParameter('admin', 'fhm_mailer'),
            $this->get('translator')->trans('place.email.moderation.create.admin.object', array(), 'ProjectPlaceBundle'),
            $this->renderMail(array_merge($data, array('type' => 'admin')), 'Place'),
            'place > moderation > create > admin'
        );
        // Email - User
        $this->sendMail
        (
            array($noreply->getEmail() => $this->getParameter('sign', 'fhm_mailer')),
            $data['user']->getEmail(),
            $this->get('translator')->trans('place.email.moderation.create.user.object', array(), 'ProjectPlaceBundle'),
            $this->renderMail(array_merge($data, array('type' => 'user')), 'Place'),
            'place > moderation > create > user'
        );
    }

    /**
     * Place - Moderation - Create - Accept
     */
    public function placeModerationCreateAccept($data)
    {
        $dm               = $this->container->get('doctrine.odm.mongodb.document_manager');
        $noreply          = $dm->getRepository("FhmUserBundle:User")->getUserByEmail($this->getParameter('noreply', 'fhm_mailer'));
        $data['template'] = isset($data['template']) ? $data['template'] : "moderation.create.accept";
        // Email - User
        $this->sendMail
        (
            array($noreply->getEmail() => $this->getParameter('sign', 'fhm_mailer')),
            $data['user']->getEmail(),
            $this->get('translator')->trans('place.email.moderation.create.accept.object', array(), 'ProjectPlaceBundle'),
            $this->renderMail($data, 'Place'),
            'place > moderation > create > accept'
        );
    }

    /**
     * Place - Moderation - Create - Refuse
     */
    public function placeModerationCreateRefuse($data)
    {
        $dm               = $this->container->get('doctrine.odm.mongodb.document_manager');
        $noreply          = $dm->getRepository("FhmUserBundle:User")->getUserByEmail($this->getParameter('noreply', 'fhm_mailer'));
        $data['template'] = isset($data['template']) ? $data['template'] : "moderation.create.refuse";
        // Email - User
        $this->sendMail
        (
            array($noreply->getEmail() => $this->getParameter('sign', 'fhm_mailer')),
            $data['user']->getEmail(),
            $this->get('translator')->trans('place.email.moderation.create.refuse.object', array(), 'ProjectPlaceBundle'),
            $this->renderMail($data, 'Place'),
            'place > moderation > create > refuse'
        );
    }

    /**
     * Place - Moderation - Create - Cancel
     */
    public function placeModerationCreateCancel($data)
    {
        $dm               = $this->container->get('doctrine.odm.mongodb.document_manager');
        $noreply          = $dm->getRepository("FhmUserBundle:User")->getUserByEmail($this->getParameter('noreply', 'fhm_mailer'));
        $data['template'] = isset($data['template']) ? $data['template'] : "moderation.create.cancel";
        // Email - User
        $this->sendMail
        (
            array($noreply->getEmail() => $this->getParameter('sign', 'fhm_mailer')),
            $data['user']->getEmail(),
            $this->get('translator')->trans('place.email.moderation.create.cancel.object', array(), 'ProjectPlaceBundle'),
            $this->renderMail($data, 'Place'),
            'place > moderation > create > cancel'
        );
    }

    /**
     * Place - Moderation - Moderator
     */
    public function placeModerationModerator($data)
    {
        $dm               = $this->container->get('doctrine.odm.mongodb.document_manager');
        $noreply          = $dm->getRepository("FhmUserBundle:User")->getUserByEmail($this->getParameter('noreply', 'fhm_mailer'));
        $data['template'] = isset($data['template']) ? $data['template'] : "moderation.moderator";
        // Email - Admin
        $this->sendMail
        (
            array($noreply->getEmail() => $this->getParameter('sign', 'fhm_mailer')),
            $this->getParameter('admin', 'fhm_mailer'),
            $this->get('translator')->trans('place.email.moderation.moderator.admin.object', array(), 'ProjectPlaceBundle'),
            $this->renderMail(array_merge($data, array('type' => 'admin')), 'Place'),
            'place > moderation > moderator > admin'
        );
        // Email - User
        $this->sendMail
        (
            array($noreply->getEmail() => $this->getParameter('sign', 'fhm_mailer')),
            $data['user']->getEmail(),
            $this->get('translator')->trans('place.email.moderation.moderator.user.object', array(), 'ProjectPlaceBundle'),
            $this->renderMail(array_merge($data, array('type' => 'user')), 'Place'),
            'place > moderation > moderator > user'
        );
    }

    /**
     * Place - Moderation - Moderator - Accept
     */
    public function placeModerationModeratorAccept($data)
    {
        $dm               = $this->container->get('doctrine.odm.mongodb.document_manager');
        $noreply          = $dm->getRepository("FhmUserBundle:User")->getUserByEmail($this->getParameter('noreply', 'fhm_mailer'));
        $data['template'] = isset($data['template']) ? $data['template'] : "moderation.moderator.accept";
        // Email - User
        $this->sendMail
        (
            array($noreply->getEmail() => $this->getParameter('sign', 'fhm_mailer')),
            $data['user']->getEmail(),
            $this->get('translator')->trans('place.email.moderation.moderator.accept.object', array(), 'ProjectPlaceBundle'),
            $this->renderMail($data, 'Place'),
            'place > moderation > moderator > accept'
        );
    }

    /**
     * Place - Moderation - Moderator - Refuse
     */
    public function placeModerationModeratorRefuse($data)
    {
        $dm               = $this->container->get('doctrine.odm.mongodb.document_manager');
        $noreply          = $dm->getRepository("FhmUserBundle:User")->getUserByEmail($this->getParameter('noreply', 'fhm_mailer'));
        $data['template'] = isset($data['template']) ? $data['template'] : "moderation.moderator.refuse";
        // Email - User
        $this->sendMail
        (
            array($noreply->getEmail() => $this->getParameter('sign', 'fhm_mailer')),
            $data['user']->getEmail(),
            $this->get('translator')->trans('place.email.moderation.moderator.refuse.object', array(), 'ProjectPlaceBundle'),
            $this->renderMail($data, 'Place'),
            'place > moderation > moderator > refuse'
        );
    }

    /**
     * Place - Moderation - Moderator - Cancel
     */
    public function placeModerationModeratorCancel($data)
    {
        $dm               = $this->container->get('doctrine.odm.mongodb.document_manager');
        $noreply          = $dm->getRepository("FhmUserBundle:User")->getUserByEmail($this->getParameter('noreply', 'fhm_mailer'));
        $data['template'] = isset($data['template']) ? $data['template'] : "moderation.moderator.cancel";
        // Email - User
        $this->sendMail
        (
            array($noreply->getEmail() => $this->getParameter('sign', 'fhm_mailer')),
            $data['user']->getEmail(),
            $this->get('translator')->trans('place.email.moderation.moderator.cancel.object', array(), 'ProjectPlaceBundle'),
            $this->renderMail($data, 'Place'),
            'place > moderation > moderator > cancel'
        );
    }
} 