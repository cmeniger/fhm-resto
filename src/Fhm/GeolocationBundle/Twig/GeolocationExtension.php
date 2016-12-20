<?php
namespace Fhm\GeolocationBundle\Twig;

use Symfony\Component\HttpKernel\KernelInterface;

/**
 * Class GeolocationExtension
 * @package Fhm\GeolocationBundle\Twig
 */
class GeolocationExtension extends \Twig_Extension
{
    /**
     * {@inheritdoc}
     */
    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('addressHtml', array($this, 'getHtml')),
            new \Twig_SimpleFilter('addressInline', array($this, 'getInline')),
        );
    }

    /**
     * @param $object
     * @return string
     */
    public function getHtml($object)
    {
        $address = '';
        $address .= $object->getAddressMain() == 'undefined' ? '' : $object->getAddressMain();
        $address .= $object->getAddressAdditional() ? ($address ? '<br/>' : '').$object->getAddressMain() : '';
        $address .= ($address ? '<br/>' : '').$object->getZipCode().' '.$object->getCity();
        $address .= '<br/>'.$object->getCountry();

        return $address;
    }

    /**
     * @param $object
     * @return string
     */
    public function getInline($object)
    {
        $address = '';
        $address .= $object->getAddressMain() == 'undefined' ? '' : $object->getAddressMain();
        $address .= $object->getAddressAdditional() ? ($address ? ', ' : '').$object->getAddressMain() : '';
        $address .= ($address ? ', ' : '').$object->getZipCode().' '.$object->getCity();
        $address .= ', '.$object->getCountry();

        return $address;
    }

    /**
     * Returns the name of the extension.
     *
     * @return string The extension name
     */
    public function getName()
    {
        return 'geolocation_extension';
    }
}
