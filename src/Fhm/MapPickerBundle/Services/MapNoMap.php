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
     *
     * @param \Symfony\Component\Templating\EngineInterface $template
     * @param \Fhm\FhmBundle\Services\Tools                 $tools
     */
    public function __construct(\Symfony\Component\Templating\EngineInterface $template, \Fhm\FhmBundle\Services\Tools $tools)
    {
        $this->fhm_tools = $tools;
        $this->template  = $template;
        $this->setName("nomap");
        $this->setWidth(0);
        $this->setHeight(0);
        $this->setZones(array("01" => ""));
    }
}