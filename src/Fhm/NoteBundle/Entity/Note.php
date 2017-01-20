<?php
namespace Fhm\NoteBundle\Entity;
use Fhm\FhmBundle\Entity\Fhm;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table()
 */
class Note extends Fhm
{
    /**
     * @ORM\Column(type="date")
     * @Assert\Type("\DateTime")
     */
    protected $date;

    /**
     * @ORM\OneToOne(targetEntity="Fhm\UserBundle\Entity\User", nullable=true, cascade={"persist"})
     */
    protected $user;

    /**
     * @ORM\OneToOne
     */
    protected $parent;

    /**
     * @ORM\Column(type="integer")
     * @Assert\Range(min=0)
     */
    protected $note;

    /**
     * @ORM\Column(type="string", length=100)
     */
    protected $content;

    /**
     * @ORM\Column(type="string", length=100)
     */
    protected $sort_parent;

    /**
     * @ORM\Column(type="string", length=100)
     */
    protected $sort_user;

    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->active      = true;
        $this->sort_parent = '';
        $this->sort_user   = '';
    }

    /**
     * Get date
     *
     * @return \datetime $date
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set date
     *
     * @param \datetime $date
     *
     * @return self
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param mixed $user
     */
    public function setUser($user)
    {
        $this->user = ($user instanceof \Fhm\UserBundle\Entity\User) ? $user : null;
    }

    /**
     * @return mixed
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * @param mixed $parent
     */
    public function setParent($parent)
    {
        $this->parent = $parent;
    }

    /**
     * @return mixed
     */
    public function getNote()
    {
        return $this->note;
    }

    /**
     * @param mixed $note
     */
    public function setNote($note)
    {
        $this->note = $note;
    }

    /**
     * @return mixed
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @param mixed $content
     */
    public function setContent($content)
    {
        $this->content = $content;
    }

    /**
     * Sort update
     *
     * @return $this
     */
    public function sortUpdate()
    {
        $this->sort_parent = $this->parent ? $this->parent->getName() : '';
        $this->sort_user   = $this->user ? $this->user->getUsername() : '';

        return parent::sortUpdate();
    }
}