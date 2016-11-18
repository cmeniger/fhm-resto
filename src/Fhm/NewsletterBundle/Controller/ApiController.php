<?php
namespace Fhm\NewsletterBundle\Controller;

use Fhm\FhmBundle\Controller\RefApiController as FhmController;
use Fhm\NewsletterBundle\Document\Newsletter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * @Route("/api/newsletter")
 */
class ApiController extends FhmController
{
    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct('Fhm', 'Newsletter', 'newsletter');
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
        // ERROR - Unknown route
        if(!$this->routeExists($this->source . '_' . $this->route) || !$this->routeExists($this->source . '_' . $this->route . '_create'))
        {
            throw $this->createNotFoundException($this->get('translator')->trans('fhm.error.route', array(), 'FhmFhmBundle'));
        }
        $document     = $this->document;
        $instance     = $this->instanceData();
        $classType    = $this->form->type->create;
        $classHandler = $this->form->handler->create;
        $form         = $this->createForm(new $classType($instance, $document), $document);
        $handler      = new $classHandler($form, $request);
        $process      = $handler->process();
        if($process)
        {
            $data = $request->get($form->getName());
            // Persist
            $document->setUserCreate($this->getUser());
            $document->setAlias($this->getAlias($document->getId(), $document->getName()));
            $document->addGrouping($instance->grouping->current);
            $document->setActive(true);
            $this->dmPersist($document);
            // Response
            if(!$request->isXmlHttpRequest())
            {
                return $this->redirect($this->generateUrl('project_home'));
            }
            else
            {
                unset($document);
                unset($form);
                $document = new \Fhm\NewsletterBundle\Document\Newsletter();
                $form     = $this->createForm(new $classType($instance, $document), $document);
            }
        }

        return array(
            'form'        => $form->createView(),
            'instance'    => $this->instanceData(),
            'process'     => $process,
            'breadcrumbs' => array(
                array(
                    'link' => $this->get('router')->generate('project_home'),
                    'text' => $this->get('translator')->trans('project.home.breadcrumb', array(), 'ProjectDefaultBundle'),
                ),
                array(
                    'link' => $this->get('router')->generate($this->source . '_' . $this->route),
                    'text' => $this->get('translator')->trans($this->translation[1] . '.front.index.breadcrumb', array(), $this->translation[0]),
                ),
                array(
                    'link'    => $this->get('router')->generate($this->source . '_' . $this->route . '_create'),
                    'text'    => $this->get('translator')->trans($this->translation[1] . '.front.create.breadcrumb', array(), $this->translation[0]),
                    'current' => true
                )
            )
        );
    }
}