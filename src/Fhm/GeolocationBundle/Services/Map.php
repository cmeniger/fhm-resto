<?php
namespace Fhm\GeolocationBundle\Services;

use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class Map
 * @package Fhm\GeolocationBundle\Services
 */
class Map
{
    private $container;
    private $map;
    private $cluster;
    private $filter;

    /**
     * Map constructor.
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        $this->map = $this->container->get('ivory_google_map.map');
        $this->cluster = $this->container->get('ivory_google_map.marker_cluster');
        $this->filter = array();
    }

    /**
     * Generate Map
     *
     * @param array $markers
     * @param array $options
     *
     * @return object $map
     */
    public function generate($markers, $options = array())
    {
        $options = array_merge(array('cluster' => array(), 'control' => array()), $options);
        foreach ($markers as $marker) {
            $this->addMarker($marker);
        }
        $this->setMarkerCluster($options['cluster']);
        $this->setControl($options['control']);

        return $this->map;
    }

    /**
     * Get filters
     *
     * @param string $filter
     *
     * @return array $filters
     */
    public function getFilters($filter = '')
    {
        return ($filter == '') ? json_encode($this->filter) : json_encode($this->filter[$filter]);
    }

    /**
     * Get cluster
     *
     * @return object $cluster
     */
    public function getCluster()
    {
        return $this->cluster;
    }

    /**
     * Add Marker
     *
     * @param object $marker
     *
     * @return self
     */
    public function addMarker($marker)
    {
        $obj = $this->container->get('ivory_google_map.marker');
        $obj->setPosition($marker->getLatitude(), $marker->getLongitude(), true);
        $obj->setOption('title', $marker->getTitle());
        $this->setMarkerPopup($obj, $marker);
        $this->setMarkerIcon($obj, $marker);
        $this->cluster->addMarker($obj);
        foreach ($marker->getFilter() as $markerFilter) {
            $this->filter[$markerFilter][] = $obj->getJavascriptVariable();
        }

        return $this;
    }

    /**
     * Set Info window
     *
     * @param object $obj
     * @param object $marker
     *
     * @return self
     */
    public function setMarkerPopup(&$obj, $marker)
    {
        $infoWindow = $this->container->get('ivory_google_map.info_window');
        $infoWindow->setContent($marker->getPopup());
        $obj->setInfoWindow($infoWindow);

        return $this;
    }

    /**
     * Set Icon
     *
     * @param object $obj
     * @param object $marker
     *
     * @return self
     */
    public function setMarkerIcon(&$obj, $marker)
    {
        $markerImage = $this->container->get('ivory_google_map.marker_image');
        $markerImage->setUrl($marker->getIcon());
        $markerImage->setSize($this->_parameter('marker_width'), $this->_parameter('marker_height'), "px", "px");
        $markerImage->setScaledSize($this->_parameter('marker_width'), $this->_parameter('marker_height'), "px", "px");
        $obj->setIcon($markerImage);

        return $this;
    }

    /**
     * Set Marker Cluster
     *
     * @param array $options
     *
     * @return self
     */
    public function setMarkerCluster($options = array())
    {
        $default = array(
            'gridSize' => $this->_parameter('cluster_grid'),
        );
        $this->cluster->setOptions(array_merge($default, $options));
        $this->cluster->setType('marker_cluster');
        $this->map->setMarkerCluster($this->cluster);

        return $this;
    }

    /**
     * Set Control
     *
     * @param array $options
     *
     * @return self
     */
    public function setControl($options = array())
    {
        $default = array(
            'pan' => array(
                'visible' => $this->_parameter(array('controls', 'pan', 'visible')),
                'position' => $this->_parameter(array('controls', 'pan', 'position')),
            ),
            'rotate' => array(
                'visible' => $this->_parameter(array('controls', 'rotate', 'visible')),
                'position' => $this->_parameter(array('controls', 'rotate', 'position')),
            ),
            'scale' => array(
                'visible' => $this->_parameter(array('controls', 'scale', 'visible')),
                'position' => $this->_parameter(array('controls', 'scale', 'position')),
                'style' => $this->_parameter(array('controls', 'scale', 'style')),
            ),
            'streetView' => array(
                'visible' => $this->_parameter(array('controls', 'streetView', 'visible')),
                'position' => $this->_parameter(array('controls', 'streetView', 'position')),
            ),
            'zoom' => array(
                'visible' => $this->_parameter(array('controls', 'zoom', 'visible')),
                'position' => $this->_parameter(array('controls', 'zoom', 'position')),
                'style' => $this->_parameter(array('controls', 'zoom', 'style')),
            ),
            'mapType' => array(
                'visible' => $this->_parameter(array('controls', 'mapType', 'visible')),
                'position' => $this->_parameter(array('controls', 'mapType', 'position')),
                'style' => 'default',
                'type' => array('roadmap', 'satellite'),
            ),
        );
        $controls = array_merge($default, $options);
        !$controls['pan']['visible'] ? $this->map->setMapOption('panControl', false) : $this->map->setPanControl(
            $controls['pan']['position']
        );
        !$controls['rotate']['visible'] ? $this->map->setMapOption(
            'rotateControl',
            false
        ) : $this->map->setRotateControl($controls['rotate']['position']);
        !$controls['scale']['visible'] ? $this->map->setMapOption('scaleControl', false) : $this->map->setScaleControl(
            $controls['scale']['position'],
            $controls['scale']['style']
        );
        !$controls['streetView']['visible'] ? $this->map->setMapOption(
            'streetViewControl',
            false
        ) : $this->map->setStreetViewControl($controls['streetView']['position']);
        !$controls['zoom']['visible'] ? $this->map->setMapOption('zoomControl', false) : $this->map->setZoomControl(
            $controls['zoom']['position'],
            $controls['zoom']['style']
        );
        !$controls['mapType']['visible'] ? $this->map->setMapOption(
            'mapTypeControl',
            false
        ) : $this->map->setMapTypeControl(
            $controls['mapType']['type'],
            $controls['mapType']['position'],
            $controls['mapType']['style']
        );

        return $this;
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