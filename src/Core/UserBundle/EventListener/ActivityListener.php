<?php
namespace Core\UserBundle\EventListener;

use Core\UserBundle\Document\User;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;

/**
 * Class ActivityListener
 * @package Core\UserBundle\EventListener
 */
class ActivityListener
{
    protected $context;
    protected $documentM;
 
    public function __construct(TokenStorage $context, $documentM)
    {
        $this->context   = $context;
        $this->documentM = $documentM;
    }

    /**
     * @param FilterControllerEvent $event
     * @return FilterControllerEvent
     */
    public function onCoreController(FilterControllerEvent $event)
    {
        if ($this->context->getToken() != "") {
            $user = $this->context->getToken()->getUser();

            if ($user instanceof User) {
                $user->setDateActivity(new \DateTime());
                $this->documentM->persist($user);
                $this->documentM->flush($user);
            }
        }

        return $event;
    }
}