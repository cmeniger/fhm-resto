<?php
namespace Fhm\MapPickerBundle\Document;

use Fhm\FhmBundle\Document\Fhm as FhmFhm;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * MapPicker
 * @MongoDB\Document(repositoryClass="Fhm\MapPickerBundle\Document\Repository\MapRepository")
 */
class MapPicker extends FhmFhm
{
    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @MongoDB\Field(type="string")
     */
    protected $map;

    /**
     * @MongoDB\Field(type="hash")
     */
    protected $zone = null;

    /**
     * @MongoDB\Field(type="int")
     * @Assert\Range(min = 0)
     */
    protected $order = 0;

    /**
     * Set map
     *
     * @param string $map
     *
     * @return self
     */
    public function setMap($map)
    {
        $this->map = $map;

        return $this;
    }

    /**
     * Get map
     *
     * @return string $map
     */
    public function getMap()
    {
        return $this->map;
    }

    /**
     * @param $code
     * @param $site
     *
     * @return $this
     */
    public function addZone($code, $site)
    {
        $this->removeZone($code);
        $this->zone[$code] = array('code' => $code, 'site' => $site);
        asort($this->zone);

        return $this;
    }

    /**
     * Remove zone
     *
     * @param string $code
     *
     * @return self
     */
    public function removeZone($code)
    {
        unset($this->zone[$code]);

        return $this;
    }

    /**
     * Delete zone
     *
     * @return self
     */
    public function deleteZone()
    {
        $this->zone = null;

        return $this;
    }

    /**
     * Get zone
     *
     * @return array $zone
     */
    public function getZone()
    {
        return $this->zone;
    }

    /**
     * Get site from zone
     *
     * @param string $code
     *
     * @return string $site
     */
    public function getZoneSite($code)
    {
        return (isset($this->zone[$code])) ? $this->zone[$code]['site'] : '';
    }

    /**
     * Get order
     *
     * @return string $order
     */
    public function getOrder()
    {
        return $this->order;
    }

    /**
     * Set order
     *
     * @param string $order
     *
     * @return self
     */
    public function setOrder($order)
    {
        $this->order = $order;

        return $this;
    }
}