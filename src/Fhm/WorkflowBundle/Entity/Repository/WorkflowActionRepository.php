<?php
namespace Fhm\WorkflowBundle\Entity\Repository;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping\ClassMetadata;
use Fhm\FhmBundle\Entity\Repository\FhmRepository;

/**
 * WorkflowActionRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class WorkflowActionRepository extends FhmRepository
{
    /**
     * WorkflowActionRepository constructor.
     * @param EntityManager $dm
     * @param ClassMetadata $class
     */
    public function __construct(EntityManager $dm, ClassMetadata $class)
    {
        parent::__construct($dm, $class);
    }
}