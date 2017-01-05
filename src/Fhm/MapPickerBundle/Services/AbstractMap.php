<?php
namespace Fhm\MapPickerBundle\Services;
/**
 * Class MapFranceDomtom
 *
 * @package Fhm\MapPickerBundle\Ressources\maps
 */
abstract class AbstractMap
{
    protected $template;
    protected $parameters;
    private $name;
    private $width;
    private $height;
    private $zones;
    private $document;

    /**
     * AbstractMap constructor.
     */
    public function __construct()
    {
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param $name
     *
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return int
     */
    public function getWidth()
    {
        return $this->width;
    }

    /**
     * @param $width
     *
     * @return $this
     */
    public function setWidth($width)
    {
        $this->width = $width;

        return $this;
    }

    /**
     * @return int
     */
    public function getHeight()
    {
        return $this->height;
    }

    /**
     * @param $height
     *
     * @return $this
     */
    public function setHeight($height)
    {
        $this->height = $height;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getZones()
    {
        return $this->zones;
    }

    /**
     * @param $zones
     *
     * @return $this
     */
    public function setZones($zones)
    {
        $this->zones = $zones;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getDocument()
    {
        return $this->document;
    }

    /**
     * @param $document
     *
     * @return $this
     */
    public function setDocument($document)
    {
        $this->document = $document;

        return $this;
    }

    /**
     * @param string $template
     *
     * @return mixed
     */
    public function getTemplate($template = 'front')
    {
        return $this->template->render('::FhmMapPicker/Map/' . $template . '.html.twig', array(
            'document' => $this->document,
            'name'     => $this->name,
            'width'    => $this->width,
            'height'   => $this->height,
            'zones'    => $this->zones,
            'instance' => $this->_instance(),
            'params'   => $this->_parameter()
        ));
    }

    /**
     * @return \stdClass
     */
    private function _instance()
    {
        $data              = new \stdClass();
        $data->class       = 'Fhm\\MapPickerBundle\\Document\\MapPicker';
        $data->route       = 'mappicker';
        $data->domain      = 'FhmMapPickerBundle';
        $data->translation = 'mappicker';

        return $data;
    }

    /**
     * @return mixed
     */
    private function _parameter()
    {
        return $this->parameters;
    }
}