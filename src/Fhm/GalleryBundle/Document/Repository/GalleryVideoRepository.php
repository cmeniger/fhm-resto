<?php
namespace Fhm\GalleryBundle\Document\Repository;

use Fhm\FhmBundle\Document\Repository\FhmRepository;
use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\ODM\MongoDB\UnitOfWork;
use Doctrine\ODM\MongoDB\Mapping\ClassMetadata;

/**
 * Class GalleryVideoRepository
 *
 * @package Fhm\GalleryBundle\Repository
 */
class GalleryVideoRepository extends FhmRepository
{
    /**
     * GalleryVideoRepository constructor.
     * @param DocumentManager $dm
     * @param UnitOfWork $uow
     * @param ClassMetadata $class
     */
    public function __construct(DocumentManager $dm, UnitOfWork $uow, ClassMetadata $class)
    {
        parent::__construct($dm, $uow, $class);
    }

    /**
     * @param \Fhm\GalleryBundle\Document\Gallery $gallery
     * @param string $search
     * @param int $page
     * @param int $count
     *
     * @return mixed
     * @throws \Doctrine\ODM\MongoDB\MongoDBException
     */
    public function getByGroupIndex(\Fhm\GalleryBundle\Document\Gallery $gallery, $search = "")
    {
        $builder = ($search) ? $this->search($search) : $this->createQueryBuilder();
        // Global
        if ($gallery->getAddGlobalVideo()) {
            $builder->addAnd(
                $builder->expr()->addOr($builder->expr()->field('galleries.id')->equals($gallery->getId()))->addOr(
                    $builder->expr()->field('global')->equals(true)
                )
            );
        } else {
            $builder->field('galleries.id')->equals($gallery->getId());
        }
        // Common
        $builder->field('active')->equals(true);
        $builder->field('delete')->equals(false);
        $builder->sort($gallery->getOrderVideoField(), $gallery->getOrderVideoOrder());

        return $builder->getQuery()->execute()->toArray();
    }

    /**
     * @param \Fhm\GalleryBundle\Document\Gallery $gallery
     * @param string $search
     *
     * @return mixed
     */
    public function getByGroupCount(\Fhm\GalleryBundle\Document\Gallery $gallery, $search = "")
    {
        $builder = ($search) ? $this->search($search) : $this->createQueryBuilder();
        // Global
        if ($gallery->getAddGlobalVideo()) {
            $builder->addAnd(
                $builder->expr()->addOr($builder->expr()->field('galleries.id')->equals($gallery->getId()))->addOr(
                    $builder->expr()->field('global')->equals(true)
                )
            );
        } else {
            $builder->field('items.id')->equals($gallery->getId());
        }
        // Common
        $builder->field('active')->equals(true);
        $builder->field('delete')->equals(false);

        return $builder->count()->getQuery()->execute();
    }

    /**
     * @param \Fhm\GalleryBundle\Document\Gallery $gallery
     *
     * @return mixed
     * @throws \Doctrine\ODM\MongoDB\MongoDBException
     */
    public function getByGroupAll(\Fhm\GalleryBundle\Document\Gallery $gallery)
    {
        $builder = $this->createQueryBuilder();
        // Global
        if ($gallery->getAddGlobalVideo()) {
            $builder->addAnd(
                $builder->expr()->addOr($builder->expr()->field('galleries.id')->equals($gallery->getId()))->addOr(
                    $builder->expr()->field('global')->equals(true)
                )
            );
        } else {
            $builder->field('galleries.id')->equals($gallery->getId());
        }
        // Common
        $builder->field('active')->equals(true);
        $builder->field('delete')->equals(false);
        $builder->sort($gallery->getOrderVideoField(), $gallery->getOrderVideoOrder());

        return $builder->getQuery()->execute();
    }
}