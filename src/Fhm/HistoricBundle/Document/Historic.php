<?php
namespace Fhm\HistoricBundle\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Doctrine\Bundle\MongoDBBundle\Validator\Constraints\Unique as MongoDBUnique;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Fhm Hisrotic
 * @MongoDB\Document(repositoryClass="Fhm\HistoricBundle\Document\Repository\HistoricRepository")
 */
class Historic
{
    /**
     * @MongoDB\Id(strategy="auto")
     */
    protected $id;

    /**
     * @MongoDB\Field(type="string")
     */
    protected $name;

    /**
     * @MongoDB\Field(type="string")
     */
    protected $type;
    /**
     * @MongoDB\Field(type="date")
     * @Assert\Type("\DateTime")
     */
    protected $date_create;

    /**
     * @MongoDB\Field(type="string")
     */
    protected $objectId;

    /**
     * @MongoDB\Field(type="string")
     */
    protected $objectStatut;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->date_create = new \DateTime();
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     * @return Historic
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param mixed $type
     * @return Historic
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getDateCreate()
    {
        return $this->date_create;
    }

    /**
     * @param mixed $date_create
     * @return Historic
     */
    public function setDateCreate($date_create)
    {
        $this->date_create = $date_create;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getObjectId()
    {
        return $this->objectId;
    }

    /**
     * @param mixed $objectId
     * @return Historic
     */
    public function setObjectId($objectId)
    {
        $this->objectId = $objectId;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getObjectStatut()
    {
        return $this->objectStatut;
    }

    /**
     * @param mixed $objectStatut
     * @return Historic
     */
    public function setObjectStatut($objectStatut)
    {
        $this->objectStatut = $objectStatut;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getArrayStatut(){
        return unserialize($this->getObjectStatut());
    }
    /**
     * @return string
     */
    public function __toString()
    {
        return (string)$this->name;
    }
}