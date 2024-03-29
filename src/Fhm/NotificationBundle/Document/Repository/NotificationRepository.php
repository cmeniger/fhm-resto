<?php
namespace Fhm\NotificationBundle\Document\Repository;

use Fhm\FhmBundle\Document\Repository\FhmRepository;
use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\ODM\MongoDB\UnitOfWork;
use Doctrine\ODM\MongoDB\Mapping\ClassMetadata;

/**
 * NotificationRepository
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class NotificationRepository extends FhmRepository
{
    /**
     * NotificationRepository constructor.
     * @param DocumentManager $dm
     * @param UnitOfWork $uow
     * @param ClassMetadata $class
     */
    public function __construct(DocumentManager $dm, UnitOfWork $uow, ClassMetadata $class)
    {
        parent::__construct($dm, $uow, $class);
    }

    /**
     * @param $user
     *
     * @return int|mixed
     * @throws \Doctrine\ODM\MongoDB\MongoDBException
     */
    public function getIndexNew($user)
    {
        if (!$user) {
            return 0;
        }
        $builder = $this->createQueryBuilder();
        $builder->field('user.id')->equals($user->getId());
        $builder->field('new')->equals(true);
        $builder->field('active')->equals(true);
        $builder->field('delete')->equals(false);
        $builder->sort('date_create', 'desc');

        return $builder->getQuery()->execute()->toArray();
    }

    /**
     * @param $user
     *
     * @return int|mixed
     * @throws \Doctrine\ODM\MongoDB\MongoDBException
     */
    public function getIndexAll($user)
    {
        if (!$user) {
            return 0;
        }
        $builder = $this->createQueryBuilder();
        $builder->field('user.id')->equals($user->getId());
        $builder->field('active')->equals(true);
        $builder->field('delete')->equals(false);
        $builder->sort('date_create', 'desc');

        return $builder->getQuery()->execute()->toArray();
    }

    /**
     * @param $user
     *
     * @return int|mixed
     * @throws \Doctrine\ODM\MongoDB\MongoDBException
     */
    public function getCountNew($user)
    {
        if (!$user) {
            return 0;
        }
        $builder = $this->createQueryBuilder();
        $builder->field('user.id')->equals($user->getId());
        $builder->field('new')->equals(true);
        $builder->field('active')->equals(true);
        $builder->field('delete')->equals(false);

        return $builder->count()->getQuery()->execute();
    }

    /**
     * @param $user
     *
     * @return int|mixed
     * @throws \Doctrine\ODM\MongoDB\MongoDBException
     */
    public function getCountAll($user)
    {
        if (!$user) {
            return 0;
        }
        $builder = $this->createQueryBuilder();
        $builder->field('user.id')->equals($user->getId());
        $builder->field('active')->equals(true);
        $builder->field('delete')->equals(false);

        return $builder->count()->getQuery()->execute();
    }
}