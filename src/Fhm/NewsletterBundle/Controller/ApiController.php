<?php

namespace Fhm\NewsletterBundle\Controller;

use Fhm\FhmBundle\Controller\RefApiController as FhmController;
use Fhm\FhmBundle\Form\Handler\Admin\CreateHandler;
use Fhm\NewsletterBundle\Document\Newsletter;
use Fhm\NewsletterBundle\Form\Type\Api\CreateType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * @Route("/api/newsletter")
 * ---------------------------------------
 * Class ApiController
 *
 * @package Fhm\NewsletterBundle\Controller
 */
class ApiController extends FhmController
{
    /**
     * ApiController constructor.
     */
    public function __construct()
    {
        self::$repository  = "FhmNewsletterBundle:Newsletter";
        self::$source      = "fhm";
        self::$domain      = "FhmNewsletterBundle";
        self::$translation = "newsletter";
        self::$class       = Newsletter::class;
        self::$route       = "newsletter";
    }

    /**
     * @Route
     * (
     *      path="/",
     *      name="fhm_api_newsletter"
     * )
     * @Template("::FhmNewsletter/Api/index.html.twig")
     */
    public function indexAction()
    {
        return parent::indexAction();
    }

    /**
     * @Route
     * (
     *      path="/autocomplete/",
     *      name="fhm_api_newsletter_autocomplete"
     * )
     * @Template("::FhmNewsletter/Api/autocomplete.html.twig")
     */
    public function autocompleteAction(Request $request)
    {
        return parent::autocompleteAction($request);
    }

    /**
     * @Route
     * (
     *      path="/embed",
     *      name="fhm_api_newsletter_embed"
     * )
     * @Template("::FhmNewsletter/Template/embed.html.twig")
     */
    public function embedAction(Request $request)
    {
        $document            = new self::$class;
        self::$form          = new \stdClass();
        self::$form->type    = CreateType::class;
        self::$form->handler = CreateHandler::class;
        $classHandler        = self::$form->handler;
        $form                = $this->createForm(self::$form->type, $document);
        $handler             = new $classHandler($form, $request);
        $process             = $handler->process();
        if($process)
        {
            // Persist
            $document->setUserCreate($this->getUser());
            $document->setAlias($this->get('fhm_tools')->getAlias($document->getId(), $document->getName(), self::$repository));
            $document->setActive(true);
            $this->get('fhm_tools')->dmPersist($document);
            // Response
            if(!$request->isXmlHttpRequest())
            {
                return $this->redirect($this->getUrl('project_home'));
            }
            else
            {
                unset($document);
                unset($form);
                $document = new self::$class;
                $form     = $this->createForm(self::$form->type, $document);
            }
        }

        return array(
            'form'    => $form->createView(),
            'process' => $process
        );
    }
}