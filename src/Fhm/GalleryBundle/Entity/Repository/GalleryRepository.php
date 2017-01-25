<?php
namespace Fhm\GalleryBundle\Entity\Repository;

use Doctrine\ORM\EntityManager;
use Fhm\FhmBundle\Entity\Repository\FhmRepository;
use Doctrine\ORM\Mapping\ClassMetadata;

/**
 * Class GalleryRepository
 *
 * @package Fhm\GalleryBundle\Entity\Repository
 */
class GalleryRepository extends FhmRepository
{
    /**
     * GalleryRepository constructor.
     * @param EntityManager $dm
     * @param ClassMetadata $class
     */
    public function __construct(EntityManager $dm, ClassMetadata $class)
    {
        parent::__construct($dm, $class);
    }

    /**
     * @param \Fhm\GalleryBundle\Entity\GalleryAlbum $album
     * @param string $search
     * @param int $page
     * @param int $count
     *
     * @return mixed
     * @throws \Doctrine\ORM\ORMException
     */
    public function getByGroupIndex(\Fhm\GalleryBundle\Entity\GalleryAlbum $album, $search = "")
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
     * @param \Fhm\GalleryBundle\Entity\GalleryAlbum $album
     * @param string $search
     *
     * @return mixed
     */
    public function getByGroupCount(\Fhm\GalleryBundle\Entity\GalleryAlbum $album, $search = "")
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
     * @param \Fhm\GalleryBundle\Entity\GalleryAlbum $album
     *
     * @return mixed
     * @throws \Doctrine\ORM\ORMException
     */
    public function getByGroupAll(\Fhm\GalleryBundle\Entity\GalleryAlbum $album)
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