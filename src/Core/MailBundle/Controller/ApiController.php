<?php
namespace Core\MailBundle\Controller;

use Core\FhmBundle\Controller\RefApiController as FhmController;
use Core\MailBundle\Document\Mail;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * @Route("/api/mail")
 */
class ApiController extends FhmController
{
    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct('Fhm', 'Mail', 'mail');
    }

    /**
     * @Route
     * (
     *      path="/",
     *      name="fhm_api_mail"
     * )
     * @Template("::FhmMail/Api/index.html.twig")
     */
    public function indexAction()
    {
        return parent::indexAction();
    }

    /**
     * @Route
     * (
     *      path="/admin/test",
     *      name="fhm_api_mail_admin_test"
     * )
     */
    public function adminTestAction()
    {
        $this->get('session')->getFlashBag()->add('notice', $this->get('translator')->trans("mail.test.flash.ok", array("%email%" => $this->getParameter('admin', 'fhm_mailer')), $this->translation[0]));
        $this->container->get('fhm_mail')->AdminTest();

        return $this->redirect($this->getLastRoute());
    }

    /**
     * @Route
     * (
     *      path="/autocomplete/",
     *      name="fhm_api_mail_autocomplete"
     * )
     * @Template("::FhmMail/Api/autocomplete.html.twig")
     */
    public function autocompleteAction(Request $request)
    {
        return parent::autocompleteAction($request);
    }

    /**
     * @Route
     * (
     *      path="/user/register",
     *      name="fhm_api_mail_user_register"
     * )
     * @Template("::FhmMail/Template/User/register.html.twig")
     */
    public function userRegisterAction()
    {
        return array
        (
            "user"             => $this->getUser(),
            "urlConfirm"       => $this->container->get('router')->generate('fos_user_registration_confirm', array('token' => md5($this->getUser()->getUsername())), true),
            "version"          => "mail",
            "server_http_host" => $this->getParameter('host', 'fhm_mailer')
        );
    }

    /**
     * @Route
     * (
     *      path="/user/reset",
     *      name="fhm_api_mail_user_reset"
     * )
     * @Template("::FhmMail/Template/User/reset.html.twig")
     */
    public function userResetAction()
    {
        return array
        (
            "user"             => $this->getUser(),
            "urlConfirm"       => $this->container->get('router')->generate('fos_user_registration_confirm', array('token' => md5($this->getUser()->getUsername())), true),
            "version"          => "mail",
            "server_http_host" => $this->getParameter('host', 'fhm_mailer')
        );
    }

    /**
     * @Route
     * (
     *      path="/contact/default",
     *      name="fhm_api_mail_contact_default"
     * )
     * @Template("::FhmMail/Template/Contact/default.html.twig")
     */
    public function contactDefaultAction()
    {
        $message = new \Fhm\ContactBundle\Document\ContactMessage();
        $message->setField(array(
            'firstname' => 'John',
            'lastname'  => 'Do',
            'email'     => 'john.do@domain.com',
            'phone'     => '0123456789',
            'content'   => 'Praesent nec nisl a purus blandit viverra. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Sed aliquam, nisi quis porttitor congue, elit erat euismod orci, ac placerat dolor lectus quis orci. Sed a libero. Praesent venenatis metus at tortor pulvinar varius. Suspendisse potenti.'
        ));
        $contact = new \Fhm\ContactBundle\Document\Contact();
        $contact->setName("Contact test");
        $contact->addMessage($message);

        return array
        (
            "message"          => $message,
            "version"          => "mail",
            "server_http_host" => $this->getParameter('host', 'fhm_mailer')
        );
    }
}