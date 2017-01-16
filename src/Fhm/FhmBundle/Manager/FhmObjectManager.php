<?php
/**
 * Created by PhpStorm.
 * User: fhm
 * Date: 16/01/17
 * Time: 17:37
 */
namespace Fhm\FhmBundle\Manager;

use Doctrine\Common\Persistence\ObjectManager;

/**
 * Class FhmObjectManager
 * @package Fhm\FhmBundle\Manager
 */
class FhmObjectManager
{
    protected $manager;

    /**
     * FhmObjectManager constructor.
     * @param ObjectManager $manager
     */
    public function __construct(ObjectManager $manager)
    {
        $this->manager = $manager;
    }

    /**
     * @param string $className
     * @return \Doctrine\Common\Persistence\ObjectRepository
     */
    public function getCurrentRepository($className = 'FhmFhmBundle:Fhm')
    {
        return $this->manager->getRepository($className);
    }

    /**
     * @param string $className
     * @return mixed
     */
    public function getCurrentModelName($className = 'FhmFhmBundle:Fhm')
    {
        $meta = $this->manager->getClassMetadata($className);

        return $meta->getReflectionClass()->name;
    }

    /**
     * @return ObjectManager
     */
    public function getManager()
    {
        return $this->manager;
    }
}