<?php

namespace Fhm\GeolocationBundle\Services;

use Ivory\GoogleMap\Service\Geocoder\Request\GeocoderAddressRequest;
use Symfony\Component\DependencyInjection\ContainerInterface;

class Geocoder
{
    private $container;
    private $geocoder;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        $this->geocoder  = $this->container->get('ivory.google_map.geocoder');
    }

    public function geocode($address)
    {
        $request = new GeocoderAddressRequest($address);
        $response = $this->geocoder->geocode($request);
        $results  = $response->getResults();
        $result   = array_shift($results);

        return $result ? array($result->getGeometry()->getLocation()->getLatitude(), $result->getGeometry()->getLocation()->getLongitude()) : array(0, 0);
    }

    public function reverse($latitude, $longitude)
    {
        $response = $this->geocoder->reverse($latitude, $longitude);
    }

    /**
     * Parameter
     */
    private function _parameter($route, $parent = 'fhm_geocoder')
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