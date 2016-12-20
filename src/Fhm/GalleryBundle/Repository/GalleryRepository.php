<?php
namespace Fhm\GalleryBundle\Repository;

use Fhm\FhmBundle\Repository\FhmRepository;
use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\ODM\MongoDB\UnitOfWork;
use Doctrine\ODM\MongoDB\Mapping\ClassMetadata;

/**
 * Class GalleryRepository
 *
 * @package Fhm\GalleryBundle\Repository
 */
class GalleryRepository extends FhmRepository
{
    /**
     * GalleryRepository constructor.
     * @param DocumentManager $dm
     * @param UnitOfWork $uow
     * @param ClassMetadata $class
     */
    public function __construct(DocumentManager $dm, UnitOfWork $uow, ClassMetadata $class)
    {
        parent::__construct($dm, $uow, $class);
    }

    /**
     * @param \Fhm\GalleryBundle\Document\GalleryAlbum $album
     * @param string $search
     * @param int $page
     * @param int $count
     *
     * @return mixed
     * @throws \Doctrine\ODM\MongoDB\MongoDBException
     */
    public function getByGroupIndex(\Fhm\GalleryBundle\Document\GalleryAlbum $album, $search = "")
    {
        $builder = ($search) ? $this->search($search) : $this->createQueryBuilder();
        // Global
        if ($album->getAddGlobal()) {
            $builder->addAnd(
                $builder->expr()->addOr($builder->expr()->field('albums.id')->equals($album->getId()))->addOr(
                    $builder->expr()->field('global')->equals(true)
                )
            );
        } else {
            $builder->field('albums.id')->equals($album->getId());
        }
        // Common
        $builder->field('active')->equals(true);
        $builder->field('delete')->equals(false);
        $builder->sort($album->getSortField(), $album->getSortOrder());

        return $builder->getQuery()->execute()->toArray();
    }

    /**
     * @param \Fhm\GalleryBundle\Document\GalleryAlbum $album
     * @param string $search
     *
     * @return mixed
     */
    public function getByGroupCount(\Fhm\GalleryBundle\Document\GalleryAlbum $album, $search = "")
    {
        $builder = ($search) ? $this->search($search) : $this->createQueryBuilder();
        // Global
        if ($album->getAddGlobal()) {
            $builder->addAnd(
                $builder->expr()->addOr($builder->expr()->field('albums.id')->equals($album->getId()))->addOr(
                    $builder->expr()->field('global')->equals(true)
                )
            );
        } else {
            $builder->field('albums.id')->equals($album->getId());
        }
        // Common
        $builder->field('active')->equals(true);
        $builder->field('delete')->equals(false);

        return $builder->count()->getQuery()->execute();
    }

    /**
     * @param \Fhm\GalleryBundle\Document\GalleryAlbum $album
     *
     * @return mixed
     * @throws \Doctrine\ODM\MongoDB\MongoDBException
     */
    public function getByGroupAll(\Fhm\GalleryBundle\Document\GalleryAlbum $album)
    {
        $builder = $this->createQueryBuilder();
        // Global
        if ($album->getAddGlobal()) {
            $builder->addAnd(
                $builder->expr()->addOr($builder->expr()->field('albums.id')->equals($album->getId()))->addOr(
                    $builder->expr()->field('global')->equals(true)
                )
            );
        } else {
            $builder->field('albums.id')->equals($album->getId());
        }
        // Common
        $builder->field('active')->equals(true);
        $builder->field('delete')->equals(false);
        $builder->sort($album->getSortField(), $album->getSortOrder());

        return $builder->getQuery()->execute();
    }
}