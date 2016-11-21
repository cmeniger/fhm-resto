<?php
namespace Fhm\FhmBundle\Services;

use Fhm\FhmBundle\Controller\FhmController;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Session\Session;

/**
 * Class Grouping
 *
 * @package Fhm\FhmBundle\Services
 */
class Grouping extends FhmController
{
    protected $site;
    protected $menu;
    protected $visible;


    public function __construct()
    {
        $this->initLanguage();
        $this->site    = $this->dmRepository('FhmSiteBundle:Site')->getDefault();
        $this->menu    = ($this->site) ? $this->site->getMenu() : "";
        $this->visible = false;

        $this->get('session')->set('site', $this->site ? $this->site->getId() : '');
        $this->get('session')->set('menu', $this->menu ? $this->menu->getId() : '');
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
        $this->get('twig')->addGlobal('site', $this->site);
        $this->get('twig')->addGlobal('menu', $this->menu);
        $this->get('twig')->addGlobal('instance', (array) $this->instanceData());
        $this->get('twig')->addGlobal(
            'grouping_name',
            $this->get('translator')->trans('fhm.grouping.name', array(), 'FhmFhmBundle')
        );
        $this->get('twig')->addGlobal(
            'grouping_add',
            $this->get('translator')->trans('fhm.grouping.add', array("%name%" => $this->getGrouping()), 'FhmFhmBundle')
        );
        $this->get('twig')->addGlobal(
            'grouping_title',
            $this->get('translator')->trans('fhm.grouping.title', array(), 'FhmFhmBundle')
        );
        $this->get('twig')->addGlobal(
            'grouping_list1',
            $this->get('translator')->trans('fhm.grouping.list1', array(), 'FhmFhmBundle')
        );
        $this->get('twig')->addGlobal(
            'grouping_list2',
            $this->get('translator')->trans('fhm.grouping.list2', array(), 'FhmFhmBundle')
        );

        return $this;
    }

    /**
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function loadGrouping()
    {
        if ($this->site == '') {
            return $this->redirect($this->generateUrl('fhm_admin_site_create'));
        } elseif ($this->menu == '') {
            $controller = new \Project\DefaultBundle\Controller\FrontController();
            $controller->setContainer($this->container);

            return $controller->templateAction('default');
        } else {
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