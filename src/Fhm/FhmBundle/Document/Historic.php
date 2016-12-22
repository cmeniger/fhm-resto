<?php
/**
 * Created by PhpStorm.
 * User: rcisse
 * Date: 21/12/16
 * Time: 14:03
 */
namespace Fhm\FhmBundle\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

/**
 * Class Historic
 * @MongoDB\Document(repositoryClass="Fhm\FhmBundle\Repository\HistoricRepository")
 *
 * @package Fhm\FhmBundle\Document
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
    protected $objectId;

    /**
     * @MongoDB\Field(type="string")
     */
    protected $etat;

    /**
     * @MongoDB\Field(type="string")
     */
    protected $class;

    /**
     * @MongoDB\Field(type="date")
     */
    protected $dateCreate;

    /**
     * Historic constructor.
     */
    public function __construct()
    {
        $this->dateCreate = new \DateTime();
    }

    /**
     * @return mixed
     */
    public function getObjectId()
    {
        return $this->objectId;
    }

    /**
     * @param $objectId
     */
    public function setObjectId($objectId)
    {
        $this->objectId = $objectId;
    }

    /**
     * @return mixed
     */
    public function getEtat()
    {
        return $this->etat;
    }

    /**
     * @param mixed $etat
     */
    public function setEtat($etat)
    {
        $this->etat = $etat;
    }

    /**
     * @return mixed
     */
    public function getClass()
    {
        return $this->class;
    }

    /**
     * @param mixed $class
     */
    public function setClass($class)
    {
        $this->class = $class;
    }

    /**
     * @return mixed
     */
    public function getDateCreate()
    {
        return $this->dateCreate;
    }

    /**
     * @param mixed $dateCreate
     * @return Historic
     */
    public function setDateCreate($dateCreate)
    {
        $this->dateCreate = $dateCreate;

        return $this;
    }

}
