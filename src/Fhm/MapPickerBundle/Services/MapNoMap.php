<?php
namespace Fhm\MapPickerBundle\Services;

/**
 * Class MapFranceRegion
 *
 * @package Fhm\MapPickerBundle\Ressources\maps
 */
class MapNoMap extends AbstractMap
{
    /**
     * MapNoMap constructor.
     * @param \Symfony\Component\Templating\EngineInterface $template
     * @param $parameters
     */
    public function __construct(\Symfony\Component\Templating\EngineInterface $template, $parameters)
    {
        $this->parameters = $parameters;
        $this->template  = $template;
        $this->setName("nomap");
        $this->setWidth(0);
        $this->setHeight(0);
        $this->setZones(array("01" => ""));
    }
}