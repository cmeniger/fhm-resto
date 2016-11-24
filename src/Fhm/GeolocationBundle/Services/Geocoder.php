<?php
namespace Fhm\GeolocationBundle\Services;

use Symfony\Component\DependencyInjection\ContainerInterface;

class Geocoder
{
    private $geocoder;
    private $request;

    /**
     * Geocoder constructor.
     *
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        $this->geocoder  = $this->container->get('ivory.google_map.geocoder');
        $this->request   = $this->container->get('ivory.google_map.geocoder');
       // $this->request   = $this->container->get('ivory_google_map.geocoder_request');
    }

    /**
     * @param $address
     *
     * @return array
     */
    public function geocode($address)
    {
        $this->request->setAddress($address);
        $response = $this->geocoder->geocode($this->request);
        $results  = $response->getResults();
        $result   = array_shift($results);

        return $result ? array($result->getGeometry()->getLocation()->getLatitude(), $result->getGeometry()->getLocation()->getLongitude()) : array(0, 0);
    }

    /**
     * @param $latitude
     * @param $longitude
     *
     * @return mixed
     */
    public function reverse($latitude, $longitude)
    {
        $response = $this->geocoder->reverse($latitude, $longitude);
        $results  = $response->getResults();
        $result   = array_shift($results);

        return $result;
    }
}