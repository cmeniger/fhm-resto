<?php
namespace Fhm\NotificationBundle\Services;

use Fhm\FhmBundle\Controller\FhmController;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Session\Session;

/**
 * Class Notification
 *
 * @package Fhm\NotificationBundle\Services
 */
class Notification extends FhmController
{
    /**
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        parent::__construct();
    }

    /**
     * @param \Fhm\UserBundle\Document\User $user
     * @param string                        $content
     * @param string                        $template
     * @param array                         $parameter
     *
     * @return $this
     */
    public function create(\Fhm\UserBundle\Document\User $user, $content = '', $template = 'default', $parameter = array())
    {
        $document = new \Fhm\NotificationBundle\Document\Notification();
        $document->setUserCreate($this->getUser());
        $document->setUser($user);
        $document->setContent($content);
        $document->setTemplate($template);
        $document->setParameter($parameter);
        $this->dmPersist($document);

        return $this;
    }
}