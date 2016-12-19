<?php
namespace Fhm\UserBundle\Repository;

use Fhm\FhmBundle\Repository\FhmRepository;
use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\ODM\MongoDB\UnitOfWork;
use Doctrine\ODM\MongoDB\Mapping\ClassMetadata;

/**
 * UserRepository
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class UserRepository extends FhmRepository
{
    /**
     * UserRepository constructor.
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
        $builder = ($search) ? $this->search($search) : $this->createQueryBuilder();
        // RoleSuperAdmin
        if (!$roleSuperAdmin) {
            $builder->field('delete')->equals(false);
        }
        // Common
        $builder->field('roles')->notIn(array('ROLE_SUPER_ADMIN'));
        $this->builderSort($builder);

        return $builder->getQuery()->execute()->toArray();
    }

    /**
     * @param string $search
     * @param bool $roleSuperAdmin
     *
     * @return int
     * @throws \Doctrine\ODM\MongoDB\MongoDBException
     */
    public function getAdminCount($search = "", $roleSuperAdmin = false)
    {
        $builder = ($search) ? $this->search($search) : $this->createQueryBuilder();
        // RoleSuperAdmin
        if (!$roleSuperAdmin) {
            $builder->field('delete')->equals(false);
        }
        // Common
        $builder->field('roles')->notIn(array('ROLE_SUPER_ADMIN'));

        return count(
            $builder->getQuery()->execute()->toArray()
        );
    }

    /**
     * @param string $search
     * @param int $page
     * @param int $count
     *
     * @return mixed
     * @throws \Doctrine\ODM\MongoDB\MongoDBException
     */
    public function getFrontIndex($search = "", $page = 1, $count = 5)
    {
        $builder = (($page > 0 && $count > 0) && $search) ? $this->search($search) : $this->createQueryBuilder();
        // Pagination
        if ($page > 0 && $count > 0) {
            $builder->limit($count);
            $builder->skip(($page - 1) * $count);
        }
        // Common
        $builder->field('roles')->notIn(array('ROLE_SUPER_ADMIN'));
        $builder->field('active')->equals(true);
        $builder->field('delete')->equals(false);
        $this->builderSort($builder);

        return $builder->getQuery()->execute()->toArray();
    }

    /**
     * @param string $search
     *
     * @return int
     * @throws \Doctrine\ODM\MongoDB\MongoDBException
     */
    public function getFrontCount($search = "")
    {
        $builder = ($search) ? $this->search($search) : $this->createQueryBuilder();
        // Common
        $builder->field('roles')->notIn(array('ROLE_SUPER_ADMIN'));
        $builder->field('active')->equals(true);
        $builder->field('delete')->equals(false);

        return count(
            $builder->getQuery()->execute()->toArray()
        );
    }

    /**
     * @param string $group
     *
     * @return mixed
     */
    public function getExport($group = "")
    {
        return $this->createQueryBuilder()->sort('username')->getQuery()->execute()->toArray();
    }

    /**
     * @param      $data
     * @param null $index
     *
     * @return mixed|string
     * @throws \Doctrine\ODM\MongoDB\MongoDBException
     */
    public function getImport($data, $index = null)
    {
        $qb = $this->createQueryBuilder();
        $qb->addOr($qb->expr()->field('usernameCanonical')->equals(strtolower($data['username'])));
        $qb->addOr($qb->expr()->field('emailCanonical')->equals(strtolower($data['email'])));
        $results = $qb->getQuery()->execute()->toArray();
        if (count($results) > 1) {
            return 'error';
        } elseif (count($results) == 1) {
            return array_shift($results);
        } else {
            return '';
        }
    }

    /**
     * @param $name
     *
     * @return Object
     */
    public function getByName($name)
    {
        return $this->getUserByUsername($name);
    }

    /**
     * @param $username
     *
     * @return object
     */
    public function getUserByUsername($username)
    {
        return $this->findOneBy(array('usernameCanonical' => strtolower($username)));
    }

    /**
     * @param $email
     *
     * @return object
     */
    public function getUserByEmail($email)
    {
        return $this->findOneBy(array('emailCanonical' => strtolower($email)));
    }
}