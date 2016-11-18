<?php
namespace Fhm\MenuBundle\Repository;

use Fhm\FhmBundle\Repository\FhmRepository;
use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\ODM\MongoDB\UnitOfWork;
use Doctrine\ODM\MongoDB\Mapping\ClassMetadata;
use MyProject\Proxies\__CG__\stdClass;

/**
 * MenuRepository
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class MenuRepository extends FhmRepository
{
    /**
     * Constructor
     */
    public function __construct(DocumentManager $dm, UnitOfWork $uow, ClassMetadata $class)
    {
        parent::__construct($dm, $uow, $class);
    }

    /**
     * @param string $grouping
     *
     * @return \Doctrine\ODM\MongoDB\Query\Builder
     */
    public function getFormEnable($grouping = "")
    {
        $builder = $this->createQueryBuilder();
        // Language
        if($this->language)
        {
            $builder->field('languages')->in((array) $this->language);
        }
        // Grouping
        if($grouping != "")
        {
            $builder->addOr($builder->expr()->field('grouping')->in((array) $grouping));
            $builder->addOr($builder->expr()->field('share')->equals(true));
        }
        // Common
        $builder->field('parent')->in(array('0', null));
        $builder->field('active')->equals(true);
        $builder->field('delete')->equals(false);
        $builder->sort('order');
        $builder->sort('name');

        return $builder;
    }

    /**
     * @param $idp
     *
     * @return mixed
     */
    public function getSons($idp)
    {
        return $this->createQueryBuilder()
            ->field('parent')->equals($idp)
            ->sort('order')
            ->sort('name')
            ->getQuery()
            ->execute()
            ->toArray();
    }

    /**
     * @param $idp
     *
     * @return object
     * @throws \Doctrine\ODM\MongoDB\LockException
     */
    public function getTree($idp)
    {
        $current          = $this->find($idp);
        $current->branchs = array();
        $branchs          = $current->getChilds();
        foreach($branchs as $branch)
        {
            $current->branchs[] = $this->getTree($branch->getId());
        }

        return $current;
    }

    /**
     * @param $idp
     *
     * @return array
     * @throws \Doctrine\ODM\MongoDB\LockException
     */
    public function getTreeMap($idp)
    {
        $current = $this->find($idp);
        $map     = array();
        if($current->getParent() != '0')
        {
            $map = array_merge($map, $this->getTreeMap($current->getParent()));
        }
        $map[] = $current;

        return $map;
    }
}