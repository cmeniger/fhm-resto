<?php
namespace Fhm\HistoricBundle\Service;

use Doctrine\Common\Persistence\ObjectManager;
use Fhm\HistoricBundle\Document\Historic;
use Symfony\Component\Serializer\Serializer;

/**
 * Created by PhpStorm.
 * User: fhm
 * Date: 12/01/17
 * Time: 13:58
 */
class HistoricManager
{
    private $objectManager;

    /**
     * HistoricManager constructor.
     * @param ObjectManager $manager
     */
    public function __construct(ObjectManager $manager)
    {
        $this->objectManager = $manager;
    }

    /**
     * @param $object
     * @return bool|Historic
     */
    public function save($object)
    {
        if ($object instanceof Historic) {
            return false;
        }
        $class = get_class($object);
        $collection = $this->objectManager->getDocumentCollection($class);
        $objectData = $collection->findOne(array('_id' => new \MongoId($object->getId())));
        $json = serialize($objectData);
        $historic = new Historic();
        $historic->setName($object->getName());
        $historic->setObjectStatut($json);
        $historic->setType($class);
        $historic->setObjectId($object->getId());
        $this->objectManager->persist($historic);

        return $historic;
    }

    /**
     * @param $historicId
     */
    public function restore($historicId)
    {
        $historic = $this->objectManager->getRepository('FhmHistoricBundle:Historic')->find($historicId);
        if ($historic instanceof Historic) {
            $collection = $this->objectManager->getDocumentCollection($historic->getType());
            $collection->findAndUpdate(
                array('_id' => new \MongoId($historic->getObjectId())),
                unserialize($historic->getObjectStatut())
            );
        }
    }
}