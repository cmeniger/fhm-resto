<?php
namespace Fhm\SiteBundle\Repository;

use Fhm\FhmBundle\Repository\FhmRepository;
use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\ODM\MongoDB\UnitOfWork;
use Doctrine\ODM\MongoDB\Mapping\ClassMetadata;

/**
 * SiteRepository
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class SiteRepository extends FhmRepository
{
    /**
     * Constructor
     */
    public function __construct(DocumentManager $dm, UnitOfWork $uow, ClassMetadata $class)
    {
        parent::__construct($dm, $uow, $class);
    }

    /**
     * @return mixed
     */
    public function getDefault()
    {
        $builder = $this->createQueryBuilder();
        // Language
        if($this->language)
        {
            $builder->field('languages')->in((array) $this->language);
        }

        return $builder
            ->field('default')->equals(true)
            ->limit(1)
            ->getQuery()
            ->getSingleResult();
    }

    /**
     *
     */
    public function resetDefault()
    {
        $builder = $this->createQueryBuilder();
        // Language
        if($this->language)
        {
            $builder->field('languages')->in((array) $this->language);
        }
        $builder
            ->field('default')->equals(true)
            ->field('default')->set(false)
            ->update()
            ->multiple(true)
            ->getQuery()
            ->execute();
    }
}