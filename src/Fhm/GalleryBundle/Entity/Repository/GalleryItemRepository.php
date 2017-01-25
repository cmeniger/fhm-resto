<?php
namespace Fhm\GalleryBundle\Entity\Repository;

use Fhm\FhmBundle\Entity\Repository\FhmRepository;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\EntityManager;

/**
 * Class GalleryItemRepository
 *
 * @package Fhm\GalleryBundle\Entity\Repository
 */
class GalleryItemRepository extends FhmRepository
{
    /**
     * GalleryItemRepository constructor.
     * @param EntityManager $dm
     * @param ClassMetadata $class
     */
    public function __construct(EntityManager $dm, ClassMetadata $class)
    {
        parent::__construct($dm, $class);
    }

    /**
     * @param \Fhm\GalleryBundle\Entity\Gallery $gallery
     * @param string $search
     *
     * @return mixed
     * @throws \Doctrine\ORM\ORMException
     */
    public function getByGroupIndex(\Fhm\GalleryBundle\Entity\Gallery $gallery, $search = "")
    {
        $builder = ($search) ? $this->search($search) : $this->createQueryBuilder();
        // Global
        if ($gallery->getAddGlobalItem()) {
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
        $builder->sort($gallery->getOrderItemField(), $gallery->getOrderItemOrder());

        return $builder->getQuery()->execute()->toArray();
    }

    /**
     * @param \Fhm\GalleryBundle\Entity\Gallery $gallery
     * @param string $search
     *
     * @return mixed
     */
    public function getByGroupCount(\Fhm\GalleryBundle\Entity\Gallery $gallery, $search = "")
    {
        $builder = ($search) ? $this->search($search) : $this->createQueryBuilder();
        // Global
        if ($gallery->getAddGlobalItem()) {
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
     * @param \Fhm\GalleryBundle\Entity\Gallery $gallery
     *
     * @return mixed
     * @throws \Doctrine\ORM\ORMException
     */
    public function getByGroupAll(\Fhm\GalleryBundle\Entity\Gallery $gallery)
    {
        $builder = $this->createQueryBuilder();
        // Global
        if ($gallery->getAddGlobalItem()) {
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
        $builder->sort($gallery->getOrderItemField(), $gallery->getOrderItemOrder());

        return $builder->getQuery()->execute();
    }
}