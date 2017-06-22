<?php

namespace Fhm\ContactBundle\Controller;

use Fhm\FhmBundle\Controller\RefApiController as FhmController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * @Route("/api/contact")
 * ------------------------------------
 * Class ApiController
 *
 * @package Fhm\ContactBundle\Controller
 */
class ApiController extends FhmController
{
    /**
     * ApiController constructor.
     */
    public function __construct()
    {
        self::$repository  = "FhmContactBundle:Contact";
        self::$source      = "fhm";
        self::$domain      = "FhmContactBundle";
        self::$translation = "contact";
        self::$route       = "contact";
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
     *      path="/detail/{template}/{id}",
     *      name="fhm_api_contact_detail",
     *      requirements={"id"=".+"},
     *      defaults={"template"="default"}
     * )
     */
    public function detailAction($template, $id)
    {
        if($id == 'demo')
        {
            return new Response(
                $this->renderView(
                    "::FhmContact/Template/" . $template . ".html.twig",
                    array(
                        'object' => null,
                        'demo'   => true
                    )
                )
            );
        }
        $object = $this->get('fhm_tools')->dmRepository(self::$repository)->getById($id);
        $object = ($object) ?: $this->get('fhm_tools')->dmRepository(self::$repository)->getByAlias($id);
        $object = ($object) ?: $this->get('fhm_tools')->dmRepository(self::$repository)->getByName($id);
        // ERROR - unknown
        if($object == "")
        {
            throw $this->createNotFoundException(
                $this->trans('slider.item.error.unknown', array(), 'FhmSliderBundle')
            );
        } // ERROR - Forbidden
        elseif($object->getDelete() || !$object->getActive())
        {
            throw new HttpException(
                403, $this->trans('contact.error.forbidden', array(), self::$domain)
            );
        }

        return new Response(
            $this->renderView(
                "::FhmContact/Template/" . $template . ".html.twig",
                array(
                    'object' => $object
                )
            )
        );
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
        $object = $this->get('fhm_tools')->dmRepository(self::$repository)->find($id);
        if($object == "")
        {
            throw $this->createNotFoundException($this->trans(self::$translation . '.error.unknown'));
        } // ERROR - Forbidden
        elseif(!$this->getUser()->hasRole('ROLE_ADMIN') && ($object->getDelete() || !$object->getActive()))
        {
            throw new HttpException(403, $this->trans(self::$translation . '.error.forbidden'));
        }
        $template     = $this->get('templating')->exists(
            "::FhmContact/Template/form." . $object->getFormTemplate() . ".html.twig"
        ) ? $object->getFormTemplate() : "default";
        $classType    = "\\Fhm\\ContactBundle\\Form\\Type\\Template\\" . ucfirst($template) . "Type";
        $classHandler = "\\Fhm\\ContactBundle\\Form\\Handler\\Api\\FormHandler";
        $form         = $this->createForm(
            $classType,
            null
            ,
            ['data_class' => $this->get('fhm.object.manager')->getCurrentModelName(self::$repository)]
        );
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
            $name = ($name == '') ? $object->getName() : $name;
            // Email
            $email = '';
            $email = (isset($data['email'])) ? $data['email'] : $email;
            $email = ($email == '') ? $this->getUser()->getEmailCanonical() : $email;
            // Message
            $messageClass = $this->get('fhm.object.manager')->getCurrentModelName('FhmContactBundle:Message');
            $message      = new $messageClass;
            $message->setName($name);
            $message->setEmail($email);
            $message->setField($data);
            // Contact
            $object->addMessage($message);
            $this->get('fhm_tools')->dmPersist($message);
            $this->get('fhm_tools')->dmPersist($object);
            // Email
            $this->get('fhm_mail')->contact(
                array(
                    'message'  => $message,
                    'template' => $object->getFormTemplate(),
                )
            );
            // Message
            $this->get('session')->getFlashBag()->add(
                'contact-' . $id,
                $this->trans(self::$translation . '.front.index.flash.ok')
            );
            // Reset form
            $form = $this->createForm($classType);
        }

        return new Response(
            $this->renderView(
                "::FhmContact/Template/form." . $template . ".html.twig",
                array(
                    'object' => $object,
                    'form'   => $form->createView(),
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
        $datas  = $request->get('FhmContact');
        $object = $this->get('fhm_tools')->dmRepository(self::$repository)->find($datas['id']);
        if($object)
        {
            // Message
            $messageClass = $this->get('fhm.object.manager')->getCurrentModelName('FhmContactBundle:Message');
            $message      = new $messageClass;
            $message->setFirstname($datas['firstname']);
            $message->setLastname($datas['lastname']);
            $message->setEmail($datas['email']);
            $message->setPhone($datas['phone']);
            $message->setContent($datas['content']);
            // Contact
            $object->addMessage($message);
            $this->get('fhm_tools')->dmPersist($message);
            $this->get('fhm_tools')->dmPersist($object);
            // Email
            $this->get('fhm_mail')->contact(
                array(
                    'message'  => $message,
                    'template' => $object->getFormTemplate(),
                )
            );
            // Message
            $this->get('session')->getFlashBag()->add(
                'notice',
                $this->trans(self::$translation . '.front.index.flash.ok')
            );
        }

        return $this->redirect($this->getUrl('project_home'));
    }
}