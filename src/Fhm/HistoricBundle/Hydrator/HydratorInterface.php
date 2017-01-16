<?php

namespace Fhm\HistoricBundle\Hydrator;

/**
 * Interface HydratorInterface
 * @package Fhm\HistoricBundle\Hydrator
 */
interface HydratorInterface
{
    /**
     * @param $object
     * @param array $array
     * @return object
     */
    public function hydrate($object, array $array);

    /**
     * @param $object
     * @return array
     */
    public function extract($object);
}