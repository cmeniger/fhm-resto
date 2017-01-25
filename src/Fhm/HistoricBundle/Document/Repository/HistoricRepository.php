<?php
namespace Fhm\HistoricBundle\Document\Repository;

use Fhm\FhmBundle\Document\Repository\FhmRepository;
use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\ODM\MongoDB\UnitOfWork;
use Doctrine\ODM\MongoDB\Mapping\ClassMetadata;

/**
 * Created by PhpStorm.
 * User: fhm
 * Date: 12/01/17
 * Time: 10:11
 */
class HistoricRepository extends FhmRepository
{
    /**
     * GeolocationRepository constructor.
     * @param DocumentManager $dm
     * @param UnitOfWork $uow
     * @param ClassMetadata $class
     */
    public function __construct(DocumentManager $dm, UnitOfWork $uow, ClassMetadata $class)
    {
        parent::__construct($dm, $uow, $class);
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