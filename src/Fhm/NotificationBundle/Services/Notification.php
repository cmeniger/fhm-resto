<?php
namespace Fhm\NotificationBundle\Services;

/**
 * Class Notification
 *
 * @package Fhm\NotificationBundle\Services
 */
class Notification
{
    private $fhm_tools;

    /**
     * Notification constructor.
     *
     * @param \Fhm\FhmBundle\Services\Tools $tools
     */
    public function __construct(\Fhm\FhmBundle\Services\Tools $tools)
    {
        $this->fhm_tools = $tools;
    }

    /**
     * @param \Fhm\UserBundle\Document\User $user
     * @param string $content
     * @param string $template
     * @param array $parameter
     *
     * @return $this
     */
    public function create(
        \Fhm\UserBundle\Document\User $user,
        $content = '',
        $template = 'default',
        $parameter = array()
    ) {
        $document = new \Fhm\NotificationBundle\Document\Notification();
        $document->setUserCreate($this->fhm_tools->getUser());
        $document->setUser($user);
        $document->setContent($content);
        $document->setTemplate($template);
        $document->setParameter($parameter);
        $this->fhm_tools->dmPersist($document);

        return $this;
    }
}