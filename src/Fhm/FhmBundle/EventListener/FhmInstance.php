<?php
namespace Fhm\FhmBundle\EventListener;

/**
 * Created by PhpStorm.
 * User: fhm
 * Date: 16/12/16
 * Time: 17:22
 */
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;

/**
 * Class FhmInstance
 * @package Fhm\FhmBundle\EventListener
 */
class FhmInstance implements EventSubscriberInterface
{
    protected $source;
    protected $repository;
    protected $translation;
    protected $class;
    protected $route;
    protected $domain;
    protected $bundle;


    /**
     * @param FilterControllerEvent $event
     * @return bool
     */
    public function onKernelController(FilterControllerEvent $event)
    {
        $controllers = $event->getController();
        $controller = $controllers[0];
        $refClass = new \ReflectionClass($controller);
        if ($refClass->hasMethod('getProperties')) {
            $instanceData = $controller->getProperties();
            $this->hydrate($instanceData);
        }
        return false;
    }

    /**
     * @param array $donnees
     */
    public function hydrate(array $donnees)
    {
        foreach ($donnees as $key => $value) {
            $method = 'set'.ucfirst($key);
            if (method_exists($this, $method)) {
                $this->$method($value);
            }
        }
    }

    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return array(
            'kernel.controller' => 'onKernelController',
        );
    }
    /**
     * @return mixed
     */
    public function getSource()
    {
        return $this->source;
    }

    /**
     * @param mixed $source
     * @return FhmInstance
     */
    public function setSource($source)
    {
        $this->source = $source;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getRepository()
    {
        return $this->repository;
    }

    /**
     * @param mixed $repository
     * @return FhmInstance
     */
    public function setRepository($repository)
    {
        $this->repository = $repository;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getTranslation()
    {
        return $this->translation;
    }

    /**
     * @param mixed $translation
     * @return FhmInstance
     */
    public function setTranslation($translation)
    {
        $this->translation = $translation;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getClass()
    {
        return $this->class;
    }

    /**
     * @param mixed $class
     * @return FhmInstance
     */
    public function setClass($class)
    {
        $this->class = $class;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getRoute()
    {
        return $this->route;
    }

    /**
     * @param mixed $route
     * @return FhmInstance
     */
    public function setRoute($route)
    {
        $this->route = $route;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getDomain()
    {
        return $this->domain;
    }

    /**
     * @param mixed $domain
     * @return FhmInstance
     */
    public function setDomain($domain)
    {
        $this->domain = $domain;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getBundle()
    {
        return $this->bundle;
    }

    /**
     * @param mixed $bundle
     * @return FhmInstance
     */
    public function setBundle($bundle)
    {
        $this->bundle = $bundle;

        return $this;
    }
}