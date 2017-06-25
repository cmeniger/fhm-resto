<?php
namespace Core\GeolocationBundle\Twig;

use Symfony\Component\HttpKernel\KernelInterface;

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

    public function getHtml($object)
    {
        $address = '';
        $address .= $object->getAddressMain() == 'undefined' ? '' : $object->getAddressMain();
        $address .= $object->getAddressAdditional() ? ($address ? '<br/>' : '') . $object->getAddressAdditional() : '';
        $address .= ($address ? '<br/>' : '') . $object->getZipCode() . ' ' . $object->getCity();
        $address .= '<br/>' . $object->getCountry();

        return $address;
    }

    public function getInline($object)
    {
        $address = '';
        $address .= $object->getAddressMain() == 'undefined' ? '' : $object->getAddressMain();
        $address .= $object->getAddressAdditional() ? ($address ? ', ' : '') . $object->getAddressAdditional() : '';
        $address .= ($address ? ', ' : '') . $object->getZipCode() . ' ' . $object->getCity();
        $address .= ', ' . $object->getCountry();

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
