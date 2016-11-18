<?php
namespace Fhm\SiteBundle\Services;

use Fhm\FhmBundle\Services\Grouping as FhmGrouping;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Session\Session;

/**
 * Class Site
 *
 * @package Fhm\SiteBundle\Services
 */
class Grouping extends FhmGrouping
{
    private $session;

    /**
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->visible   = true;
        $this->container = $container;
        $this->session   = $this->container->get('request')->getSession();
        $site            = "";
        if($this->session->get('site') != '')
        {
            $site = $this->dmRepository('FhmSiteBundle:Site')->find($this->session->get('site'));
            if($site == '')
            {
                $this->session->set('site', '');
            }
        }
        if($this->session->get('site') == '')
        {
            $site = $this->dmRepository('FhmSiteBundle:Site')->getDefault();
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
        $this->container->get('twig')->addGlobal('grouping_name', $this->container->get('translator')->trans('site.grouping.name', array(), 'FhmSiteBundle'));
        $this->container->get('twig')->addGlobal('grouping_add', $this->container->get('translator')->trans('site.grouping.add', array("%name%" => $this->getGrouping()), 'FhmSiteBundle'));
        $this->container->get('twig')->addGlobal('grouping_title', $this->container->get('translator')->trans('site.grouping.title', array(), 'FhmSiteBundle'));
        $this->container->get('twig')->addGlobal('grouping_list1', $this->container->get('translator')->trans('site.grouping.list1', array(), 'FhmSiteBundle'));
        $this->container->get('twig')->addGlobal('grouping_list2', $this->container->get('translator')->trans('site.grouping.list2', array(), 'FhmSiteBundle'));

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
            $document = $this->dmRepository('FhmSiteBundle:Site')->getById($grouping);
            $document = ($document) ? $document : $this->dmRepository('FhmSiteBundle:Site')->getByAlias($grouping);
            $document = ($document) ? $document : $this->dmRepository('FhmSiteBundle:Site')->getByName($grouping);
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
        $sites    = $this->dmRepository('FhmSiteBundle:Site')->getAll();
        $grouping = array();
        foreach($sites as $site)
        {
            $grouping[$site->getName()] = $site->getName();
        }

        return $grouping;
    }
}