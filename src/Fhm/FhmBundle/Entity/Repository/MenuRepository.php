<?php
namespace Fhm\FhmBundle\Entity\Repository;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping\ClassMetadata;

/**
 * MenuRepository
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class MenuRepository extends FhmRepository
{
    /**
     * MenuRepository constructor.
     * @param EntityManager $dm
     * @param ClassMetadata $class
     */
    public function __construct(EntityManager $dm, ClassMetadata $class)
    {
        parent::__construct($dm, $class);
    }

    /**
     * @return \Doctrine\ODM\MongoDB\Query\Builder
     */
    public function getFormEnable()
    {
        $builder = $this->createQueryBuilder('a');
        // Common
        if ($this->parent) {
            $builder->andWhere('a.parent IN :(parent)')->setParameter('parent', [0, null]);
        }
        $builder->andWhere('a.active = :bool1')->setParameter('bool1', true);
        $builder->andWhere('a.delete = :bool2')->setParameter('bool2', false);
        $builder->orderBy('a.order');
        $builder->orderBy('a.name');

        return $builder;
    }

    /**
     * @param $idp
     *
     * @return mixed
     */
    public function getSons($idp)
    {
        return $this->createQueryBuilder('a')->andWhere('a.parent = :(parent)')->setParameter('parent', $idp)->orderBy(
                'a.order'
            )->orderBy('a.name')->getQuery()->execute()->toArray();
    }

    /**
     * @param $idp
     * @return null|object
     */
    public function getTree($idp)
    {
        $current = $this->find($idp);
        $current->branchs = array();
        $branchs = $current->getChilds();
        if (is_array($branchs)) {
            foreach ($branchs as $branch) {
                $current->branchs[] = $this->getTree($branch->getId());
            }
        }

        return $current;
    }

    /**
     * @param $idp
     * @return array
     */
    public function getTreeMap($idp)
    {
        $current = $this->find($idp);
        $map = array();
        if ($current->getParent() != '0') {
            $map = array_merge($map, $this->getTreeMap($current->getParent()));
        }
        $map[] = $current;

        return $map;
    }
}