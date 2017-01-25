<?php
namespace Fhm\ContactBundle\Entity\Repository;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping\ClassMetadata;
use Fhm\FhmBundle\Entity\Repository\FhmRepository;

/**
 * ContactRepository
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ContactRepository extends FhmRepository
{
    /**
     * ContactRepository constructor.
     * @param EntityManager $dm
     * @param ClassMetadata $class
     */
    public function __construct(EntityManager $dm, ClassMetadata $class)
    {
        parent::__construct($dm, $class);
    }

    /**
     * @param string $search
     * @return mixed
     */
    public function getFrontIndex($search = "")
    {
        return parent::getFrontIndex($search);
    }
}