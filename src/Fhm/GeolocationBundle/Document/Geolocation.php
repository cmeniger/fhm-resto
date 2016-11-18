<?php
namespace Fhm\GeolocationBundle\Document;

use Fhm\FhmBundle\Document\Fhm as FhmFhm;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Doctrine\Bundle\MongoDBBundle\Validator\Constraints\Unique as MongoDBUnique;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Geolocation
 * @MongoDBUnique(fields="unique_position", message="geolocation.unique")
 */
class Geolocation extends FhmFhm
{
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
    protected $code;

    /**
     * @MongoDB\Field(type="string")
     */
    protected $latitude;

    /**
     * @MongoDB\Field(type="string")
     */
    protected $longitude;

    /**
     * @MongoDB\Field(type="string")
     */
    protected $unique_position;

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
        $this->latitude        = $latitude;
        $this->unique_position = $this->latitude . ':' . $this->longitude;

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
        $this->longitude       = $longitude;
        $this->unique_position = $this->latitude . ':' . $this->longitude;

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
     * @return mixed
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @param mixed $code
     */
    public function setCode($code)
    {
        $this->code = $code;
    }

    /**
     * @MongoDB\PrePersist()
     */
    public function prePersist()
    {
        parent::prePersist();

        return $this;
    }

    /**
     * @MongoDB\PreUpdate()
     */
    public function preUpdate()
    {
        parent::preUpdate();

        return $this;
    }

    /**
     * Get CSV header
     *
     * @return array
     */
    public function getCsvHeader()
    {
        return array_merge(parent::getCsvHeader(), array(
            'address_main',
            'address_additional',
            'zip_code',
            'city',
            'country',
            'latitude',
            'longitude'
        ));
    }

    /**
     * Get CSV data
     *
     * @return array
     */
    public function getCsvData()
    {
        return array_merge(parent::getCsvData(), array(
            utf8_decode($this->address_main),
            utf8_decode($this->address_additional),
            utf8_decode($this->zip_code),
            utf8_decode($this->city),
            utf8_decode($this->country),
            utf8_decode($this->latitude),
            utf8_decode($this->longitude)
        ));
    }

    /**
     * Set CSV data
     *
     * @param array $data
     *
     * @return self
     */
    public function setCsvData($data)
    {
        $this->address_main       = (isset($data['address_main'])) ? $data['address_main'] : $this->address_main;
        $this->address_additional = (isset($data['address_additional'])) ? $data['address_additional'] : $this->address_additional;
        $this->zip_code           = (isset($data['zip_code'])) ? $data['zip_code'] : $this->zip_code;
        $this->city               = (isset($data['city'])) ? $data['city'] : $this->city;
        $this->country            = (isset($data['country'])) ? $data['country'] : $this->country;
        $this->latitude           = (isset($data['latitude'])) ? $data['latitude'] : $this->latitude;
        $this->longitude          = (isset($data['longitude'])) ? $data['longitude'] : $this->longitude;
        $this->unique_position    = $this->latitude . ':' . $this->longitude;

        return parent::setCsvData($data);
    }
}
