<?php
namespace Fhm\FhmBundle\Services;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\RedirectResponse;

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
     * Grouping constructor.
     * @param Tools $tools
     */
    public function __construct(Tools $tools)
    {
        $this->fhm_tools = $tools;
        $this->fhm_tools->initLanguage();
        $this->site    = $this->fhm_tools->dmRepository('FhmSiteBundle:Site')->getDefault();
        $this->menu    = ($this->site) ? $this->site->getMenu() : "";
        $this->visible = false;
        $session       = $this->fhm_tools->getContainer()->get('request')->getSession();
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
        $container = $this->fhm_tools->getContainer();
        $container->get('twig')->addGlobal('site', $this->site);
        $container->get('twig')->addGlobal('menu', $this->menu);
        $container->get('twig')->addGlobal('instance', (array) $this->fhm_tools->instanceData());
        $container->get('twig')->addGlobal(
            'grouping_name',
            $this->fhm_tools->trans('fhm.grouping.name', array(), 'FhmFhmBundle')
        );
        $container->get('twig')->addGlobal(
            'grouping_add',
            $this->fhm_tools->trans('fhm.grouping.add', array("%name%" => $this->getGrouping()), 'FhmFhmBundle')
        );
        $container->get('twig')->addGlobal(
            'grouping_title',
            $this->fhm_tools->trans('fhm.grouping.title', array(), 'FhmFhmBundle')
        );
        $container->get('twig')->addGlobal(
            'grouping_list1',
            $this->fhm_tools->trans('fhm.grouping.list1', array(), 'FhmFhmBundle')
        );
        $container->get('twig')->addGlobal(
            'grouping_list2',
            $this->fhm_tools->trans('fhm.grouping.list2', array(), 'FhmFhmBundle')
        );

        return $this;
    }

    /**
     * @return array|RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function loadGrouping()
    {
        $container = $this->fhm_tools->getContainer();

        if ($this->site == '') {
            $url = $container->get('router')->generate('fhm_admin_site_create');
            return new RedirectResponse($url, 302);
        } elseif ($this->menu == '') {
            return $container->get('project_default_controller_front')->templateAction('default');
        } else {
            return $container->get('fhm_menu_controller_front')->liteAction($this->menu->getAlias());
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