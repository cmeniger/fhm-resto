<?php
namespace Fhm\FhmBundle\Entity\Repository;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping\ClassMetadata;

/**
 * SiteRepository
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class SiteRepository extends FhmRepository
{
    /**
     * SiteRepository constructor.
     * @param EntityManager $dm
     * @param ClassMetadata $class
     */
    public function __construct(EntityManager $dm, ClassMetadata $class)
    {
        parent::__construct($dm, $class);
    }

    /**
     * @return mixed
     */
    public function getDefault()
    {
        $builder = $this->createQueryBuilder('a');

        return
            $builder
            ->andWhere('a.default = :bool')->setParameter('bool', true)
            ->setMaxResults(1)
            ->getQuery()
            ->getResult();
    }

    /**
     * void function
     */
    public function resetDefault()
    {
        $this->_em->createQueryBuilder()
        ->update($this->_entityName, 'a')
        ->set('a.default', false)
        ->andWhere('a.default = :bool')->setParameter('bool', true)->getQuery()->execute();
    }
}