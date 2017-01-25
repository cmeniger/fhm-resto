<?php
namespace Fhm\NewsBundle\Entity\Repository;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping\ClassMetadata;
use Fhm\FhmBundle\Entity\Repository\FhmRepository;

/**
 * NewsRepository
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class NewsRepository extends FhmRepository
{
    /**
     * NewsRepository constructor.
     * @param EntityManager $dm
     * @param ClassMetadata $class
     */
    public function __construct(EntityManager $dm, ClassMetadata $class)
    {
        parent::__construct($dm, $class);
    }

    /**
     * @param string $search
     * @param int $page
     * @param int $count
     * @param string $grouping
     *
     * @return mixed
     * @throws \Doctrine\ORM\ORMException
     */
    public function getFrontIndex($search = "", $page = 1, $count = 5, $grouping = "")
    {
        $builder = ($search) ? $this->search($search) : $this->createQueryBuilder();
        // Dates
        $builder->addAnd(
            $builder->expr()->addOr($builder->expr()->field('date_start')->equals(null))->addOr(
                $builder->expr()->field('date_start')->lt(new \DateTime())
            )
        );
        $builder->addAnd(
            $builder->expr()->addOr($builder->expr()->field('date_end')->equals(null))->addOr(
                $builder->expr()->field('date_end')->gt(new \DateTime())
            )
        );
        // Common
        $builder->field('active')->equals(true);
        $builder->field('delete')->equals(false);
        $builder->sort('order', 'asc');
        $builder->sort('date_start', 'desc');
        $builder->sort('date_create', 'desc');
        $builder->sort('name', 'asc');

        return $builder->getQuery()->execute()->toArray();
    }

    /**
     * @param string $search
     * @param string $grouping
     *
     * @return int
     * @throws \Doctrine\ORM\ORMException
     */
    public function getFrontCount($search = "", $grouping = "")
    {
        $builder = ($search) ? $this->search($search) : $this->createQueryBuilder();
        // Dates
        $builder->addAnd(
            $builder->expr()->addOr($builder->expr()->field('date_start')->equals(null))->addOr(
                $builder->expr()->field('date_start')->lt(new \DateTime())
            )
        );
        $builder->addAnd(
            $builder->expr()->addOr($builder->expr()->field('date_end')->equals(null))->addOr(
                $builder->expr()->field('date_end')->gt(new \DateTime())
            )
        );
        // Common
        $builder->field('active')->equals(true);
        $builder->field('delete')->equals(false);

        return count(
            $builder->getQuery()->execute()->toArray()
        );
    }

    /**
     * @param \Fhm\NewsBundle\Entity\NewsGroup $newsgroup
     * @param string $search
     * @param int $page
     * @param int $count
     *
     * @return mixed
     * @throws \Doctrine\ORM\ORMException
     */
    public function getNewsByGroupIndex(
        \Fhm\NewsBundle\Entity\NewsGroup $newsgroup,
        $search = "",
        $page = 1,
        $count = 5
    ) {
        $builder = ($search) ? $this->search($search) : $this->createQueryBuilder();
        // Global
        if ($newsgroup->getAddGlobal()) {
            $builder->addAnd(
                $builder->expr()->addOr($builder->expr()->field('newsgroups.id')->equals($newsgroup->getId()))->addOr(
                    $builder->expr()->field('global')->equals(true)
                )
            );
        } else {
            $builder->field('newsgroups.id')->equals($newsgroup->getId());
        }
        // Dates
        $builder->addAnd(
            $builder->expr()->addOr($builder->expr()->field('date_start')->equals(null))->addOr(
                $builder->expr()->field('date_start')->lt(new \DateTime())
            )
        );
        $builder->addAnd(
            $builder->expr()->addOr($builder->expr()->field('date_end')->equals(null))->addOr(
                $builder->expr()->field('date_end')->gt(new \DateTime())
            )
        );
        // Common
        $builder->field('active')->equals(true);
        $builder->field('delete')->equals(false);
        $builder->sort($newsgroup->getSortField(), $newsgroup->getSortOrder());

        return $builder->getQuery()->execute()->toArray();
    }

    /**
     * @param \Fhm\NewsBundle\Entity\NewsGroup $newsgroup
     * @param string $search
     *
     * @return mixed
     */
    public function getNewsByGroupCount(\Fhm\NewsBundle\Entity\NewsGroup $newsgroup, $search = "")
    {
        $builder = ($search) ? $this->search($search) : $this->createQueryBuilder();
        // Global
        if ($newsgroup->getAddGlobal()) {
            $builder->addAnd(
                $builder->expr()->addOr($builder->expr()->field('newsgroups.id')->equals($newsgroup->getId()))->addOr(
                    $builder->expr()->field('global')->equals(true)
                )
            );
        } else {
            $builder->field('newsgroups.id')->equals($newsgroup->getId());
        }
        // Dates
        $builder->addAnd(
            $builder->expr()->addOr($builder->expr()->field('date_start')->equals(null))->addOr(
                $builder->expr()->field('date_start')->lt(new \DateTime())
            )
        );
        $builder->addAnd(
            $builder->expr()->addOr($builder->expr()->field('date_end')->equals(null))->addOr(
                $builder->expr()->field('date_end')->gt(new \DateTime())
            )
        );
        // Common
        $builder->field('active')->equals(true);
        $builder->field('delete')->equals(false);

        return $builder->count()->getQuery()->execute();
    }

    /**
     * @param \Fhm\NewsBundle\Entity\NewsGroup $newsgroup
     *
     * @return int
     * @throws \Doctrine\ORM\ORMException
     */
    public function getNewsByGroupAll(\Fhm\NewsBundle\Entity\NewsGroup $newsgroup)
    {
        $builder = $this->createQueryBuilder();
        // Global
        if ($newsgroup->getAddGlobal()) {
            $builder->addAnd(
                $builder->expr()->addOr($builder->expr()->field('newsgroups.id')->equals($newsgroup->getId()))->addOr(
                    $builder->expr()->field('global')->equals(true)
                )
            );
        } else {
            $builder->field('newsgroups.id')->equals($newsgroup->getId());
        }
        // Dates
        $builder->addAnd(
            $builder->expr()->addOr($builder->expr()->field('date_start')->equals(null))->addOr(
                $builder->expr()->field('date_start')->lt(new \DateTime())
            )
        );
        $builder->addAnd(
            $builder->expr()->addOr($builder->expr()->field('date_end')->equals(null))->addOr(
                $builder->expr()->field('date_end')->gt(new \DateTime())
            )
        );
        // Common
        $builder->field('active')->equals(true);
        $builder->field('delete')->equals(false);
        $builder->sort($newsgroup->getSortField(), $newsgroup->getSortOrder());

        return $builder->getQuery()->execute();
    }

    public function findAllParent()
    {
        return $this->createQueryBuilder()->field('parent')->in(array('0', null))->sort('name')->getQuery()->execute(
        )->toArray();
    }
}