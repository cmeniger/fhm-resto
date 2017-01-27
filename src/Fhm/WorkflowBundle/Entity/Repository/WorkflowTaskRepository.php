<?php
namespace Fhm\WorkflowBundle\Entity\Repository;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping\ClassMetadata;
use Fhm\FhmBundle\Entity\Repository\FhmRepository;

/**
 * WorkflowTaskRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class WorkflowTaskRepository extends FhmRepository
{
    /**
     * WorkflowTaskRepository constructor.
     * @param EntityManager $dm
     * @param ClassMetadata $class
     */
    public function __construct(EntityManager $dm, ClassMetadata $class)
    {
        parent::__construct($dm, $class);
    }

    /**
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function getFormParent()
    {
        $builder = $this->createQueryBuilder();
        // Parent
        if ($this->parent) {
            $builder->field('parent')->in(array('0', null));
        }
        // Common
        $builder->field('parents')->equals(null);
        $builder->field('active')->equals(true);
        $builder->field('delete')->equals(false);
        $this->builderSort($builder);

        return $builder;
    }
}