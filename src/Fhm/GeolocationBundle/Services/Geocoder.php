<?php
namespace Fhm\GeolocationBundle\Services;

use Ivory\GoogleMap\Service\Geocoder\GeocoderService;
use Ivory\GoogleMap\Service\Geocoder\Request\GeocoderAddressRequest;
use Ivory\GoogleMap\Service\Geocoder\Request\GeocoderComponentType;
use Ivory\GoogleMap\Service\Geocoder\Request\GeocoderRequestInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class Geocoder
 * @package Fhm\GeolocationBundle\Services
 */
class Geocoder
{
    private $geocoder;
    private $request;

    /**
     * Geocoder constructor.
     * @param GeocoderAddressRequest $request
     * @param GeocoderService $geodecoder
     */
//    public function __construct(GeocoderAddressRequest $request, GeocoderService $geodecoder)
//    {
//        $this->geocoder = $geodecoder;
//        $this->request  = $request;
//    }

    /**
     * @param $address
     *
     * @return array
     */
    public function geocode($address)
    {
        $this->request->setAddress($address);
        $response = $this->geocoder->geocode($this->request);
        $results = $response->getResults();
        $result = array_shift($results);

        return $result ? array(
            $result->getGeometry()->getLocation()->getLatitude(),
            $result->getGeometry()->getLocation()->getLongitude(),
        ) : array(0, 0);
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
        $results = $response->getResults();
        $result = array_shift($results);

        return $result;
    }
}