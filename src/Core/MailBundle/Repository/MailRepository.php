<?php
namespace Core\MailBundle\Repository;

use Core\FhmBundle\Repository\FhmRepository;
use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\ODM\MongoDB\UnitOfWork;
use Doctrine\ODM\MongoDB\Mapping\ClassMetadata;

/**
 * MailRepository
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class MailRepository extends FhmRepository
{
    /**
     * Constructor
     */
    public function __construct(DocumentManager $dm, UnitOfWork $uow, ClassMetadata $class)
    {
        parent::__construct($dm, $uow, $class);
    }
}