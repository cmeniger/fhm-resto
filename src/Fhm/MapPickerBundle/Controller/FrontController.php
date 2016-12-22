<?php
namespace Fhm\MapPickerBundle\Controller;

use Fhm\FhmBundle\Controller\RefFrontController as FhmController;
use Fhm\MapPickerBundle\Document\Map;
use Fhm\MapPickerBundle\Document\MapPicker;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * @Route("/mappicker")
 * ----------------------------------------
 * Class FrontController
 * @package Fhm\MapPickerBundle\Controller
 */
class FrontController extends FhmController
{
    /**
     * FrontController constructor.
     */
    public function __construct()
    {
        self::$repository = "FhmMapPickerBundle:MapPicker";
        self::$source = "fhm";
        self::$domain = "FhmMapPickerBundle";
        self::$translation = "mappicker";
        self::$class = MapPicker::class;
        self::$route = "mappicker";
    }

    /**
     * @Route
     * (
     *      path="/",
     *      name="fhm_mappicker"
     * )
     * @Template("::FhmMapPicker/Front/index.html.twig")
     */
    public function indexAction()
    {
        $documents = $this->get('fhm_tools')->dmRepository(self::$repository)->getFrontIndex();
        foreach ($documents as &$document) {
            $document->mappicker = $this->get('mappicker.'.$document->getMap())->setDocument($document);
            foreach ($document->getZone() as $zone) {
                $document->addZone(
                    $zone['code'],
                    $this->get('fhm_tools')->dm()->getRepository('FhmSiteBundle:Site')->find($zone['site'])
                );
            }
        }

        return array(
            'documents' => $documents,
        );
    }
}