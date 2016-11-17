<?php
/**
 * Created by PhpStorm.
 * User: fhm
 * Date: 15/11/16
 * Time: 17:27
 */

use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class GlobalDataEventListener implements EventSubscriberInterface
{
    private $twig;

    public function __construct($twig)
    {
        $this->twig = $twig;
    }

    public static function getSubscribedEvents()
    {
        return array(KernelEvents::CONTROLLER => array(
            array('sendTwig', 0),
        ));
    }
    public function sendTwig(FilterControllerEvent $event)
    {
        $controller = $event->getController();
        $request = $event->getRequest();
        $attributes = $request->attributes->all();
        // Ignore sub requests
        if (HttpKernel::MASTER_REQUEST != $event->getRequestType()) {

            return;
        }
//        $instanceData = array();
//        if (method_exists($controller, 'getControllerInfo')) {
//            $instanceData = $controller->getControllerInfo();
//        }

//        $instanceData = array_merge($instanceData,$attributes);

        $this->twig->addGlobal('toto', $attributes);
    }
}