<?php
namespace Fhm\WorkflowBundle\Entity\Repository;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping\ClassMetadata;
use Fhm\FhmBundle\Entity\Repository\FhmRepository;

/**
 * WorkflowRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class WorkflowRepository extends FhmRepository
{
    /**
     * WorkflowRepository constructor.
     * @param EntityManager $dm
     * @param ClassMetadata $class
     */
    public function __construct(EntityManager $dm, ClassMetadata $class)
    {
        parent::__construct($dm, $class);
    }

    /**
     * @param            $status
     * @param bool|false $enable
     *
     * @return mixed
     * @throws \Doctrine\ORM\ORMException
     */
    public function getByStatus($status, $enable=false)
    {
        $builder = $this->createQueryBuilder();
        $builder->field('status')->equals($status);
        if($enable)
        {
            $builder->field('active')->equals(true);
            $builder->field('delete')->equals(false);
        }
        $this->builderSort($builder);

        return $builder
            ->getQuery()
            ->execute()
            ->toArray();
    }

    /**
     * @param            $step
     * @param bool|false $enable
     *
     * @return mixed
     * @throws \Doctrine\ORM\ORMException
     */
    public function getByStep($step, $enable=false)
    {
        $builder = $this->createQueryBuilder();
        $builder->field('step.id')->equals($step);
        if($enable)
        {
            $builder->field('active')->equals(true);
            $builder->field('delete')->equals(false);
        }
        $this->builderSort($builder);

        return $builder
            ->getQuery()
            ->execute()
            ->toArray();
    }
}