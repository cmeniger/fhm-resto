<?php

namespace Fhm\Manager;
use Doctrine\ODM\MongoDB\DocumentManager;
use Fhm\FhmBundle\Document\Fhm;

/**
 * Created by PhpStorm.
 * User: reap
 * Date: 13/01/17
 * Time: 14:36
 */
class FhmDocumentManager implements ManagerInterface
{
    protected $manager;

    /**
     * FhmDocumentManager constructor.
     */
    public function __construct(DocumentManager $manager)
    {
        $this->manager = $manager;
    }

    /**
     * @param null $documentName
     * @return \Doctrine\ODM\MongoDB\DocumentRepository
     */
    public function getCurrentRepository($documentName = null)
    {
        if (is_null($documentName)) {
            return $this->manager->getRepository('FhmFhmBundle:Fhm');
        }

        return $this->manager->getRepository($documentName);
    }

    public function getCurrentModelName()
    {
        return Fhm::class;
    }
}