<?php
namespace Fhm\HistoricBundle\Entity\Repository;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping\ClassMetadata;
use Fhm\FhmBundle\Entity\Repository\FhmRepository;

/**
 * Class HistoricRepository
 * @package Fhm\HistoricBundle\Entity\Repository
 */
class HistoricRepository extends FhmRepository
{
    /**
     * FhmRepository constructor.
     * @param EntityManager $dm
     * @param ClassMetadata $class
     */
    public function __construct(EntityManager $dm, ClassMetadata $class)
    {
        parent::__construct($dm, $class);
    }

    /**
     * @param string $search
     * @param bool $roleSuperAdmin
     * @return mixed
     */
    public function getAdminIndex($search = "", $roleSuperAdmin = false)
    {
        $builder = $search ? $this->search($search) : $this->createQueryBuilder();
        // Common
        $this->builderSort($builder);
        return $builder->getQuery();
    }
}