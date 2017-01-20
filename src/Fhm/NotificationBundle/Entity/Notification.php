<?php
namespace Fhm\NotificationBundle\Entity;
use Fhm\FhmBundle\Entity\Fhm;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table()
 */
class Notification extends Fhm
{
    /**
     * @ORM\OneToOne(targetEntity="Fhm\UserBundle\Entity\User", nullable=true)
     */
    protected $user;

    /**
     * @ORM\Column(type="boolean")
     */
    protected $new;

    /**
     * @ORM\Column(type="string", length=100)
     */
    protected $content;

    /**
     * @ORM\Column(type="string", length=100)
     */
    protected $template;

    /**
     * @ORM\Column(type="hash")
     */
    protected $parameter;

    /**
     * @ORM\Column(type="string", length=100)
     */
    protected $sort_user;

    /**
     * @ORM\Column(type="string", length=100)
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
     * @return \Fhm\UserBundle\Entity\User $user
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set user
     *
     * @param \Fhm\UserBundle\Entity\User $user
     *
     * @return self
     */
    public function setUser($user)
    {
        $this->user = ($user instanceof \Fhm\UserBundle\Entity\User) ? $user : null;

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
     * @ORM\PrePersist()
     */
    public function prePersist()
    {
        $this->name = $this->user->getUsernameCanonical();

        return parent::prePersist();
    }

    /**
     * @ORM\PreUpdate()
     */
    public function preUpdate()
    {
        $this->name = $this->user->getUsernameCanonical();

        return parent::preUpdate();
    }
}