<?php

namespace Fhm\Manager;
use Doctrine\ORM\EntityManager;
use Fhm\FhmBundle\Entity\Fhm;

/**
 * Created by PhpStorm.
 * User: reap
 * Date: 13/01/17
 * Time: 14:36
 */
class FhmEntityManager implements ManagerInterface
{

    protected $manager;

    /**
     * FhmEntityManager constructor.
     */
    public function __construct(EntityManager $manager)
    {
        $this->manager = $manager;
    }

    /**
     * @param null $entityName
     * @return \Doctrine\ORM\EntityRepository
     */
    public function getCurrentRepository($entityName = null)
    {
        if (is_null($entityName)) {
            return $this->manager->getRepository('FhmFhmBundle:Fhm');
        }
        return $this->manager->getRepository($entityName);
    }

    public function getCurrentModelName()
    {
        return Fhm::class;
    }
}