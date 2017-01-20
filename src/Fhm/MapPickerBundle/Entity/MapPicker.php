<?php
namespace Fhm\MapPickerBundle\Entity;
use Fhm\FhmBundle\Entity\Fhm;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table()
 */
class MapPicker extends Fhm
{
    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @ORM\Column(type="string", length=100)
     */
    protected $map;

    /**
     * @ORM\Column(type="hash")
     */
    protected $zone = null;

    /**
     * @ORM\Column(type="integer")
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