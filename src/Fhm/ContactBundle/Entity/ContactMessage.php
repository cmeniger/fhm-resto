<?php
namespace Fhm\ContactBundle\Entity;
use Fhm\FhmBundle\Entity\Fhm as FhmFhm;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table()
 */
class ContactMessage extends FhmFhm
{
    /**
     * @ORM\Column(type="string", length=100)
     */
    protected $email;

    /**
     * @ORM\Column(type="array")
     */
    protected $field;

    /**
     * @ORM\OneToOne(targetEntity="Fhm\ContactBundle\Entity\Contact", orphanRemoval=true, cascade={"persist"}, inversedBy="messages")
     */
    protected $contact;

    /**
     * @ORM\Column(type="string", length=100)
     */
    protected $sort_contact;

    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->contact = null;
        $this->sort_contact = null;
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param $email
     *
     * @return $this
     */
    public function setEmail($email)
    {
        $this->email = strtolower($email);

        return $this;
    }

    /**
     * @return mixed
     */
    public function getField()
    {
        return $this->field;
    }

    /**
     * @param $field
     *
     * @return $this
     */
    public function setField($field)
    {
        $this->field = $field;

        return $this;
    }

    /**
     * Get contact
     *
     * @return \Fhm\ContactBundle\Entity\Contact
     */
    public function getContact()
    {
        return $this->contact;
    }

    /**
     * Set contact
     *
     * @param Contact $contact
     *
     * @return $this
     */
    public function setContact($contact)
    {
        $this->contact = ($contact instanceof \Fhm\ContactBundle\Entity\Contact) ? $contact : null;

        return $this;
    }

    /**
     * Remove contact
     *
     * @return $this
     */
    public function removeContact()
    {
        $this->contact = null;

        return $this;
    }

    /**
     * Sort update
     *
     * @return $this
     */
    public function sortUpdate()
    {
        $this->sort_contact = $this->contact ? $this->contact->getAlias() : null;

        return parent::sortUpdate();
    }

    /**
     * @ORM\PreRemove()
     */
    public function preRemove()
    {
        parent::preRemove();
        if ($this->contact) {
            $this->contact->removeMessage($this);
        }
    }
}