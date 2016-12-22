<?php
/**
 * Created by PhpStorm.
 * User: rcisse
 * Date: 21/12/16
 * Time: 14:18
 */
namespace Fhm\FhmBundle\Services;

use Fhm\FhmBundle\Document\Historic;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Serializer\Serializer;

/**
 * Class HistoricService
 *
 * @package Fhm\FhmBundle\Services
 */
class HistoricService
{
    private $serializer;
    private $dm;

    /**
     * HistoricService constructor.
     * @param Serializer $serializer
     * @param $dm
     */
    public function __construct(Serializer $serializer, $dm)
    {
        $this->dm = $dm;
        $this->serializer = $serializer;
    }

    /**
     * @param $object
     * @return Historic
     */
    public function save($object)
    {
        $objectContent = $this->serializer->serialize(get_object_vars($object), 'json');
        $historic = new Historic();
        $historic->setObjectId($object->getId());
        $historic->setEtat($objectContent);
        $historic->setClass(get_class($object));
        $this->dm->persist($historic);
        $this->dm->flush();

        return $historic;
    }

    /**
     * @param $objectId
     *
     * @return ContainerInterface
     */
    public function restaure($id)
    {
        //var_dump($objectId);
        $historic = $this->dm->getRepository('FhmFhmBundle:Historic')->find($id);
        $source = explode('\\', $historic->getClass());
        $currentDocument = $this->dm->getRepository($source[0].$source[1].':'.$source[3])->find(
                $historic->getObjectId()
            );
        $document = $this->serializer->deserialize($historic->getEtat(), $historic->getClass(), 'json');
//        $result=$this->serializer->deserialize($historic->getEtat(), $historic->getClass(),
//            'json', array('object' => $currentDocument));
        var_dump($document);
        die;

        return $this->container;
    }

    /**
     * @param $object
     *
     * @return array
     */
    public function getAllHistoricsByObject($object)
    {
        $histories = $this->dm->getRepository('FhmFhmBundle:Historic')->findBy(array('objectId' => $object->getId()));

        return $histories;
    }

}