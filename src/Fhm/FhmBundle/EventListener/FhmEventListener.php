<?php
namespace Fhm\FhmBundle\EventListener;

use Symfony\Component\DependencyInjection\ContainerAware;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpKernel\Event\GetResponseForControllerResultEvent;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Symfony\Component\HttpKernel\Event\FinishRequestEvent;
use Symfony\Component\HttpKernel\Event\PostResponseEvent;
use Symfony\Component\HttpKernel\HttpKernel;

class FhmEventListener
{

    protected $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * @param GetResponseEvent $event
     *
     * @return bool
     */
    public function onKernelRequest(GetResponseEvent $event)
    {
        // Twig global
        $parameters = $this->container->getParameter("fhm_fhm");
        $this->container->get($parameters["grouping"])->loadTwigGlobal();
        $this->container->get('project_twig_global')->load();
        // Locale
        if($event->getRequest()->hasPreviousSession())
        {
            if($locale = $event->getRequest()->attributes->get('_locale'))
            {
                $event->getRequest()->getSession()->set('_locale', $locale);
            }
            $event->getRequest()->setLocale($event->getRequest()->getSession()->get('_locale'));
        }
        // Construct / Maintenance
        if(HttpKernel::MASTER_REQUEST != $event->getRequestType() || $event->getRequest()->isXmlHttpRequest())
        {
            return false;
        }
        $parameters  = $this->container->getParameter("project");
        $maintenance = isset($parameters['maintenance']) ? $parameters['maintenance'] : false;
        $construct   = isset($parameters['construct']) ? $parameters['construct'] : false;
        $firewall    = isset($parameters['firewall']) ? $parameters['firewall'] : array();
        $date        = isset($parameters['date']) ? new \DateTime($parameters['date']) : '';
        $route       = $event->getRequest()->attributes->get('_route');
        $authorized  = false;
        $authorized  = in_array($route, $firewall) ? true : $authorized;
        $authorized  = $this->container->get('security.context')->isGranted('ROLE_ADMIN') ? true : $authorized;
        $authorized  = in_array($this->container->get('kernel')->getEnvironment(), array('test', 'dev')) ? true : $authorized;
        if($construct && !$authorized)
        {
            $event->setResponse(new Response($this->container->get('templating')->render('::ProjectDefault/Template/construct.html.twig', array('date' => $date, 'site' => $this->container->get($this->container->getParameter("fhm_fhm")["grouping"])->getSite())), 503));
            $event->stopPropagation();
        }
        elseif($maintenance && !$authorized)
        {
            $event->setResponse(new Response($this->container->get('templating')->render('::ProjectDefault/Template/maintenance.html.twig', array('date' => $date, 'site' => $this->container->get($this->container->getParameter("fhm_fhm")["grouping"])->getSite())), 503));
            $event->stopPropagation();
        }

        return false;
    }

    /**
     * @param GetResponseForControllerResultEvent $event
     *
     * @return bool
     */
    public function onKernelView(GetResponseForControllerResultEvent $event)
    {
        return false;
    }

    /**
     * @param FilterControllerEvent $event
     *
     * @return bool
     */
    public function onKernelController(FilterControllerEvent $event)
    {
        return false;
    }

    /**
     * @param FilterResponseEvent $event
     *
     * @return bool
     */
    public function onKernelResponse(FilterResponseEvent $event)
    {
        return false;
    }

    /**
     * @param FinishRequestEvent $event
     *
     * @return bool
     */
    public function onKernelFinishRequest(FinishRequestEvent $event)
    {
        return false;
    }

    /**
     * @param PostResponseEvent $event
     *
     * @return bool
     */
    public function onKernelTerminate(PostResponseEvent $event)
    {
        return false;
    }

    /**
     * @param GetResponseForExceptionEvent $event
     *
     * @return bool
     */
    public function onKernelException(GetResponseForExceptionEvent $event)
    {
        return false;
    }
}