<?php
namespace Fhm\GeolocationBundle\Controller;

use Fhm\FhmBundle\Controller\RefApiController as FhmController;
use Fhm\GeolocationBundle\Document\Geolocation;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * @Route("/map")
 * -----------------------------------------
 * Class MapController
 * @package Fhm\GeolocationBundle\Controller
 */
class MapController extends FhmController
{
    /**
     * AdminController constructor.
     * @param string $repository
     * @param string $source
     * @param string $domain
     * @param string $translation
     * @param string $document
     * @param string $route
     */
    public function __construct(
        $repository = "FhmGeolocationBundle:Geolocation",
        $source = "fhm",
        $domain = "FhmGeolocationBundle",
        $translation = "geolocation",
        $document = Geolocation::class,
        $route = 'geolocation'
    ) {
        self::$repository = $repository;
        self::$source = $source;
        self::$domain = $domain;
        self::$translation = $translation;
        self::$document = new $document();
        self::$class = get_class(self::$document);
        self::$route = $route;
    }
}