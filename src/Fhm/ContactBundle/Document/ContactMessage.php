<?php
namespace Fhm\ContactBundle\Document;

use Fhm\FhmBundle\Document\Fhm as FhmFhm;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * ContactMessage
 * @MongoDB\Document(repositoryClass="Fhm\ContactBundle\Document\Repository\ContactMessageRepository")
 */
class ContactMessage extends FhmFhm
{
    /**
     * @MongoDB\Field(type="string")
     */
    protected $email;

    /**
     * @MongoDB\Field(type="hash")
     */
    protected $field;

    /**
     * @MongoDB\ReferenceOne(targetDocument="Fhm\ContactBundle\Document\Contact", nullable=true, cascade={"persist"})
     */
    protected $contact;

    /**
     * @MongoDB\Field(type="string")
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
     * @return \Fhm\ContactBundle\Document\Contact
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
        $this->contact = ($contact instanceof \Fhm\ContactBundle\Document\Contact) ? $contact : null;

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
     * @MongoDB\PreRemove()
     */
    public function preRemove()
    {
        parent::preRemove();
        if ($this->contact) {
            $this->contact->removeMessage($this);
        }
    }
}