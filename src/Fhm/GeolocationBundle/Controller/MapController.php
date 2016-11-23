<?php

namespace Fhm\GeolocationBundle\Controller;

use Fhm\FhmBundle\Controller\RefApiController as FhmController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * @Route("/map", service="fhm_map_controller")
 */
class MapController extends FhmController
{
    /**
     * MapController constructor.
     *
     * @param \Fhm\FhmBundle\Services\Tools $tools
     */
    public function __construct(\Fhm\FhmBundle\Services\Tools $tools)
    {
        $this->setFhmTools($tools);
        parent::__construct('Fhm', 'Geolocation', 'geolocation');
    }
}