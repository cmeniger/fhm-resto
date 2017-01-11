<?php
namespace Fhm\HistoricBundle\EventListener;

use Doctrine\Common\EventSubscriber;
use Doctrine\ODM\MongoDB\Event\PostFlushEventArgs;
use Doctrine\ODM\MongoDB\Events;
use Fhm\HistoricBundle\Document\Historic;
use Doctrine\Common\Persistence\Event\LifecycleEventArgs;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Serializer\Serializer;

/**
 * Created by PhpStorm.
 * User: fhm
 * Date: 10/01/17
 * Time: 10:05
 */
class HistoricService implements EventSubscriber
{
    private $serailizer;
    private $request;
    private $elementToFlush = [];

    /**
     * HistoricService constructor.
     * @param Serializer $serializer
     * @param RequestStack $requestStack
     */
    public function __construct(Serializer $serializer, RequestStack $requestStack)
    {
        $this->serailizer = $serializer;
        $this->request = $requestStack->getCurrentRequest();
    }

    /**
     * @param LifecycleEventArgs $args
     */
    public function preUpdate(LifecycleEventArgs $args)
    {
        $object = $args->getObject();
        $manager = $args->getObjectManager();
        if (method_exists($object, 'setUserUpdate') && method_exists($object, 'setDateUpdate')) {
            $object->setUserUpdate($this->request->getUser());
            $object->setDateUpdate(new \DateTime());
        }
        $this->save($object, $manager);
    }

    /**
     * @param LifecycleEventArgs $args
     */
    public function prePersist(LifecycleEventArgs $args)
    {
        $object = $args->getObject();
        $manager = $args->getObjectManager();
        $class = get_class($object);
        if (method_exists($object, 'setUserCreate') && method_exists($object, 'setAlias')) {
            $object->setUserCreate($this->request->getUser());
            $object->setAlias($this->getAlias($object, $manager->getRepository($class)));
            $object->setDateCreate(new \DateTime());
        }
        $this->save($object, $manager);
    }


    /**
     * @param $object
     * @param $manager
     * @return bool
     */
    private function save($object, $manager)
    {
        if ($object instanceof Historic) {
            return false;
        }
        $class = get_class($object);
        $collection = $manager->getDocumentCollection($class);
        $objectData = $collection->findOne(array('_id' => new \MongoId($object->getId())));
        $json = $this->serailizer->serialize($objectData, 'json');
        if (!in_array($object, $this->elementToFlush)) {
            $historic = new Historic();
            $historic->setName($object->getName());
            $historic->setObjectStatut($json);
            $historic->setType($class);
            $historic->setObjectId($object->getId());
            $this->elementToFlush[] = $object;
            $this->elementToFlush[] = $historic;
        }
    }

    /**
     * @param PostFlushEventArgs $event
     */
    public function postFlush(PostFlushEventArgs $event)
    {
        if (!empty($this->elementToFlush)) {
            $em = $event->getObjectManager();
            foreach ($this->elementToFlush as $object) {
                $em->persist($object);
            }
            $em->flush();
            $this->elementToFlush = [];
        }
    }

    /***
     * @return array
     */
    public function getSubscribedEvents()
    {
        return [Events::prePersist, Events::preUpdate, Events::postFlush];
    }

    /**
     * @param $object
     * @param $repository
     * @return mixed|string
     */
    public function getAlias($object, $repository)
    {
        $alias = "";
        $unique = false;
        $code = 0;
        $replace = array(
            'À' => 'a',
            'Á' => 'a',
            'Â' => 'a',
            'Ä' => 'a',
            'à' => 'a',
            'á' => 'a',
            'â' => 'a',
            'ä' => 'a',
            '@' => 'a',
            'È' => 'e',
            'É' => 'e',
            'Ê' => 'e',
            'Ë' => 'e',
            'è' => 'e',
            'é' => 'e',
            'ê' => 'e',
            'ë' => 'e',
            '€' => 'e',
            'Ì' => 'i',
            'Í' => 'i',
            'Î' => 'i',
            'Ï' => 'i',
            'ì' => 'i',
            'í' => 'i',
            'î' => 'i',
            'ï' => 'i',
            'Ò' => 'o',
            'Ó' => 'o',
            'Ô' => 'o',
            'Ö' => 'o',
            'ò' => 'o',
            'ó' => 'o',
            'ô' => 'o',
            'ö' => 'o',
            'Ù' => 'u',
            'Ú' => 'u',
            'Û' => 'u',
            'Ü' => 'u',
            'ù' => 'u',
            'ú' => 'u',
            'û' => 'u',
            'ü' => 'u',
            'µ' => 'u',
            'Œ' => 'oe',
            'œ' => 'oe',
            '$' => 's',
        );
        while ($alias == "" || !$unique) {
            $alias = method_exists($object, 'getName')? $object->getName(): "";
            $alias = strtr($alias, $replace);
            $alias = preg_replace('#[^A-Za-z0-9]+#', '-', $alias);
            $alias = trim($alias, '-');
            $alias = strtolower($alias);
            $alias = ($code > 0) ? $alias.'-'.$code : $alias;
            $unique = method_exists($repository, 'isUnique')?$repository->isUnique($object->getId(), $alias):$alias;
            $code++;
        }

        return $alias;
    }
}