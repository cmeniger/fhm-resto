<?php
namespace Core\MailBundle\Services;

use Symfony\Component\Templating\EngineInterface;

class Mailer
{
    protected $mailer;
    protected $templating;
    protected $dm;
    protected $session;
    protected $translator;
    protected $parameter;

    public function __construct(\Swift_Mailer $mailer, EngineInterface $templating, $dm, $session, $translator, $fhmMailer)
    {
        $this->mailer     = $mailer;
        $this->templating = $templating;
        $this->dm         = $dm;
        $this->session    = $session;
        $this->translator = $translator;
        $this->parameter  = $fhmMailer;
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
        if($this->parameter['enable'])
        {
            $transport = \Swift_SmtpTransport::newInstance($this->parameter['fhm_host'], $this->parameter['fhm_port'])
                ->setUsername($this->parameter['fhm_user'])
                ->setPassword($this->parameter['fhm_password'])
                ->setEncryption($this->parameter['fhm_encryption']);
            $mailer    = \Swift_Mailer::newInstance($transport);
            $message   = \Swift_Message::newInstance()
                ->setSubject($subject)
                ->setFrom($fromEmail)
                ->setTo($toEmail)
                ->setBody($body)
                ->setContentType('text/html');
            $mailer->send($message);
            // Save mail
            $document = new \Core\MailBundle\Document\Mail();
            $document
                ->setType('mail')
                ->setModel($model)
                ->setFrom(array_keys($fromEmail)[0])
                ->setTo($toEmail)
                ->setSubject($subject)
                ->setBody($body);
            $this->dm->persist($document);
            $this->dm->flush();
        }
        else
        {
            $this->session->getFlashBag()->add('notice', 'mail.flash.disable', array(), 'FhmMailBundle');
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
        $thread = $this->get('fos_message.composer')->newThread();
        $thread
            ->addRecipient($toUser)
            ->setSender($fromUser)
            ->setSubject($subject)
            ->setBody($body);
        $sender = $this->get('fos_message.sender');
        $sender->send($thread->getMessage());
        // Save mail
        $document = new \Core\MailBundle\Document\Mail();
        $document
            ->setType('message')
            ->setModel($model)
            ->setFrom($fromUser->getEmailCanonical())
            ->setTo($toUser->getEmailCanonical())
            ->setSubject($subject)
            ->setBody($body);
        $this->dm->persist($document);
        $this->dm->flush();
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
                    'server_http_host' => $this->parameter['fhm_host'],
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
        $noreply = $this->dm->getRepository("FhmUserBundle:User")->getUserByEmail($this->parameter['noreply']);
        // Email - Admin
        $this->sendMail
        (
            array($noreply->getEmail() => $this->parameter['sign']),
            $this->parameter['admin'],
            $this->parameter['project'] . " email test",
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
        // var_dump($data); exit;
        // Email
        $this->sendMail
        (
            array($this->parameter['contact'] => $this->parameter['sign']),
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
        $noreply           = $this->dm->getRepository("CoreUserBundle:User")->getUserByEmail($this->parameter['noreply']);
        $data["send_mail"] = (isset($data["send_mail"])) ? $data["send_mail"] : true;
        // Email - User

        if($data["send_mail"])
        {
            $this->sendMail
            (
                array($noreply->getEmail() => $this->parameter['sign']),
                $data['user']->getEmail(),
                "Bienvenue sur " . $this->parameter['project'],
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
        $noreply           = $this->dm->getRepository("CoreUserBundle:User")->getUserByEmail($this->parameter['noreply']);
        $data["send_mail"] = (isset($data["send_mail"])) ? $data["send_mail"] : true;
        // Email - User
        if($data["send_mail"])
        {
            $this->sendMail
            (
                array($noreply->getEmail() => $this->parameter['sign']),
                $data['user']->getEmail(),
                "Réinitialiser mon mot de passe " . $this->parameter['project'],
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
        $noreply          = $this->dm->getRepository("CoreUserBundle:User")->getUserByEmail($this->parameter['noreply']);
        $data['template'] = isset($data['template']) ? $data['template'] : "default";
        // Email - Contact
        $this->sendMail
        (
            array($noreply->getEmail() => $this->parameter['sign']),
            $this->parameter['contact'],
            "[CONTACT][" . $data['message']->getContact()->getName() . "] " . $this->parameter['project'],
            $this->renderMail($data, 'Contact'),
            'contact > ' . $data['template']
        );
        // Email - User
        $this->sendMail
        (
            array($noreply->getEmail() => $this->parameter['sign']),
            $data['message']->getEmail(),
            $this->parameter['project'] . " contact",
            $this->renderMail($data, 'Contact'),
            'contact > ' . $data['template']
        );
    }

    /**
     * Place - Moderation - Create
     */
    public function placeModerationCreate($data)
    {
        $noreply          = $this->dm->getRepository("CoreUserBundle:User")->getUserByEmail($this->parameter['noreply']);
        $data['template'] = isset($data['template']) ? $data['template'] : "moderation.create";
        // Email - Admin
        $this->sendMail
        (
            array($noreply->getEmail() => $this->parameter['sign']),
            $this->parameter['admin'],
            $this->translator->trans('place.email.moderation.create.admin.object', array(), 'ProjectPlaceBundle'),
            $this->renderMail(array_merge($data, array('type' => 'admin')), 'Place'),
            'place > moderation > create > admin'
        );
        // Email - User
        $this->sendMail
        (
            array($noreply->getEmail() => $this->parameter['sign']),
            $data['user']->getEmail(),
            $this->translator->trans('place.email.moderation.create.user.object', array(), 'ProjectPlaceBundle'),
            $this->renderMail(array_merge($data, array('type' => 'user')), 'Place'),
            'place > moderation > create > user'
        );
    }

    /**
     * Place - Moderation - Create - Accept
     */
    public function placeModerationCreateAccept($data)
    {
        $noreply          = $this->dm->getRepository("CoreUserBundle:User")->getUserByEmail($this->parameter['noreply']);
        $data['template'] = isset($data['template']) ? $data['template'] : "moderation.create.accept";
        // Email - User
        $this->sendMail
        (
            array($noreply->getEmail() => $this->parameter['sign']),
            $data['user']->getEmail(),
            $this->translator->trans('place.email.moderation.create.accept.object', array(), 'ProjectPlaceBundle'),
            $this->renderMail($data, 'Place'),
            'place > moderation > create > accept'
        );
    }

    /**
     * Place - Moderation - Create - Refuse
     */
    public function placeModerationCreateRefuse($data)
    {
        $noreply          = $this->dm->getRepository("CoreUserBundle:User")->getUserByEmail($this->parameter['noreply']);
        $data['template'] = isset($data['template']) ? $data['template'] : "moderation.create.refuse";
        // Email - User
        $this->sendMail
        (
            array($noreply->getEmail() => $this->parameter['sign']),
            $data['user']->getEmail(),
            $this->translator->trans('place.email.moderation.create.refuse.object', array(), 'ProjectPlaceBundle'),
            $this->renderMail($data, 'Place'),
            'place > moderation > create > refuse'
        );
    }

    /**
     * Place - Moderation - Create - Cancel
     */
    public function placeModerationCreateCancel($data)
    {
        $noreply          = $this->dm->getRepository("CoreUserBundle:User")->getUserByEmail($this->parameter['noreply']);
        $data['template'] = isset($data['template']) ? $data['template'] : "moderation.create.cancel";
        // Email - User
        $this->sendMail
        (
            array($noreply->getEmail() => $this->parameter['sign']),
            $data['user']->getEmail(),
            $this->translator->trans('place.email.moderation.create.cancel.object', array(), 'ProjectPlaceBundle'),
            $this->renderMail($data, 'Place'),
            'place > moderation > create > cancel'
        );
    }

    /**
     * Place - Moderation - Moderator
     */
    public function placeModerationModerator($data)
    {
        $noreply          = $this->dm->getRepository("CoreUserBundle:User")->getUserByEmail($this->parameter['noreply']);
        $data['template'] = isset($data['template']) ? $data['template'] : "moderation.moderator";
        // Email - Admin
        $this->sendMail
        (
            array($noreply->getEmail() => $this->parameter['sign']),
            $this->parameter['admin'],
            $this->translator->trans('place.email.moderation.moderator.admin.object', array(), 'ProjectPlaceBundle'),
            $this->renderMail(array_merge($data, array('type' => 'admin')), 'Place'),
            'place > moderation > moderator > admin'
        );
        // Email - User
        $this->sendMail
        (
            array($noreply->getEmail() => $this->parameter['sign']),
            $data['user']->getEmail(),
            $this->translator->trans('place.email.moderation.moderator.user.object', array(), 'ProjectPlaceBundle'),
            $this->renderMail(array_merge($data, array('type' => 'user')), 'Place'),
            'place > moderation > moderator > user'
        );
    }

    /**
     * Place - Moderation - Moderator - Accept
     */
    public function placeModerationModeratorAccept($data)
    {
        $noreply          = $this->dm->getRepository("CoreUserBundle:User")->getUserByEmail($this->parameter['noreply']);
        $data['template'] = isset($data['template']) ? $data['template'] : "moderation.moderator.accept";
        // Email - User
        $this->sendMail
        (
            array($noreply->getEmail() => $this->parameter['sign']),
            $data['user']->getEmail(),
            $this->translator->trans('place.email.moderation.moderator.accept.object', array(), 'ProjectPlaceBundle'),
            $this->renderMail($data, 'Place'),
            'place > moderation > moderator > accept'
        );
    }

    /**
     * Place - Moderation - Moderator - Refuse
     */
    public function placeModerationModeratorRefuse($data)
    {
        $noreply          = $this->dm->getRepository("CoreUserBundle:User")->getUserByEmail($this->parameter['noreply']);
        $data['template'] = isset($data['template']) ? $data['template'] : "moderation.moderator.refuse";
        // Email - User
        $this->sendMail
        (
            array($noreply->getEmail() => $this->parameter['sign']),
            $data['user']->getEmail(),
            $this->translator->trans('place.email.moderation.moderator.refuse.object', array(), 'ProjectPlaceBundle'),
            $this->renderMail($data, 'Place'),
            'place > moderation > moderator > refuse'
        );
    }

    /**
     * Place - Moderation - Moderator - Cancel
     */
    public function placeModerationModeratorCancel($data)
    {
        $noreply          = $this->dm->getRepository("CoreUserBundle:User")->getUserByEmail($this->parameter['noreply']);
        $data['template'] = isset($data['template']) ? $data['template'] : "moderation.moderator.cancel";
        // Email - User
        $this->sendMail
        (
            array($noreply->getEmail() => $this->parameter['sign']),
            $data['user']->getEmail(),
            $this->translator->trans('place.email.moderation.moderator.cancel.object', array(), 'ProjectPlaceBundle'),
            $this->renderMail($data, 'Place'),
            'place > moderation > moderator > cancel'
        );
    }
} 