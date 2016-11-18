<?php
namespace Fhm\NotificationBundle\Document;

use Fhm\FhmBundle\Document\Fhm as FhmFhm;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Notification
 * @MongoDB\Document(repositoryClass="Fhm\NotificationBundle\Repository\NotificationRepository")
 */
class Notification extends FhmFhm
{
    /**
     * @MongoDB\ReferenceOne(targetDocument="Fhm\UserBundle\Document\User", nullable=true)
     */
    protected $user;

    /**
     * @MongoDB\Field(type="boolean")
     */
    protected $new;

    /**
     * @MongoDB\Field(type="string")
     */
    protected $content;

    /**
     * @MongoDB\Field(type="string")
     */
    protected $template;

    /**
     * @MongoDB\Field(type="hash")
     */
    protected $parameter;

    /**
     * @MongoDB\Field(type="string")
     */
    protected $sort_user;

    /**
     * @MongoDB\Field(type="string")
     */
    protected $sort_data;

    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->new       = true;
        $this->active    = true;
        $this->template  = "default";
        $this->sort_user = "";
        $this->sort_data = "";
    }

    /**
     * Get user
     *
     * @return \Fhm\UserBundle\Document\User $user
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set user
     *
     * @param \Fhm\UserBundle\Document\User $user
     *
     * @return self
     */
    public function setUser($user)
    {
        $this->user = ($user instanceof \Fhm\UserBundle\Document\User) ? $user : null;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getNew()
    {
        return $this->new;
    }

    /**
     * @param $new
     *
     * @return $this
     */
    public function setNew($new)
    {
        $this->new = $new;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @param $content
     *
     * @return $this
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getTemplate()
    {
        return $this->template;
    }

    /**
     * @param $template
     *
     * @return $this
     */
    public function setTemplate($template)
    {
        $this->template = $template;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getParameter()
    {
        return $this->parameter;
    }

    /**
     * @param $parameter
     *
     * @return $this
     */
    public function setParameter($parameter)
    {
        $this->parameter = $parameter;

        return $this;
    }

    /**
     * Sort update
     *
     * @return $this
     */
    public function sortUpdate()
    {
        $this->sort_user = $this->user->getUsernameCanonical();
        $this->sort_data = $this->user->getEmailCanonical() . ';'
            . $this->user->getUsernameCanonical() . ';'
            . $this->user->getFirstName() . ';'
            . $this->user->getLastName();

        return parent::sortUpdate();
    }

    /**
     * @MongoDB\PrePersist()
     */
    public function prePersist()
    {
        $this->name = $this->user->getUsernameCanonical();

        return parent::prePersist();
    }

    /**
     * @MongoDB\PreUpdate()
     */
    public function preUpdate()
    {
        $this->name = $this->user->getUsernameCanonical();

        return parent::preUpdate();
    }
}