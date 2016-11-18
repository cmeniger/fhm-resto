<?php
namespace Fhm\ContactBundle\Controller;

use Fhm\FhmBundle\Controller\RefApiController as FhmController;
use Fhm\ContactBundle\Document\Contact;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * @Route("/api/contact")
 */
class ApiController extends FhmController
{
    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct('Fhm', 'Contact', 'contact');
    }

    /**
     * @Route
     * (
     *      path="/",
     *      name="fhm_api_contact"
     * )
     * @Template("::FhmContact/Api/index.html.twig")
     */
    public function indexAction()
    {
        return parent::indexAction();
    }

    /**
     * @Route
     * (
     *      path="/autocomplete/",
     *      name="fhm_api_contact_autocomplete"
     * )
     * @Template("::FhmContact/Api/autocomplete.html.twig")
     */
    public function autocompleteAction(Request $request)
    {
        return parent::autocompleteAction($request);
    }

    /**
     * @Route
     * (
     *      path="/form/{id}",
     *      name="fhm_api_contact_form"
     * )
     */
    public function formAction(Request $request, $id)
    {
        $document = $this->dmRepository()->find($id);
        $instance = $this->instanceData($document);
        if($document == "")
        {
            throw $this->createNotFoundException($this->get('translator')->trans($this->translation[1] . '.error.unknown', array(), $this->translation[0]));
        }
        // ERROR - Forbidden
        elseif(!$instance->user->admin && ($document->getDelete() || !$document->getActive()))
        {
            throw new HttpException(403, $this->get('translator')->trans($this->translation[1] . '.error.forbidden', array(), $this->translation[0]));
        }
        $template     = $this->get('templating')->exists("::FhmContact/Template/form." . $document->getFormTemplate() . ".html.twig") ? $document->getFormTemplate() : "default";
        $classType    = "\\Fhm\\ContactBundle\\Form\\Type\\Template\\" . ucfirst($template) . "Type";
        $classHandler = "\\Fhm\\ContactBundle\\Form\\Handler\\Api\\FormHandler";
        $form         = $this->createForm(new $classType($instance), null);
        $handler      = new $classHandler($form, $request);
        $process      = $handler->process();
        if($process)
        {
            $data = $request->get($form->getName());
            unset($data['_token']);
            // Name
            $name = '';
            $name = (isset($data['name'])) ? $data['name'] : $name;
            $name = ($name == '' && isset($data['firstname']) && isset($data['lastname'])) ? $data['firstname'] . ' ' . $data['lastname'] : $name;
            $name = ($name == '' && isset($data['email'])) ? $data['email'] : $name;
            $name = ($name == '') ? $document->getName() : $name;
            // Email
            $email = '';
            $email = (isset($data['email'])) ? $data['email'] : $email;
            $email = ($email == '') ? $this->getUser()->getEmailCanonical() : $email;
            // Message
            $message = new \Fhm\ContactBundle\Document\ContactMessage();
            $message->setName($name);
            $message->setEmail($email);
            $message->setField($data);
            // Contact
            $document->addMessage($message);
            $this->dmPersist($message);
            $this->dmPersist($document);
            // Email
            $this->container->get('fhm_mail')->contact
            (
                array
                (
                    'message'  => $message,
                    'template' => $document->getFormTemplate()
                )
            );
            // Message
            $this->get('session')->getFlashBag()->add('contact-' . $id, $this->get('translator')->trans($this->translation[1] . '.front.index.flash.ok', array(), $this->translation[0]));
            // Reset form
            $form = $this->createForm(new $classType($instance), null);
        }

        return new Response(
            $this->renderView(
                "::FhmContact/Template/form." . $template . ".html.twig",
                array(
                    'document' => $document,
                    'instance' => $instance,
                    'form'     => $form->createView()
                )
            )
        );
    }

    /**
     * @Route
     * (
     *      path="/email",
     *      name="fhm_api_contact_email"
     * )
     */
    public function emailAction(Request $request)
    {
        $datas    = $request->get('FhmContact');
        $document = $this->dmRepository()->find($datas['id']);
        if($document)
        {
            // Message
            $message = new \Fhm\ContactBundle\Document\Message();
            $message->setFirstname($datas['firstname']);
            $message->setLastname($datas['lastname']);
            $message->setEmail($datas['email']);
            $message->setPhone($datas['phone']);
            $message->setContent($datas['content']);
            // Contact
            $document->addMessage($message);
            $this->dmPersist($message);
            $this->dmPersist($document);
            // Email
            $this->container->get('fhm_mail')->contact
            (
                array
                (
                    'message'  => $message,
                    'template' => $document->getFormTemplate()
                )
            );
            // Message
            $this->get('session')->getFlashBag()->add('notice', $this->get('translator')->trans($this->translation[1] . '.front.index.flash.ok', array(), $this->translation[0]));
        }

        return $this->redirect($this->generateUrl('project_home'));
    }
}