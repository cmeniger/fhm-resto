<?php
namespace Fhm\NotificationBundle\Services;

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
    public function __construct($dm)
    {
        $this->dm = $dm;
    }

    /**
     * @param $user
     * @param string $content
     * @param string $template
     * @param array $parameter
     *
     * @return $this
     */
    public function create($user, $content = '', $template = 'default', $parameter = array())
    {
        $objectName = $this->dm->getCurrentModelName('NotificationBundle:Notification');
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