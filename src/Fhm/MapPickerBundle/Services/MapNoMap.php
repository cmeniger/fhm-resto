<?php
namespace Fhm\MapPickerBundle\Services;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Templating\EngineInterface;

/**
 * Class MapFranceRegion
 *
 * @package Fhm\MapPickerBundle\Ressources\maps
 */
class MapNoMap extends AbstractMap
{
    public function __construct(EngineInterface $template, ContainerInterface $container)
    {
        $this->container = $container;
        $this->template  = $template;
        $this->setName("nomap");
        $this->setWidth(0);
        $this->setHeight(0);
        $this->setZones(array("01" => ""));
    }
}