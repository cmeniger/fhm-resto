<?php
namespace Fhm\GeolocationBundle\Services;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Templating\EngineInterface;

/**
 * Class Marker
 * @package Fhm\GeolocationBundle\Services
 */
class Marker
{
    private $container;
    private $template;
    private $document;
    private $latitude;
    private $longitude;
    private $icon;
    private $popup;
    private $filter;
    private $title;

    /**
     * Marker constructor.
     * @param EngineInterface $template
     * @param ContainerInterface $container
     */
    public function __construct(EngineInterface $template, ContainerInterface $container)
    {
        $this->template = $template;
        $this->container = $container;
        $this->popup = '::FhmGeolocation/Map/popup.html.twig';
        $this->filter = array();
    }

    /**
     * Set document
     *
     * @param object $document
     *
     * @return self
     */
    public function setDocument($document)
    {
        $this->document = $document;

        return $this;
    }

    /**
     * Get document
     *
     * @return object $document
     */
    public function getDocument()
    {
        return $this->document;
    }

    /**
     * Set latitude
     *
     * @param string $latitude
     *
     * @return self
     */
    public function setLatitude($latitude)
    {
        $this->latitude = $latitude;

        return $this;
    }

    /**
     * Get latitude
     *
     * @return string $latitude
     */
    public function getLatitude()
    {
        return $this->latitude;
    }

    /**
     * Set longitude
     *
     * @param string $longitude
     *
     * @return self
     */
    public function setLongitude($longitude)
    {
        $this->longitude = $longitude;

        return $this;
    }

    /**
     * Get longitude
     *
     * @return string $longitude
     */
    public function getLongitude()
    {
        return $this->longitude;
    }

    /**
     * Set icon
     *
     * @param string $icon
     *
     * @return self
     */
    public function setIcon($icon)
    {
        $this->icon = $icon;

        return $this;
    }

    /**
     * Get icon
     *
     * @return string $icon
     */
    public function getIcon()
    {
        return $this->_parameter('marker_icon_folder').$this->icon;
    }

    /**
     * Set title
     *
     * @param string $title
     *
     * @return self
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string $title
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set popup
     *
     * @param string $popup
     *
     * @return self
     */
    public function setPopup($popup)
    {
        $this->popup = $popup;

        return $this;
    }

    /**
     * Get popup
     *
     * @return string $popup
     */
    public function getPopup()
    {
        return $this->template->render($this->popup, array('document' => $this->document));
    }

    /**
     * Set filter
     *
     * @param string $filter
     *
     * @return self
     */
    public function setFilter($filter)
    {
        $this->filter = $filter;

        return $this;
    }

    /**
     * Get filter
     *
     * @return string $filter
     */
    public function getFilter()
    {
        return $this->filter;
    }

    /**
     * Parameter
     */
    private function _parameter($route, $parent = 'fhm_map')
    {
        $parameters = $this->container->getParameter($parent);
        $value = $parameters;
        foreach ((array)$route as $sub) {
            $value = $value[$sub];
        }

        return $value;
    }
}
