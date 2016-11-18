<?php
namespace Fhm\NoteBundle\Document;

use Fhm\FhmBundle\Document\Fhm as FhmFhm;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Doctrine\Common\Collections\ArrayCollection;
use Fhm\UserBundle\Document\User;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Note
 * @MongoDB\Document(repositoryClass="Fhm\NoteBundle\Repository\NoteRepository")
 */
class Note extends FhmFhm
{
    /**
     * @MongoDB\Field(type="date")
     * @Assert\Type("\DateTime")
     */
    protected $date;

    /**
     * @MongoDB\ReferenceOne(targetDocument="Fhm\UserBundle\Document\User", nullable=true, cascade={"persist"})
     */
    protected $user;

    /**
     * @MongoDB\ReferenceOne
     */
    protected $parent;

    /**
     * @MongoDB\Field(type="int")
     * @Assert\Range(min=0)
     */
    protected $note;

    /**
     * @MongoDB\Field(type="string")
     */
    protected $content;

    /**
     * @MongoDB\Field(type="string")
     */
    protected $sort_parent;

    /**
     * @MongoDB\Field(type="string")
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
        $this->user = ($user instanceof \Fhm\UserBundle\Document\User) ? $user : null;
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