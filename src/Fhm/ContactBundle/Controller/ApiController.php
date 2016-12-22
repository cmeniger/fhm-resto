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
 * ------------------------------------
 * Class ApiController
 * @package Fhm\ContactBundle\Controller
 */
class ApiController extends FhmController
{
    /**
     * ApiController constructor.
     */
    public function __construct()
    {
        self::$repository = "FhmContactBundle:Contact";
        self::$source = "fhm";
        self::$domain = "FhmContactBundle";
        self::$translation = "contact";
        self::$class = Contact::class;
        self::$route = "contact";
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
        $document = $this->get('fhm_tools')->dmRepository(self::$repository)->find($id);
        if ($document == "") {
            throw $this->createNotFoundException($this->trans(self::$translation.'.error.unknown'));
        } // ERROR - Forbidden
        elseif (!$this->getUser()->hasRole('ROLE_ADMIN') && ($document->getDelete() || !$document->getActive())) {
            throw new HttpException(403, $this->trans(self::$translation.'.error.forbidden'));
        }
        $template = $this->get('templating')->exists(
            "::FhmContact/Template/form.".$document->getFormTemplate().".html.twig"
        ) ? $document->getFormTemplate() : "default";
        $classType = "\\Fhm\\ContactBundle\\Form\\Type\\Template\\".ucfirst($template)."Type";
        $classHandler = "\\Fhm\\ContactBundle\\Form\\Handler\\Api\\FormHandler";
        $form = $this->createForm($classType);
        $handler = new $classHandler($form, $request);
        $process = $handler->process();
        if ($process) {
            $data = $request->get($form->getName());
            unset($data['_token']);
            // Name
            $name = '';
            $name = (isset($data['name'])) ? $data['name'] : $name;
            $name = ($name == '' && isset($data['firstname']) && isset($data['lastname'])) ? $data['firstname'].' '.$data['lastname'] : $name;
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
            $this->get('fhm_tools')->dmPersist($message);
            $this->get('fhm_tools')->dmPersist($document);
            // Email
            $this->get('fhm_mail')->contact(
                array(
                    'message' => $message,
                    'template' => $document->getFormTemplate(),
                )
            );
            // Message
            $this->get('session')->getFlashBag()->add(
                'contact-'.$id,
                $this->trans(self::$translation.'.front.index.flash.ok')
            );
            // Reset form
            $form = $this->createForm($classType);
        }

        return new Response(
            $this->renderView(
                "::FhmContact/Template/form.".$template.".html.twig",
                array(
                    'document' => $document,
                    'form' => $form->createView(),
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
        $datas = $request->get('FhmContact');
        $document = $this->get('fhm_tools')->dmRepository(self::$repository)->find($datas['id']);
        if ($document) {
            // Message
            $message = new \Fhm\ContactBundle\Document\Message();
            $message->setFirstname($datas['firstname']);
            $message->setLastname($datas['lastname']);
            $message->setEmail($datas['email']);
            $message->setPhone($datas['phone']);
            $message->setContent($datas['content']);
            // Contact
            $document->addMessage($message);
            $this->get('fhm_tools')->dmPersist($message);
            $this->get('fhm_tools')->dmPersist($document);
            // Email
            $this->get('fhm_mail')->contact(
                array(
                    'message' => $message,
                    'template' => $document->getFormTemplate(),
                )
            );
            // Message
            $this->get('session')->getFlashBag()->add(
                'notice',
                $this->trans(self::$translation.'.front.index.flash.ok')
            );
        }

        return $this->redirect($this->getUrl('project_home'));
    }
}