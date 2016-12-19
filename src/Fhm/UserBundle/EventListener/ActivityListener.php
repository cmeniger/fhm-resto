<?php
namespace Fhm\UserBundle\EventListener;

use Doctrine\ODM\MongoDB\DocumentManager;
use Fhm\UserBundle\Document\User;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;

/**
 * Class ActivityListener
 * @package Fhm\UserBundle\EventListener
 */
class ActivityListener
{
    protected $context;
    protected $dm;

    /**
     * ActivityListener constructor.
     * @param TokenStorage $context
     * @param DocumentManager $dm
     */
    public function __construct(TokenStorage $context, DocumentManager $dm)
    {
        $this->context = $context;
        $this->dm = $dm;
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
                $this->dm->persist($user);
                $this->dm->flush($user);
            }
        }
    }
}