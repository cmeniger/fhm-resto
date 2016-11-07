<?php
namespace Fhm\UserBundle\EventListener;

use Fhm\UserBundle\Document\User;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;

/**
 * Class ActivityListener
 * @package Fhm\UserBundle\EventListener
 */
class ActivityListener
{
    protected $context;
    protected $dmongo;
 
    public function __construct(TokenStorage $context, $dmongo)
    {
        $this->context   = $context;
        $this->dmongo = $dmongo;
    }
 
    /**
     * On each request we want to update the user's last activity datetime
     *
     * @param \Symfony\Component\HttpKernel\Event\FilterControllerEvent $event
     * @return void
     */
    public function onCoreController(FilterControllerEvent $event)
    {
        if ($this->context->getToken() != "") {

            $user = $this->context->getToken()->getUser();

            if ($user instanceof User) {

                $user->setDateActivity(new \DateTime());
                $manager = $this->dmongo->getManager();
                $manager->persist($user);
                $manager->flush($user);
            }
        }
    }
}