<?php
namespace Fhm\NotificationBundle\Services;

use Fhm\Manager\AbstractManager;
use Fhm\UserBundle\Document\User;

/**
 * Class Notification
 *
 * @package Fhm\NotificationBundle\Services
 */
class Notification
{
    private $dm;

    /**
     * Notification constructor.
     * @param $dm
     */
    public function __construct(AbstractManager $dm)
    {
        $this->dm = $dm;
    }

    /**
     * @param \Fhm\UserBundle\Document\User $user
     * @param string $content
     * @param string $template
     * @param array $parameter
     *
     * @return $this
     */
    public function create(User $user, $content = '', $template = 'default', $parameter = array())
    {
        $objectName = $this->dm->getCurrentModelName();
        $document = new $objectName;
        $document->setUserCreate($user);
        $document->setUser($user);
        $document->setContent($content);
        $document->setTemplate($template);
        $document->setParameter($parameter);
        $this->dm->getManager()->persist($document);
        $this->dm->getManager()->flush();

        return $this;
    }
}