<?php
//namespace Fhm\CategoryBundle\Services;
//
//use Fhm\FhmBundle\Services\Grouping as FhmGrouping;
//use Symfony\Component\DependencyInjection\ContainerInterface;
//use Symfony\Component\HttpFoundation\Session\Session;
//
///**
// * Class Site
// *
// * @package Fhm\CategoryBundle\Services
// */
//class Grouping extends FhmGrouping
//{
//    protected $container;
//    protected $site;
//    protected $menu;
//
//    /**
//     * @param ContainerInterface $container
//     */
//    public function __construct(ContainerInterface $container)
//    {
//        $this->container = $container;
//        $this->site      = $this->_repository('FhmSiteBundle:Site')->getDefault();;
//        $this->menu = ($this->site) ? $this->site->getMenu() : "";
//        $session    = $this->container->get('request')->getSession();
//        $session->set('site', $this->site ? $this->site->getId() : '');
//        $session->set('menu', $this->menu ? $this->menu->getId() : '');
//        $this->category = $session->get('category');
//    }
//
//    /**
//     * @return mixed
//     */
//    public function getSite()
//    {
//        return $this->site;
//    }
//
//    /**
//     * @return mixed
//     */
//    public function getMenu()
//    {
//        return $this->menu;
//    }
//
//    /**
//     * @return mixed
//     */
//    public function getCategory()
//    {
//        return $this->category;
//    }
//
//    /**
//     * @return $this
//     */
//    public function loadTwigGlobal()
//    {
//        $this->container->get('twig')->addGlobal('site', $this->site);
//        $this->container->get('twig')->addGlobal('menu', $this->menu);
//        $this->container->get('twig')->addGlobal('category', $this->category);
//        $this->container->get('twig')->addGlobal('allcategorys', $this->_repository('FhmCategoryBundle:Category')->getAllcategorys());
//        $this->container->get('twig')->addGlobal('grouping_name', $this->container->get('translator')->trans('category.grouping.name', array(), 'FhmCategoryBundle'));
//        $this->container->get('twig')->addGlobal('grouping_add', $this->container->get('translator')->trans('category.grouping.add', array("%name%" => $this->getGrouping()), 'FhmCategoryBundle'));
//        $this->container->get('twig')->addGlobal('grouping_title', $this->container->get('translator')->trans('category.grouping.title', array(), 'FhmCategoryBundle'));
//        $this->container->get('twig')->addGlobal('grouping_list1', $this->container->get('translator')->trans('category.grouping.list1', array(), 'FhmCategoryBundle'));
//        $this->container->get('twig')->addGlobal('grouping_list2', $this->container->get('translator')->trans('category.grouping.list2', array(), 'FhmCategoryBundle'));
//
//        return $this;
//    }
//
//    /**
//     * @return $this
//     */
//    public function loadGrouping()
//    {
//        $controller = new \Symfony\Bundle\FrameworkBundle\Controller\Controller();
//        $controller->setContainer($this->container);
//        if($this->site == '')
//        {
//            return $controller->redirect($controller->generateUrl('fhm_admin_site_create'));
//        }
//        elseif($this->menu == '')
//        {
//            $controller = new \Project\DefaultBundle\Controller\FrontController();
//            $controller->setContainer($this->container);
//
//            return $controller->templateAction('default', null);
//        }
//        else
//        {
//            $controller = new \Fhm\MenuBundle\Controller\FrontController();
//            $controller->setContainer($this->container);
//
//            return $controller->liteAction($this->menu->getAlias());
//        }
//    }
//
//    /**
//     * @return string
//     */
//    public function getGrouping()
//    {
//        return $this->category ? $this->category->getName() : "";
//    }
//
//    /**
//     * @param $grouping
//     *
//     * @return $this
//     */
//    public function setGrouping($grouping)
//    {
//        $document = $this->_repository('FhmCategoryBundle:Category')->getById($grouping);
//        $document = ($document) ? $document : $this->_repository('FhmCategoryBundle:Category')->getByAlias($grouping);
//        $document = ($document) ? $document : $this->_repository('FhmCategoryBundle:Category')->getByName($grouping);
//        $session  = $this->container->get('request')->getSession();
//        if($document)
//        {
//            $session->set('category', $document);
//            $this->category = $document;
//        }
//
//        return $this;
//    }
//
//    /**
//     * @return array
//     */
//    public function getGroupingAvailable()
//    {
//        $documents    = $this->_repository('FhmCategoryBundle:Category')->getAll();
//        $grouping = array();
//        foreach($documents as $document)
//        {
//            $grouping[$document->getName()] = $document->getName();
//        }
//
//        return $grouping;
//    }
//
//    /**
//     * @param $repository
//     *
//     * @return mixed
//     */
//    private function _repository($repository)
//    {
//        return $this->container->get('doctrine_mongodb')->getManager()->getRepository($repository);
//    }
//}