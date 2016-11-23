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
 * @Route("/api/newsletter", service="fhm_newsletter_controller_api")
 */
class ApiController extends FhmController
{
    /**
     * ApiController constructor.
     *
     * @param \Fhm\FhmBundle\Services\Tools $tools
     */
    public function __construct(\Fhm\FhmBundle\Services\Tools $tools)
    {
        $this->setFhmTools($tools);
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
        if(!$this->fhm_tools->routeExists($this->source . '_' . $this->route) || !$this->fhm_tools->routeExists($this->source . '_' . $this->route . '_create'))
        {
            throw $this->createNotFoundException($this->fhm_tools->trans('fhm.error.route', array(), 'FhmFhmBundle'));
        }
        $document     = $this->document;
        $instance     = $this->fhm_tools->instanceData();
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
            $document->setAlias($this->fhm_tools->getAlias($document->getId(), $document->getName()));
            $document->addGrouping($instance->grouping->current);
            $document->setActive(true);
            $this->fhm_tools->dmPersist($document);
            // Response
            if(!$request->isXmlHttpRequest())
            {
                return $this->redirect($this->fhm_tools->getUrl('project_home'));
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
            'instance'    => $this->fhm_tools->instanceData(),
            'process'     => $process,
            'breadcrumbs' => array(
                array(
                    'link' => $this->fhm_tools->getUrl('project_home'),
                    'text' => $this->fhm_tools->trans('project.home.breadcrumb', array(), 'ProjectDefaultBundle'),
                ),
                array(
                    'link' => $this->fhm_tools->getUrl($this->source . '_' . $this->route),
                    'text' => $this->fhm_tools->trans('.front.index.breadcrumb'),
                ),
                array(
                    'link'    => $this->fhm_tools->getUrl($this->source . '_' . $this->route . '_create'),
                    'text'    => $this->fhm_tools->trans('.front.create.breadcrumb'),
                    'current' => true
                )
            )
        );
    }
}