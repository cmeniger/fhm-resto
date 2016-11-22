<?php

namespace Fhm\GeolocationBundle\Controller;

use Fhm\FhmBundle\Controller\FhmController as FhmController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * @Route("/map")
 */
class MapController extends Controller
{
    protected $fhm_tools;

    public function __construct($fhm_tools)
    {
        $this->fhm_tools = $fhm_tools;
    }
}