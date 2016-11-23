<?php
namespace Fhm\SiteBundle\Services;

use Fhm\FhmBundle\Services\Tools;
use Fhm\FhmBundle\Services\Grouping as FhmGrouping;

/**
 * Class Site
 *
 * @package Fhm\SiteBundle\Services
 */
class Grouping extends FhmGrouping
{
    private $session;
    /**
     * Grouping constructor.
     * @param Tools $tools
     */
    public function __construct(Tools $tools)
    {
        parent::__construct($tools);
        $this->visible = true;
        $site          = "";
        $this->session = $tools->getSession();

        if ($this->session->get('site') != '') {
            $site = $this->fhm_tools->dmRepository('FhmSiteBundle:Site')->find($this->session->get('site'));
            if ($site == '') {
                $this->session->set('site', '');
            }
        }
        if ($this->session->get('site') == '') {
            $site = $this->fhm_tools->dmRepository('FhmSiteBundle:Site')->getDefault();
            if ($site) {
                $this->session->set('site', $site->getId());
            }
        }
        $this->site = $site;
        $this->menu = ($site) ? $this->site->getMenu() : "";
    }

    /**
     * @return $this
     */
    public function loadTwigGlobal()
    {
        parent::loadTwigGlobal();
        $container = $this->fhm_tools->getContainer();
        $container->get('twig')->addGlobal(
            'grouping_name',
            $this->fhm_tools->trans('site.grouping.name', array(), 'FhmSiteBundle')
        );
        $container->get('twig')->addGlobal(
            'grouping_add',
            $this->fhm_tools->trans('site.grouping.add', array("%name%" => $this->getGrouping()), 'FhmSiteBundle')
        );
        $container->get('twig')->addGlobal(
            'grouping_title',
            $this->fhm_tools->trans('site.grouping.title', array(), 'FhmSiteBundle')
        );
        $container->get('twig')->addGlobal(
            'grouping_list1',
            $this->fhm_tools->trans('site.grouping.list1', array(), 'FhmSiteBundle')
        );
        $container->get('twig')->addGlobal(
            'grouping_list2',
            $this->fhm_tools->trans('site.grouping.list2', array(), 'FhmSiteBundle')
        );

        return $this;
    }

    /**
     * @return string
     */
    public function getGrouping()
    {
        return $this->site ? $this->site->getName() : "";
    }

    /**
     * @param $grouping
     *
     * @return $this
     */
    public function setGrouping($grouping)
    {
        if ($grouping) {
            $document = $this->fhm_tools->dmRepository('FhmSiteBundle:Site')->getById($grouping);
            $document = ($document)?
                $document:
                $this->fhm_tools->dmRepository('FhmSiteBundle:Site')->getByAlias($grouping);
            $document = ($document)?
                $document:
                $this->fhm_tools->dmRepository('FhmSiteBundle:Site')->getByName($grouping);

            if ($document) {
                $this->session->set('site', $document->getId());
                $this->site = $document;
                $this->menu = $document->getMenu();
            }
        }

        return $this;
    }

    /**
     * @return array
     */
    public function getGroupingAvailable()
    {
        $sites    = $this->fhm_tools->dmRepository('FhmSiteBundle:Site')->getAll();
        $grouping = array();
        foreach ($sites as $site) {
            $grouping[$site->getName()] = $site->getName();
        }

        return $grouping;
    }
}