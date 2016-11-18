<?php
namespace Fhm\MapPickerBundle\Services;
/**
 * Class MapFranceDomtom
 *
 * @package Fhm\MapPickerBundle\Ressources\maps
 */
abstract class AbstractMap
{
    protected $container;
    protected $template;
    private $name;
    private $width;
    private $height;
    private $zones;
    private $params;
    private $document;

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
            'params'   => $this->_parameter(array())
        ));
    }

    /**
     * @return mixed
     */
    private function _repository()
    {
        return $this->container->get('doctrine_mongodb')->getManager()->getRepository('FhmMapPickerBundle:MapPicker');
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
     * @param        $route
     * @param string $parent
     *
     * @return mixed
     */
    private function _parameter($route, $parent = 'fhm_mappicker')
    {
        $parameters = $this->container->getParameter($parent);
        $value      = $parameters;
        foreach((array) $route as $sub)
        {
            $value = $value[$sub];
        }

        return $value;
    }
}