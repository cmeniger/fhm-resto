<?php
/**
 * Created by PhpStorm.
 * User: fhm
 * Date: 14/12/16
 * Time: 10:13
 */
namespace Fhm\FhmBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Class GenericController
 *
 * @package Fhm\FhmBundle\Controller
 */
abstract class GenericController extends Controller
{
    protected static $source;
    protected static $repository;
    protected static $translation;
    protected static $class;
    protected static $form;
    protected static $route;
    protected static $domain;

    /**
     * @param $route
     * @param $parent
     * @return mixed
     */
    protected function getParameters($route, $parent)
    {
        $parameters = $this->getParameter($parent);
        $value      = $parameters;
        foreach ((array) $route as $sub) {
            $value = $value[$sub];
        }

        return $value;
    }

    /**
     * @param array $parameters
     * @param null  $route
     * @param null  $referenceType
     *
     * @return string
     */
    protected function getUrl($route = null, $parameters = array(), $referenceType = null)
    {
        if ($this->routeExists($route)) {
            return $this->get('router')->generate($route, $parameters, $referenceType);
        } else {
            $this->get('request_stack')->getCurrentRequest()->get('_route');
        }
    }

    /**
     * @param       $key
     * @param array $parameters
     * @param null  $domain
     *
     * @return string
     */
    protected function trans($key, $parameters = array(), $domain = null)
    {
        $key = $key[0] == '.' ? self::$translation.$key : $key;
        $domain = $domain == null ? self::$domain : $domain;

        return $this->get('translator')->trans($key, $parameters, $domain);
    }

    /**
     * To get list of instance data
     * @return array
     */
    public function getProperties()
    {
        return get_class_vars(self::class);
    }

    /**
     * @param $name
     * @return bool
     */
    public function routeExists($name)
    {
        $router = $this->get('router');
        return (null === $router->getRouteCollection()->get($name)) ? false : true;
    }
}