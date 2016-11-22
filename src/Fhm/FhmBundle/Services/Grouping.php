<?php
namespace Fhm\FhmBundle\Services;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Session\Session;

/**
 * Class Grouping
 *
 * @package Fhm\FhmBundle\Services
 */
class Grouping
{
    protected $site;
    protected $menu;
    protected $visible;
    protected $fhm_tools;

    /**
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        $this->fhm_tools = $container->get('fhm_tools');
        $this->fhm_tools->initLanguage();
        $this->site    = $this->fhm_tools->dmRepository('FhmSiteBundle:Site')->getDefault();
        $this->menu    = ($this->site) ? $this->site->getMenu() : "";
        $this->visible = false;
        $session       = $this->container->get('request')->getSession();
        $session->set('site', $this->site ? $this->site->getId() : '');
        $session->set('menu', $this->menu ? $this->menu->getId() : '');
    }

    /**
     * @return mixed
     */
    public function getSite()
    {
        return $this->site;
    }

    /**
     * @return mixed
     */
    public function getMenu()
    {
        return $this->menu;
    }

    /**
     * @return $this
     */
    public function loadTwigGlobal()
    {
        $this->container->get('twig')->addGlobal('site', $this->site);
        $this->container->get('twig')->addGlobal('menu', $this->menu);
        //        $this->container->get('twig')->addGlobal('instance', (array) $this->fhm_tools->instanceData());
        $this->container->get('twig')->addGlobal('grouping_name', $this->container->get('translator')->trans('fhm.grouping.name', array(), 'FhmFhmBundle'));
        $this->container->get('twig')->addGlobal('grouping_add', $this->container->get('translator')->trans('fhm.grouping.add', array("%name%" => $this->getGrouping()), 'FhmFhmBundle'));
        $this->container->get('twig')->addGlobal('grouping_title', $this->container->get('translator')->trans('fhm.grouping.title', array(), 'FhmFhmBundle'));
        $this->container->get('twig')->addGlobal('grouping_list1', $this->container->get('translator')->trans('fhm.grouping.list1', array(), 'FhmFhmBundle'));
        $this->container->get('twig')->addGlobal('grouping_list2', $this->container->get('translator')->trans('fhm.grouping.list2', array(), 'FhmFhmBundle'));

        return $this;
    }

    /**
     * @return $this
     */
    public function loadGrouping()
    {
        $controller = new \Symfony\Bundle\FrameworkBundle\Controller\Controller();
        $controller->setContainer($this->container);
        if($this->site == '')
        {
            return $controller->redirect($controller->generateUrl('fhm_admin_site_create'));
        }
        elseif($this->menu == '')
        {
            $controller = new \Project\DefaultBundle\Controller\FrontController();
            $controller->setContainer($this->container);

            return $controller->templateAction('default');
        }
        else
        {
            $controller = new \Fhm\MenuBundle\Controller\FrontController();
            $controller->setContainer($this->container);

            return $controller->liteAction($this->menu->getAlias());
        }
    }

    /**
     * @return string
     */
    public function getGrouping()
    {
        return "";
    }

    /**
     * @param $grouping
     *
     * @return $this
     */
    public function setGrouping($grouping)
    {
        return $this;
    }

    /**
     * @return array
     */
    public function getGroupingAvailable()
    {
        return array();
    }

    /**
     * @return bool
     */
    public function getVisible()
    {
        return $this->visible;
    }

    /**
     * @param $bool
     *
     * @return $this
     */
    public function setVisible($bool)
    {
        $this->visible = $bool;

        return $this;
    }
}