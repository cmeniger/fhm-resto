<?php
namespace Core\UserBundle\EventListener;

use Core\UserBundle\Document\User;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;

class ActivityListener
{
    protected $context;
    protected $container;
 
    public function __construct(TokenStorage $context, Container $container)
    {
        $this->context   = $context;
        $this->container = $container;
    }
 
    /**
     * On each request we want to update the user's last activity datetime
     *
     * @param \Symfony\Component\HttpKernel\Event\FilterControllerEvent $event
     * @return void
     */
    public function onCoreController(FilterControllerEvent $event)
    {
        if($this->context->getToken() != "")
        {
            $user = $this->context->getToken()->getUser();

            if($user instanceof User)
            {
                $user->setDateActivity(new \DateTime());
                $dm = $this->container->get('doctrine_mongodb')->getManager();
                $dm->persist($user);
                $dm->flush($user);
            }
        }
    }
}