<?php
namespace Fhm\SiteBundle\Services;

use Fhm\FhmBundle\Services\Grouping as FhmGrouping;

/**
 * Class Site
 *
 * @package Fhm\SiteBundle\Services
 */
class Grouping extends FhmGrouping
{
    /**
     * Grouping constructor.
     *
     * @param \Fhm\FhmBundle\Services\Tools $tools
     * @param \Twig_Environment             $twig
     */
    public function __construct(\Fhm\FhmBundle\Services\Tools $tools, \Twig_Environment $twig)
    {
        parent::__construct($tools, $twig);
        $this->visible = true;
        $site          = "";
        if($this->session->get('site') != '')
        {
            $site = $this->fhm_tools->dmRepository('FhmSiteBundle:Site')->find($this->session->get('site'));
            if($site == '')
            {
                $this->session->set('site', '');
            }
        }
        if($this->session->get('site') == '')
        {
            $site = $this->fhm_tools->dmRepository('FhmSiteBundle:Site')->getDefault();
            if($site)
            {
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
        $this->twig->addGlobal('grouping_name', $this->fhm_tools->trans('site.grouping.name', array(), 'FhmSiteBundle'));
        $this->twig->addGlobal('grouping_add', $this->fhm_tools->trans('site.grouping.add', array("%name%" => $this->getGrouping()), 'FhmSiteBundle'));
        $this->twig->addGlobal('grouping_title', $this->fhm_tools->trans('site.grouping.title', array(), 'FhmSiteBundle'));
        $this->twig->addGlobal('grouping_list1', $this->fhm_tools->trans('site.grouping.list1', array(), 'FhmSiteBundle'));
        $this->twig->addGlobal('grouping_list2', $this->fhm_tools->trans('site.grouping.list2', array(), 'FhmSiteBundle'));

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
        if($grouping)
        {
            $document = $this->fhm_tools->dmRepository('FhmSiteBundle:Site')->getById($grouping);
            $document = ($document) ? $document : $this->fhm_tools->dmRepository('FhmSiteBundle:Site')->getByAlias($grouping);
            $document = ($document) ? $document : $this->fhm_tools->dmRepository('FhmSiteBundle:Site')->getByName($grouping);
            if($document)
            {
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
        foreach($sites as $site)
        {
            $grouping[$site->getName()] = $site->getName();
        }

        return $grouping;
    }
}