<?php

namespace Fhm\FhmBundle\Form\DataTransformer;

use Fhm\FhmBundle\Services\Schedules;
use Symfony\Component\Form\DataTransformerInterface;

/**
 * Class Schedules
 *
 * @package Fhm\FhmBundle\Services
 */
class SchedulesTransformer implements DataTransformerInterface
{
    protected $service;

    /**
     * SchedulesTransformer constructor.
     *
     * @param Schedules $schedules
     */
    public function __construct(\Fhm\FhmBundle\Services\Schedules $schedules)
    {
        $this->service = $schedules;
    }

    /**
     * @inheritdoc
     */
    public function transform($value)
    {
        if($value === null)
        {
            return array();
        }

        return $this->service->entityToForm($value);
    }

    /**
     * @inheritdoc
     */
    public function reverseTransform($value)
    {
        if($value === null)
        {
            return array();
        }

        return $this->service->formToEntity($value);
    }
}