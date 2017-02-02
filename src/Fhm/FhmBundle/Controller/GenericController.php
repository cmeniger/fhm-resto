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
    protected static $object;

    /**
     * @param $route
     * @param $parent
     * @return mixed
     */
    protected function getParameters($route, $parent)
    {
        $parameters = $this->getParameter($parent);
        $value = $parameters;
        foreach ((array)$route as $sub) {
            $value = $value[$sub];
        }

        return $value;
    }

    /**
     * @param array $parameters
     * @param null $route
     * @param null $referenceType
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
     * @param null $domain
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

    /**
     * @param $data
     * @return mixed
     */
    public function redirectUrl($data, $object, $side = "admin")
    {
        $redirect = $this->redirect($this->generateUrl(self::$source.'_admin_'.self::$route));
        $redirect = isset($data['submitSave']) ? $this->redirect(
            $this->generateUrl(
                self::$source.'_'.$side.'_'.self::$route.'_update',
                array('id' => $object->getId())
            )
        ) : $redirect;
        $redirect = isset($data['submitDuplicate']) ? $this->redirect(
            $this->generateUrl(
                self::$source.'_'.$side.'_'.self::$route.'_duplicate',
                array('id' => $object->getId())
            )
        ) : $redirect;
        $redirect = isset($data['submitNew']) ? $this->redirect(
            $this->generateUrl(self::$source.'_'.$side.'_'.self::$route.'_create')
        ) : $redirect;
        $redirect = isset($data['submitConfig']) ? $this->redirect(
            $this->generateUrl(
                self::$source.'_'.$side.'_'.self::$route.'_detail',
                array('id' => $object->getId())
            )
        ) : $redirect;

        return $redirect;
    }
}