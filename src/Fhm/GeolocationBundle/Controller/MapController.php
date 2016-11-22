<?php

namespace Fhm\GeolocationBundle\Controller;

use Fhm\FhmBundle\Controller\FhmController as FhmController;
use Fhm\FhmBundle\Services\Tools;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * @Route("/map")
 */
class MapController extends Controller
{
    protected $tools;

    public function __construct(Tools $fhm_tools)
    {
        $this->tools = $fhm_tools;
    }
}