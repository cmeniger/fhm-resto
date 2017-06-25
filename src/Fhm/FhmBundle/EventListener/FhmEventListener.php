<?php

namespace Fhm\FhmBundle\EventListener;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;

/**
 * Class FhmEventListener
 *
 * @package Fhm\FhmBundle\EventListener
 */
class FhmEventListener implements EventSubscriberInterface
{
    /** template to display when app is on maintain mode */
    const MAINTENANCE_TEMPLATE = '::ProjectDefault/Template/maintenance.html.twig';

    /** template to display when app is building mode */
    const CONSTRUCT_TEMPLATE = '::ProjectDefault/Template/construct.html.twig';

    private $template;
    private $securityToken;
    private $securityAuthorization;

    /** @parameters must be configured in parameter file and it is compulsory */
    private $parameters;

    /**
     * FhmEventListener constructor.
     *
     * @param $templating
     * @param $securityToken
     * @param $securityAuthorization
     * @param $project
     */
    public function __construct($templating, $securityToken, $securityAuthorization, $project)
    {
        $this->template              = $templating;
        $this->securityAuthorization = $securityAuthorization;
        $this->securityToken         = $securityToken;
        $this->parameters            = $project;
    }

    /**
     * @param GetResponseEvent $event
     *
     * @return bool
     */
    public function onKernelRequest(GetResponseEvent $event)
    {
        $date       = new \DateTime($this->parameters['date']);
        $route      = $event->getRequest()->attributes->get('_route');
        $authorized = false;
        $authorized = in_array($route, $this->parameters['firewall']) ? true : $authorized;
        if($this->securityToken->getToken())
        {
            $authorized = $this->securityAuthorization->isGranted(
                'ROLE_ADMIN'
            ) ? true : $authorized;
        }
        $authorized = in_array(
            getenv('SYMFONY_ENV'),
            array('test', 'dev')
        ) ? true : $authorized;
        if($this->parameters['construct'] && !$authorized)
        {
            $event->setResponse(
                new Response(
                    $this->template->render(
                        self::CONSTRUCT_TEMPLATE,
                        array('date' => $date, 'site' => null)
                    ),
                    503
                )
            );
            $event->stopPropagation();
        }
        elseif($this->parameters['maintenance'] && !$authorized)
        {
            $event->setResponse(
                new Response(
                    $this->template->render(
                        self::MAINTENANCE_TEMPLATE,
                        array('date' => $date, 'site' => null)
                    ),
                    503
                )
            );
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
            'kernel.request' => 'onKernelRequest',
        );
    }
}