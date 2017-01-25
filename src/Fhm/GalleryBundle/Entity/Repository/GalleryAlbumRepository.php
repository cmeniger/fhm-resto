<?php
namespace Fhm\GalleryBundle\Entity\Repository;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping\ClassMetadata;
use Fhm\FhmBundle\Entity\Repository\FhmRepository;

/**
 * Class GalleryAlbumRepository
 *
 * @package Fhm\GalleryBundle\Entity\Repository
 */
class GalleryAlbumRepository extends FhmRepository
{

    /**
     * GalleryAlbumRepository constructor.
     * @param EntityManager $dm
     * @param ClassMetadata $class
     */
    public function __construct(EntityManager $dm, ClassMetadata $class)
    {
        parent::__construct($dm, $class);
    }
}