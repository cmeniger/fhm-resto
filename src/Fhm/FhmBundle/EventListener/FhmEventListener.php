<?php
namespace Fhm\FhmBundle\EventListener;

use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
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

/**
 * Class FhmEventListener
 * @package Fhm\FhmBundle\EventListener
 */
class FhmEventListener implements EventSubscriberInterface
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * Sets the container.
     *
     * @param ContainerInterface|null $container A ContainerInterface instance or null
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    /**
     * @return mixed
     */
    public function getContainer()
    {
        return $this->container;
    }

    /**
     * @param GetResponseEvent $event
     *
     * @return bool
     */
    public function onKernelRequest(GetResponseEvent $event)
    {
        // Locale
        if ($event->getRequest()->hasPreviousSession()) {
            if ($locale = $event->getRequest()->attributes->get('_locale')) {
                $event->getRequest()->getSession()->set('_locale', $locale);
            }
            $event->getRequest()->setLocale($event->getRequest()->getSession()->get('_locale'));
        }
        // Construct / Maintenance
        if (HttpKernel::MASTER_REQUEST != $event->getRequestType() || $event->getRequest()->isXmlHttpRequest()) {
            return false;
        }
        $parameters = $this->getContainer()->getParameter("project");
        $maintenance = isset($parameters['maintenance']) ? $parameters['maintenance'] : false;
        $construct = isset($parameters['construct']) ? $parameters['construct'] : false;
        $firewall = isset($parameters['firewall']) ? $parameters['firewall'] : array();
        $date = isset($parameters['date']) ? new \DateTime($parameters['date']) : '';
        $route = $event->getRequest()->attributes->get('_route');
        $authorized = false;
        $authorized = in_array($route, $firewall) ? true : $authorized;
        if ($this->getContainer()->get('security.token_storage')->getToken()) {
            $authorized = $this->getContainer()->get('security.authorization_checker')->isGranted(
                'ROLE_ADMIN'
            ) ? true : $authorized;
        }
        $authorized = in_array(
            $this->getContainer()->get('kernel')->getEnvironment(),
            array('test', 'dev')
        ) ? true : $authorized;
        if ($construct && !$authorized) {
            $event->setResponse(new Response("", 503));
            $event->stopPropagation();
        } elseif ($maintenance && !$authorized) {
            $event->setResponse( new Response("", 503));
            $event->stopPropagation();
        }

        return false;
    }

    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return array(
            'kernel.request' => 'onKernelRequest'
        );
    }
}