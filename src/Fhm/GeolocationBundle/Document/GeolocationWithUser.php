<?php

namespace Fhm\GeolocationBundle\Document;

use Fhm\FhmBundle\Document\FhmWithUser;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Geolocation
 */
class GeolocationWithUser extends FhmWithUser
{
    /**
     * Service geocoder
     */
    private $geocoder;

    /**
     * @MongoDB\Field(type="string")
     */
    protected $address_main;

    /**
     * @MongoDB\Field(type="string")
     */
    protected $address_additional;

    /**
     * @MongoDB\Field(type="string")
     */
    protected $zip_code;

    /**
     * @MongoDB\Field(type="string")
     */
    protected $city;

    /**
     * @MongoDB\Field(type="string")
     */
    protected $country;

    /**
     * @MongoDB\Field(type="string")
     */
    protected $latitude;

    /**
     * @MongoDB\Field(type="string")
     */
    protected $longitude;

    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        global $kernel;
        $kernel         = (get_class($kernel) == 'AppCache') ? $kernel->getKernel() : $kernel;
        $this->geocoder = $kernel->getContainer()->get('fhm_geocoder');
    }

    /**
     * Get geocode
     *
     * @return Geocoder
     */
    public function getGeocoder()
    {
        return $this->geocoder;
    }

    /**
     * Set main address
     *
     * @param string $address_main
     *
     * @return self
     */
    public function setAddressMain($address_main)
    {
        $this->address_main = $address_main;

        return $this;
    }

    /**
     * Get main address
     *
     * @return string $address_main
     */
    public function getAddressMain()
    {
        return $this->address_main;
    }

    /**
     * Set additional address
     *
     * @param string $address_additional
     *
     * @return self
     */
    public function setAddressAdditional($address_additional)
    {
        $this->address_additional = $address_additional;

        return $this;
    }

    /**
     * Get additional address
     *
     * @return string $address_additional
     */
    public function getAddressAdditional()
    {
        return $this->address_additional;
    }

    /**
     * Set zip code
     *
     * @param string $zip_code
     *
     * @return self
     */
    public function setZipCode($zip_code)
    {
        $this->zip_code = $zip_code;

        return $this;
    }

    /**
     * Get zip code
     *
     * @return string $zip_code
     */
    public function getZipCode()
    {
        return $this->zip_code;
    }

    /**
     * Set city
     *
     * @param string $city
     *
     * @return self
     */
    public function setCity($city)
    {
        $this->city = $city;

        return $this;
    }

    /**
     * Get city
     *
     * @return string $city
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * Set country
     *
     * @param string $country
     *
     * @return self
     */
    public function setCountry($country)
    {
        $this->country = $country;

        return $this;
    }

    /**
     * Get country
     *
     * @return string $country
     */
    public function getCountry()
    {
        return $this->country;
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
}
