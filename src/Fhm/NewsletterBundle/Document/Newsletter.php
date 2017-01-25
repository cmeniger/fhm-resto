<?php
namespace Fhm\NewsletterBundle\Document;

use Fhm\FhmBundle\Document\Fhm as FhmFhm;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Doctrine\Bundle\MongoDBBundle\Validator\Constraints\Unique as MongoDBUnique;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Newsletter
 * @MongoDB\Document(repositoryClass="Fhm\NewsletterBundle\Document\Repository\NewsletterRepository")
 * @MongoDBUnique(fields="email")
 */
class Newsletter extends FhmFhm
{
    /**
     * @MongoDB\Field(type="string")
     * @Assert\NotBlank()
     */
    protected $email;

    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @param $email
     *
     * @return $this
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Get CSV header
     *
     * @return array
     */
    public function getCsvHeader()
    {
        return array(
            'id',
            'name',
            'email',
            'date_create',
            'date_update',
            'delete',
            'active'
        );
    }

    /**
     * Get CSV data
     *
     * @return array
     */
    public function getCsvData()
    {
        return array(
            utf8_decode($this->id),
            utf8_decode($this->name),
            utf8_decode($this->email),
            ($this->date_create) ? $this->date_create->format('d/m/Y H:i:s') : '',
            ($this->date_update) ? $this->date_update->format('d/m/Y H:i:s') : '',
            $this->delete,
            $this->active
        );
    }

    /**
     * Set CSV data
     *
     * @param array $data
     *
     * @return self
     */
    public function setCsvData($data)
    {
        $this->email = (isset($data['email'])) ? $data['email'] : $this->email;

        return $this;
    }

    /**
     * @MongoDB\PrePersist()
     */
    public function prePersist()
    {
        $this->setName(strtolower($this->email));
        $this->setAlias(strtolower($this->email));

        return parent::prePersist();
    }

    /**
     * @MongoDB\PreUpdate()
     */
    public function preUpdate()
    {
        $this->setName(strtolower($this->email));
        $this->setAlias(strtolower($this->email));

        return parent::preUpdate();
    }
}