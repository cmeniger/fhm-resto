<?php
namespace Fhm\FhmBundle\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class Schedules
 *
 * @package Fhm\FhmBundle\Services
 */
class SchedulesTransformer implements DataTransformerInterface
{
    protected $container;
    protected $service;

    /**
     * SchedulesTransformer constructor.
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        $this->service   = $container->get('fhm_schedules');
    }

    /**
     * @inheritdoc
     */
    public function transform($value)
    {
        if ($value === null) {
            return array();
        }

        return $this->service->entityToForm($value);
    }

    /**
     * @inheritdoc
     */
    public function reverseTransform($value)
    {
        if ($value === null) {
            return array();
        }

        return $this->service->formToEntity($value);
    }
}