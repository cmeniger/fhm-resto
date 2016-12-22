<?php
namespace Fhm\FhmBundle\Repository;

use Fhm\FhmBundle\Repository\FhmRepository;
use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\ODM\MongoDB\UnitOfWork;
use Doctrine\ODM\MongoDB\Mapping\ClassMetadata;

/**
 * Class HistoricRepository
 *
 * @package Fhm\FhmBundle\Repository
 */
class HistoricRepository extends FhmRepository
{
    /**
     * HistoricRepository constructor.
     * @param DocumentManager $dm
     * @param UnitOfWork $uow
     * @param ClassMetadata $class
     */
    public function __construct(DocumentManager $dm, UnitOfWork $uow, ClassMetadata $class)
    {
        parent::__construct($dm, $uow, $class);
    }
}