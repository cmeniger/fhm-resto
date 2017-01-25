<?php
namespace Fhm\GalleryBundle\Document\Repository;

use Fhm\FhmBundle\Document\Repository\FhmRepository;
use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\ODM\MongoDB\UnitOfWork;
use Doctrine\ODM\MongoDB\Mapping\ClassMetadata;

/**
 * Class GalleryAlbumRepository
 *
 * @package Fhm\GalleryBundle\Repository
 */
class GalleryAlbumRepository extends FhmRepository
{
    /**
     * GalleryAlbumRepository constructor.
     * @param DocumentManager $dm
     * @param UnitOfWork $uow
     * @param ClassMetadata $class
     */
    public function __construct(DocumentManager $dm, UnitOfWork $uow, ClassMetadata $class)
    {
        parent::__construct($dm, $uow, $class);
    }
}