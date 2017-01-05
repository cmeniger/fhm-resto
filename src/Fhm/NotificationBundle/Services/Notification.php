<?php
namespace Fhm\NotificationBundle\Services;

use Doctrine\ODM\MongoDB\DocumentManager;
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
    public function __construct(DocumentManager $dm)
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
        $document = new \Fhm\NotificationBundle\Document\Notification();
        $document->setUserCreate($user);
        $document->setUser($user);
        $document->setContent($content);
        $document->setTemplate($template);
        $document->setParameter($parameter);
        $this->dm->persist($document);
        $this->dm->flush();

        return $this;
    }
}