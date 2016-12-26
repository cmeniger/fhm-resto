<?php
namespace Fhm\FhmBundle\Services;

use Fhm\UserBundle\Document\User;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Class Tools
 * @package Fhm\FhmBundle\Services
 */
class Tools
{

    /**
     * Tools constructor.
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * @return null
     */
    public function getContainer()
    {
        return $this->container;
    }

    /**
     * @param $datas
     * @param $filename
     * @param $csvDelimiter
     * @return Response
     */
    public function csvExport($datas, $filename, $csvDelimiter)
    {
        $flp = fopen('php://output', 'w');
        ob_start();
        foreach ($datas as $data) {
            fputcsv($flp, $data, $csvDelimiter);
        }
        fclose($flp);
        $csv = ob_get_clean();
        // Response
        $response = new Response();
        $response->headers->set('Content-Type', 'text/csv');
        $response->headers->set(
            'Content-Disposition',
            'attachment;filename='.(($filename == '') ? date('YmdHis').'_export.csv' : $filename)
        );
        $response->setContent($csv);

        return $response;
    }

    /**
     * @param $name
     * @param $datas
     *
     * @return array
     */
    public function formRename($name, $datas)
    {
        $post = array();
        foreach ((array)$datas as $key => $value) {
            $post[$name."[".$key."]"] = $value;
        }

        return $post;
    }


    /**
     * @param $parent
     *
     * @return $this
     */
    public function setParent($parent)
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * @param $section
     *
     * @return $this
     */
    public function setSection($section)
    {
        $this->section = $section;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getLastRoute(Request $request)
    {
        $referer = $request->headers->get('referer');
        $route = ($referer && $request->getBaseUrl() == '') ? $referer : '';
        $route = ($referer == '' && $request->getBaseUrl()) ? $request->getBaseUrl() : $route;
        $route = ($referer && $request->getBaseUrl()) ?
            str_replace($request->getBaseUrl(), '', substr($referer, strpos($referer, $request->getBaseUrl()))) :
            $route;

        return $route;
    }

    /**
     * @param      $id
     * @param      $name
     * @param      $repository
     *
     * @return mixed|string
     */
    public function getAlias($id, $name, $repository)
    {
        $alias = "";
        $unique = false;
        $code = 0;
        $replace = array(
            'À' => 'a',
            'Á' => 'a',
            'Â' => 'a',
            'Ä' => 'a',
            'à' => 'a',
            'á' => 'a',
            'â' => 'a',
            'ä' => 'a',
            '@' => 'a',
            'È' => 'e',
            'É' => 'e',
            'Ê' => 'e',
            'Ë' => 'e',
            'è' => 'e',
            'é' => 'e',
            'ê' => 'e',
            'ë' => 'e',
            '€' => 'e',
            'Ì' => 'i',
            'Í' => 'i',
            'Î' => 'i',
            'Ï' => 'i',
            'ì' => 'i',
            'í' => 'i',
            'î' => 'i',
            'ï' => 'i',
            'Ò' => 'o',
            'Ó' => 'o',
            'Ô' => 'o',
            'Ö' => 'o',
            'ò' => 'o',
            'ó' => 'o',
            'ô' => 'o',
            'ö' => 'o',
            'Ù' => 'u',
            'Ú' => 'u',
            'Û' => 'u',
            'Ü' => 'u',
            'ù' => 'u',
            'ú' => 'u',
            'û' => 'u',
            'ü' => 'u',
            'µ' => 'u',
            'Œ' => 'oe',
            'œ' => 'oe',
            '$' => 's',
        );
        while ($alias == "" || !$unique) {
            $alias = $name;
            $alias = strtr($alias, $replace);
            $alias = preg_replace('#[^A-Za-z0-9]+#', '-', $alias);
            $alias = trim($alias, '-');
            $alias = strtolower($alias);
            $alias = ($code > 0) ? $alias.'-'.$code : $alias;
            $unique = $this->dmRepository($repository)->isUnique($id, $alias);
            $code++;
        }

        return $alias;
    }

    /**
     * @param            $id
     * @param            $name
     * @param bool|false $multiple
     * @param null $repository
     * @param int $length
     *
     * @return string
     */
    public function getUnique($id, $name, $multiple = false, $repository = null, $length = 4)
    {
        $alias = "";
        $unique = false;
        $code = $multiple ? 1 : 0;
        while ($alias == "" || !$unique) {
            $alias = $name;
            $alias = ($code > 0) ? $alias.'_'.str_pad($code, $length, '0', STR_PAD_LEFT) : $alias;
            $unique = $this->dmRepository($repository)->isUnique($id, $alias);
            $code++;
        }

        return $alias;
    }

    /**
     * @param $datas
     *
     * @return ArrayCollection
     */
    public function getCollection($datas)
    {
        $collection = new ArrayCollection();
        foreach ($datas as $data) {
            $collection->add($data);
        }

        return $collection;
    }

    /**
     * @param $datas
     *
     * @return array
     */
    public function getList($datas)
    {
        $list = array();
        foreach ($datas as $data) {
            if ($data->getActive() && !$data->getDelete()) {
                $list[$data->getId()] = $data;
            }
        }

        return $list;
    }

    /**
     * @param $route
     * @param $parent
     *
     * @return mixed
     */
    public function getParameters($route, $parent)
    {
        $parameters = $this->container->getParameter($parent);
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
    public function getUrl($route = null, $parameters = array(), $referenceType = null)
    {
        $route = $route == null ? $this->container->get('request_stack')->getCurrentRequest()->get(
            '_route'
        ) : $route;

        return $this->container->get('router')->generate($route, $parameters, $referenceType);
    }

    /**
     * @return mixed
     */
    public function getUser()
    {
        if ($this->container->get('security.token_storage')->getToken()) {
            return $this->container->get('security.token_storage')->getToken()->getUser();
        }

        return null;
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Session\Session
     */
    public function getSession()
    {
        return $this->getContainer()->get('session');
    }

    /**
     * @param $key
     * @param $parameters
     * @param $domain
     * @param $translation
     * @return string
     */
    public function trans($key, $parameters, $domain, $translation)
    {
        $key = $key[0] == '.' ? $translation.$key : $key;
        return $this->container->get('translator')->trans($key, $parameters, $domain);
    }

    /**
     * @return \Doctrine\Common\Persistence\ObjectManager|object
     */
    public function dm()
    {
        return $this->container->get('doctrine_mongodb')->getManager();
    }

    /**
     * @param $obj
     * @return $this
     */
    public function dmPersist(&$obj)
    {
        $this->dm()->persist($obj);
        $this->dm()->flush();

        return $this;
    }

    /**
     * @param $repository
     * @return \Doctrine\Common\Persistence\ObjectRepository|null
     */
    public function dmRepository($repository)
    {
        return $this->dm()->getRepository($repository);
    }

    /**
     * @param $name
     *
     * @return bool
     */
    protected function routeExists($name)
    {
        $router = $this->container->get('router');

        return ($router->getRouteCollection()->get($name) === null) ? false : true;
    }
}