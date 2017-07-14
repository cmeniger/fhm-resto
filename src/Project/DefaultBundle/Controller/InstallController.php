<?php

namespace Project\DefaultBundle\Controller;

use Fhm\FhmBundle\Controller\RefFrontController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Session\Session;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * @Route("/install")
 */
class InstallController extends RefFrontController
{
    /**
     * FrontController constructor.
     */
    public function __construct()
    {
        self::$source      = "project";
        self::$domain      = "ProjectDefaultBundle";
        self::$translation = "project.install";
        self::$route       = 'project_install';
    }

    /**
     * @Route
     * (
     *      path="/",
     *      name="project_install"
     * )
     * @Template("::ProjectDefault/Install/index.html.twig")
     */
    public function indexAction()
    {
        $site    = $this->get('fhm_tools')->dmRepository('FhmFhmBundle:Site')->getDefault();
        $process = $this->get('fhm_tools')->getParameters(array('install', 'process'), 'project');

        return array(
            'site'    => $site,
            'process' => $site ? '[]' : json_encode($process)
        );
    }

    /**
     * @Route
     * (
     *      path="/user",
     *      name="project_install_user"
     * )
     */
    public function userAction(Request $request)
    {
        $logs        = array();
        $group       = $this->get('fhm_tools')->trans('project.install.groups.user', array(), self::$domain);
        $manipulator = $this->get('fos_user.util.user_manipulator');
        // FHM user
        $object = $this->get('fhm_tools')->dmRepository('FhmUserBundle:User')->getUserByUsername('fhm');
        if($object == '')
        {
            $username = 'fhm';
            $email    = $this->get('fhm_tools')->getParameters(array('install', 'user', 'fhm'), 'project');
            $password = $this->get('fhm_tools')->getParameters(array('install', 'project'), 'project') . $username . '42';
            $manipulator->create($username, $password, $email, true, true);
            $logs[] = array(
                date('H:i:s'),
                '',
                $this->get('fhm_tools')->trans('project.install.logs.created', array('%group%' => $group, '%name%' => 'fhm (ROLE_SUPER_ADMIN)'), self::$domain)
            );
        }
        else
        {
            $logs[] = array(
                date('H:i:s'),
                'warning',
                $this->get('fhm_tools')->trans('project.install.logs.exist', array('%group%' => $group, '%name%' => 'fhm'), self::$domain)
            );
        }
        // ADMIN user
        $object = $this->get('fhm_tools')->dmRepository('FhmUserBundle:User')->getUserByUsername('admin');
        if($object == '')
        {
            $username = 'admin';
            $email    = $this->get('fhm_tools')->getParameters(array('install', 'user', 'admin'), 'project');
            $password = $this->get('fhm_tools')->getParameters(array('install', 'project'), 'project') . $username . '42';
            $manipulator->create($username, $password, $email, true, false);
            $manipulator->addRole($username, 'ROLE_ADMIN');
            $logs[] = array(
                date('H:i:s'),
                '',
                $this->get('fhm_tools')->trans('project.install.logs.created', array('%group%' => $group, '%name%' => 'admin (ROLE_ADMIN)'), self::$domain)
            );
        }
        else
        {
            $logs[] = array(
                date('H:i:s'),
                'warning',
                $this->get('fhm_tools')->trans('project.install.logs.exist', array('%group%' => $group, '%name%' => 'admin'), self::$domain)
            );
        }
        // MODERATOR user
        $object = $this->get('fhm_tools')->dmRepository('FhmUserBundle:User')->getUserByUsername('moderator');
        if($object == '')
        {
            $username = 'moderator';
            $email    = $this->get('fhm_tools')->getParameters(array('install', 'user', 'moderator'), 'project');
            $password = $this->get('fhm_tools')->getParameters(array('install', 'project'), 'project') . $username . '42';
            $manipulator->create($username, $password, $email, true, false);
            $manipulator->addRole($username, 'ROLE_MODERATOR');
            $logs[] = array(
                date('H:i:s'),
                '',
                $this->get('fhm_tools')->trans('project.install.logs.created', array('%group%' => $group, '%name%' => 'moderator (ROLE_MODERATOR)'), self::$domain)
            );
        }
        else
        {
            $logs[] = array(
                date('H:i:s'),
                'warning',
                $this->get('fhm_tools')->trans('project.install.logs.exist', array('%group%' => $group, '%name%' => 'moderator'), self::$domain)
            );
        }
        // NOREPLY user
        $object = $this->get('fhm_tools')->dmRepository('FhmUserBundle:User')->getUserByUsername('noreply');
        if($object == '')
        {
            $username = 'noreply';
            $email    = $this->get('fhm_tools')->getParameters(array('install', 'user', 'noreply'), 'project');
            $password = $this->get('fhm_tools')->getParameters(array('install', 'project'), 'project') . $username . '42';
            $manipulator->create($username, $password, $email, true, false);
            $logs[] = array(
                date('H:i:s'),
                '',
                $this->get('fhm_tools')->trans('project.install.logs.created', array('%group%' => $group, '%name%' => 'noreply'), self::$domain)
            );
        }
        else
        {
            $logs[] = array(
                date('H:i:s'),
                'warning',
                $this->get('fhm_tools')->trans('project.install.logs.exist', array('%group%' => $group, '%name%' => 'noreply'), self::$domain)
            );
        }

        // Response
        return new JsonResponse(array(
            'status' => 200,
            'post'   => '',
            'logs'   => $logs
        ));
    }

    /**
     * @Route
     * (
     *      path="/site",
     *      name="project_install_site"
     * )
     */
    public function siteAction(Request $request)
    {
        $logs   = array();
        $group  = $this->get('fhm_tools')->trans('project.install.groups.site', array(), self::$domain);
        $object = $this->get('fhm_tools')->dmRepository('FhmFhmBundle:Site')->findOneByName('main');
        if($object == '')
        {
            $class  = $this->get('fhm.object.manager')->getCurrentModelName('FhmFhmBundle:Site');
            $object = new $class;
            $object->setName('main');
            $object->setAlias($this->get('fhm_tools')->getAlias('', 'main', 'FhmFhmBundle:Site'));
            $object->setActive(true);
            $object->setDemo(true);
            $this->get('fhm_tools')->dmPersist($object);
            $logs[] = array(
                date('H:i:s'),
                '',
                $this->get('fhm_tools')->trans('project.install.logs.created', array('%group%' => $group, '%name%' => 'main'), self::$domain)
            );
        }
        else
        {
            $logs[] = array(
                date('H:i:s'),
                'warning',
                $this->get('fhm_tools')->trans('project.install.logs.exist', array('%group%' => $group, '%name%' => 'main'), self::$domain)
            );
        }

        // Response
        return new JsonResponse(array(
            'status' => 200,
            'post'   => array('site' => $object->getId()),
            'logs'   => $logs
        ));
    }

    /**
     * @Route
     * (
     *      path="/menu",
     *      name="project_install_menu"
     * )
     */
    public function menuAction(Request $request)
    {
        $logs   = array();
        $group  = $this->get('fhm_tools')->trans('project.install.groups.menu', array(), self::$domain);
        $status = 200;
        $site   = '';
        if($request->get('site') == '')
        {
            $group  = $this->get('fhm_tools')->trans('project.install.groups.error', array(), self::$domain);
            $status = 500;
            $logs[] = array(
                date('H:i:s'),
                'alert',
                $this->get('fhm_tools')->trans('project.install.logs.empty', array('%group%' => $group, '%name%' => 'site'), self::$domain)
            );
        }
        else
        {
            $site = $this->get('fhm_tools')->dmRepository('FhmFhmBundle:Site')->find($request->get('site'));
            // Main
            $object = $this->get('fhm_tools')->dmRepository('FhmFhmBundle:Menu')->findOneByName('main');
            if($object == '')
            {
                $class  = $this->get('fhm.object.manager')->getCurrentModelName('FhmFhmBundle:Menu');
                $object = new $class;
                $object->setName('main');
                $object->setAlias($this->get('fhm_tools')->getAlias('', 'main', 'FhmFhmBundle:Menu'));
                $object->setActive(true);
                $this->get('fhm_tools')->dmPersist($object);
                $site->setMenu($object);
                $logs[] = array(
                    date('H:i:s'),
                    '',
                    $this->get('fhm_tools')->trans('project.install.logs.created', array('%group%' => $group, '%name%' => 'main'), self::$domain)
                );
            }
            else
            {
                $site->setMenu($object);
                $logs[] = array(
                    date('H:i:s'),
                    'warning',
                    $this->get('fhm_tools')->trans('project.install.logs.exist', array('%group%' => $group, '%name%' => 'main'), self::$domain)
                );
            }
            // Home side
            $object = $this->get('fhm_tools')->dmRepository('FhmFhmBundle:Menu')->findOneByName('home side');
            if($object == '')
            {
                $class  = $this->get('fhm.object.manager')->getCurrentModelName('FhmFhmBundle:Menu');
                $object = new $class;
                $object->setName('home side');
                $object->setAlias($this->get('fhm_tools')->getAlias('', 'home side', 'FhmFhmBundle:Menu'));
                $object->setActive(true);
                $this->get('fhm_tools')->dmPersist($object);
                $site->setMenuHomeSide($object);
                $logs[] = array(
                    date('H:i:s'),
                    '',
                    $this->get('fhm_tools')->trans('project.install.logs.created', array('%group%' => $group, '%name%' => 'home side'), self::$domain)
                );
            }
            else
            {
                $site->setMenuHomeSide($object);
                $logs[] = array(
                    date('H:i:s'),
                    'warning',
                    $this->get('fhm_tools')->trans('project.install.logs.exist', array('%group%' => $group, '%name%' => 'home side'), self::$domain)
                );
            }
            // Home left
            $object = $this->get('fhm_tools')->dmRepository('FhmFhmBundle:Menu')->findOneByName('home left');
            if($object == '')
            {
                $class  = $this->get('fhm.object.manager')->getCurrentModelName('FhmFhmBundle:Menu');
                $object = new $class;
                $object->setName('home left');
                $object->setAlias($this->get('fhm_tools')->getAlias('', 'home left', 'FhmFhmBundle:Menu'));
                $object->setActive(true);
                $this->get('fhm_tools')->dmPersist($object);
                $site->setMenuHomeLeft($object);
                $logs[] = array(
                    date('H:i:s'),
                    '',
                    $this->get('fhm_tools')->trans('project.install.logs.created', array('%group%' => $group, '%name%' => 'home left'), self::$domain)
                );
            }
            else
            {
                $site->setMenuHomeLeft($object);
                $logs[] = array(
                    date('H:i:s'),
                    'warning',
                    $this->get('fhm_tools')->trans('project.install.logs.exist', array('%group%' => $group, '%name%' => 'home left'), self::$domain)
                );
            }
            // Home right
            $object = $this->get('fhm_tools')->dmRepository('FhmFhmBundle:Menu')->findOneByName('home right');
            if($object == '')
            {
                $class  = $this->get('fhm.object.manager')->getCurrentModelName('FhmFhmBundle:Menu');
                $object = new $class;
                $object->setName('home right');
                $object->setAlias($this->get('fhm_tools')->getAlias('', 'home right', 'FhmFhmBundle:Menu'));
                $object->setActive(true);
                $this->get('fhm_tools')->dmPersist($object);
                $site->setMenuHomeRight($object);
                $logs[] = array(
                    date('H:i:s'),
                    '',
                    $this->get('fhm_tools')->trans('project.install.logs.created', array('%group%' => $group, '%name%' => 'home right'), self::$domain)
                );
            }
            else
            {
                $site->setMenuHomeRight($object);
                $logs[] = array(
                    date('H:i:s'),
                    'warning',
                    $this->get('fhm_tools')->trans('project.install.logs.exist', array('%group%' => $group, '%name%' => 'home right'), self::$domain)
                );
            }
            // Footer
            $object = $this->get('fhm_tools')->dmRepository('FhmFhmBundle:Menu')->findOneByName('footer');
            if($object == '')
            {
                $class  = $this->get('fhm.object.manager')->getCurrentModelName('FhmFhmBundle:Menu');
                $object = new $class;
                $object->setName('footer');
                $object->setAlias($this->get('fhm_tools')->getAlias('', 'footer', 'FhmFhmBundle:Menu'));
                $object->setActive(true);
                $this->get('fhm_tools')->dmPersist($object);
                $site->setMenuFooter($object);
                $logs[] = array(
                    date('H:i:s'),
                    '',
                    $this->get('fhm_tools')->trans('project.install.logs.created', array('%group%' => $group, '%name%' => 'footer'), self::$domain)
                );
            }
            else
            {
                $site->setMenuFooter($object);
                $logs[] = array(
                    date('H:i:s'),
                    'warning',
                    $this->get('fhm_tools')->trans('project.install.logs.exist', array('%group%' => $group, '%name%' => 'footer'), self::$domain)
                );
            }
            // Site
            $this->get('fhm_tools')->dmPersist($site);
        }

        // Response
        return new JsonResponse(array(
            'status' => $status,
            'post'   => $site ? array('site' => $site->getId()) : '',
            'logs'   => $logs
        ));
    }

    /**
     * @Route
     * (
     *      path="/slider",
     *      name="project_install_slider"
     * )
     */
    public function sliderAction(Request $request)
    {
        $logs   = array();
        $group  = $this->get('fhm_tools')->trans('project.install.groups.slider', array(), self::$domain);
        $status = 200;
        $site   = '';
        if($request->get('site') == '')
        {
            $group  = $this->get('fhm_tools')->trans('project.install.groups.error', array(), self::$domain);
            $status = 500;
            $logs[] = array(
                date('H:i:s'),
                'alert',
                $this->get('fhm_tools')->trans('project.install.logs.empty', array('%group%' => $group, '%name%' => 'site'), self::$domain)
            );
        }
        else
        {
            $site = $this->get('fhm_tools')->dmRepository('FhmFhmBundle:Site')->find($request->get('site'));
            // Main
            $object = $this->get('fhm_tools')->dmRepository('FhmSliderBundle:Slider')->findOneByName('home');
            if($object == '')
            {
                $class  = $this->get('fhm.object.manager')->getCurrentModelName('FhmSliderBundle:Slider');
                $object = new $class;
                $object->setName('home');
                $object->setAlias($this->get('fhm_tools')->getAlias('', 'home', 'FhmSliderBundle:Slider'));
                $object->setActive(true);
                $this->get('fhm_tools')->dmPersist($object);
                $site->setSlider($object);
                $logs[] = array(
                    date('H:i:s'),
                    '',
                    $this->get('fhm_tools')->trans('project.install.logs.created', array('%group%' => $group, '%name%' => 'home'), self::$domain)
                );
            }
            else
            {
                $site->setSlider($object);
                $logs[] = array(
                    date('H:i:s'),
                    'warning',
                    $this->get('fhm_tools')->trans('project.install.logs.exist', array('%group%' => $group, '%name%' => 'home'), self::$domain)
                );
            }
            // Site
            $this->get('fhm_tools')->dmPersist($site);
        }

        // Response
        return new JsonResponse(array(
            'status' => $status,
            'post'   => $site ? array('site' => $site->getId()) : '',
            'logs'   => $logs
        ));
    }

    /**
     * @Route
     * (
     *      path="/gallery",
     *      name="project_install_gallery"
     * )
     */
    public function galleryAction(Request $request)
    {
        $logs   = array();
        $group  = $this->get('fhm_tools')->trans('project.install.groups.gallery', array(), self::$domain);
        $status = 200;
        $site   = '';
        if($request->get('site') == '')
        {
            $group  = $this->get('fhm_tools')->trans('project.install.groups.error', array(), self::$domain);
            $status = 500;
            $logs[] = array(
                date('H:i:s'),
                'alert',
                $this->get('fhm_tools')->trans('project.install.logs.empty', array('%group%' => $group, '%name%' => 'site'), self::$domain)
            );
        }
        else
        {
            $site = $this->get('fhm_tools')->dmRepository('FhmFhmBundle:Site')->find($request->get('site'));
            // Top
            $object = $this->get('fhm_tools')->dmRepository('FhmGalleryBundle:Gallery')->findOneByName('home top');
            if($object == '')
            {
                $class  = $this->get('fhm.object.manager')->getCurrentModelName('FhmGalleryBundle:Gallery');
                $object = new $class;
                $object->setName('home top');
                $object->setAlias($this->get('fhm_tools')->getAlias('', 'home top', 'FhmGalleryBundle:Gallery'));
                $object->setActive(true);
                $this->get('fhm_tools')->dmPersist($object);
                $site->setGalleryTop($object);
                $logs[] = array(
                    date('H:i:s'),
                    '',
                    $this->get('fhm_tools')->trans('project.install.logs.created', array('%group%' => $group, '%name%' => 'home top'), self::$domain)
                );
            }
            else
            {
                $site->setGalleryTop($object);
                $logs[] = array(
                    date('H:i:s'),
                    'warning',
                    $this->get('fhm_tools')->trans('project.install.logs.exist', array('%group%' => $group, '%name%' => 'home top'), self::$domain)
                );
            }
            // Bottom
            $object = $this->get('fhm_tools')->dmRepository('FhmGalleryBundle:Gallery')->findOneByName('home bottom');
            if($object == '')
            {
                $class  = $this->get('fhm.object.manager')->getCurrentModelName('FhmGalleryBundle:Gallery');
                $object = new $class;
                $object->setName('home bottom');
                $object->setAlias($this->get('fhm_tools')->getAlias('', 'home bottom', 'FhmGalleryBundle:Gallery'));
                $object->setActive(true);
                $this->get('fhm_tools')->dmPersist($object);
                $site->setGalleryBottom($object);
                $logs[] = array(
                    date('H:i:s'),
                    '',
                    $this->get('fhm_tools')->trans('project.install.logs.created', array('%group%' => $group, '%name%' => 'home bottom'), self::$domain)
                );
            }
            else
            {
                $site->setGalleryBottom($object);
                $logs[] = array(
                    date('H:i:s'),
                    'warning',
                    $this->get('fhm_tools')->trans('project.install.logs.exist', array('%group%' => $group, '%name%' => 'home bottom'), self::$domain)
                );
            }
            // Site
            $this->get('fhm_tools')->dmPersist($site);
        }

        // Response
        return new JsonResponse(array(
            'status' => $status,
            'post'   => $site ? array('site' => $site->getId()) : '',
            'logs'   => $logs
        ));
    }

    /**
     * @Route
     * (
     *      path="/card",
     *      name="project_install_card"
     * )
     */
    public function cardAction(Request $request)
    {
        $logs   = array();
        $group  = $this->get('fhm_tools')->trans('project.install.groups.card', array(), self::$domain);
        $status = 200;
        $site   = '';
        if($request->get('site') == '')
        {
            $group  = $this->get('fhm_tools')->trans('project.install.groups.error', array(), self::$domain);
            $status = 500;
            $logs[] = array(
                date('H:i:s'),
                'alert',
                $this->get('fhm_tools')->trans('project.install.logs.empty', array('%group%' => $group, '%name%' => 'site'), self::$domain)
            );
        }
        else
        {
            $site = $this->get('fhm_tools')->dmRepository('FhmFhmBundle:Site')->find($request->get('site'));
            // Home slider
            $object = $this->get('fhm_tools')->dmRepository('FhmCardBundle:Card')->findOneByName('home slider');
            if($object == '')
            {
                $class  = $this->get('fhm.object.manager')->getCurrentModelName('FhmCardBundle:Card');
                $object = new $class;
                $object->setName('home slider');
                $object->setAlias($this->get('fhm_tools')->getAlias('', 'home slider', 'FhmCardBundle:Card'));
                $object->setActive(true);
                $this->get('fhm_tools')->dmPersist($object);
                $site->setCardSlider($object);
                $logs[] = array(
                    date('H:i:s'),
                    '',
                    $this->get('fhm_tools')->trans('project.install.logs.created', array('%group%' => $group, '%name%' => 'home slider'), self::$domain)
                );
            }
            else
            {
                $site->setCardSlider($object);
                $logs[] = array(
                    date('H:i:s'),
                    'warning',
                    $this->get('fhm_tools')->trans('project.install.logs.exist', array('%group%' => $group, '%name%' => 'home slider'), self::$domain)
                );
            }
            // Home main
            $object = $this->get('fhm_tools')->dmRepository('FhmCardBundle:Card')->findOneByName('home main');
            if($object == '')
            {
                $class  = $this->get('fhm.object.manager')->getCurrentModelName('FhmCardBundle:Card');
                $object = new $class;
                $object->setName('home main');
                $object->setAlias($this->get('fhm_tools')->getAlias('', 'home main', 'FhmCardBundle:Card'));
                $object->setActive(true);
                $this->get('fhm_tools')->dmPersist($object);
                $site->setCardMain($object);
                $logs[] = array(
                    date('H:i:s'),
                    '',
                    $this->get('fhm_tools')->trans('project.install.logs.created', array('%group%' => $group, '%name%' => 'home main'), self::$domain)
                );
            }
            else
            {
                $site->setCardMain($object);
                $logs[] = array(
                    date('H:i:s'),
                    'warning',
                    $this->get('fhm_tools')->trans('project.install.logs.exist', array('%group%' => $group, '%name%' => 'home main'), self::$domain)
                );
            }
            // Home forward
            $object = $this->get('fhm_tools')->dmRepository('FhmCardBundle:Card')->findOneByName('home forward');
            if($object == '')
            {
                $class  = $this->get('fhm.object.manager')->getCurrentModelName('FhmCardBundle:Card');
                $object = new $class;
                $object->setName('home forward');
                $object->setAlias($this->get('fhm_tools')->getAlias('', 'home forward', 'FhmCardBundle:Card'));
                $object->setActive(true);
                $this->get('fhm_tools')->dmPersist($object);
                $site->setCardForward($object);
                $logs[] = array(
                    date('H:i:s'),
                    '',
                    $this->get('fhm_tools')->trans('project.install.logs.created', array('%group%' => $group, '%name%' => 'home forward'), self::$domain)
                );
            }
            else
            {
                $site->setCardForward($object);
                $logs[] = array(
                    date('H:i:s'),
                    'warning',
                    $this->get('fhm_tools')->trans('project.install.logs.exist', array('%group%' => $group, '%name%' => 'home forward'), self::$domain)
                );
            }
            // Site
            $this->get('fhm_tools')->dmPersist($site);
        }

        // Response
        return new JsonResponse(array(
            'status' => $status,
            'post'   => $site ? array('site' => $site->getId()) : '',
            'logs'   => $logs
        ));
    }

    /**
     * @Route
     * (
     *      path="/news",
     *      name="project_install_news"
     * )
     */
    public function newsAction(Request $request)
    {
        $logs   = array();
        $group  = $this->get('fhm_tools')->trans('project.install.groups.news', array(), self::$domain);
        $status = 200;
        $site   = '';
        if($request->get('site') == '')
        {
            $group  = $this->get('fhm_tools')->trans('project.install.groups.error', array(), self::$domain);
            $status = 500;
            $logs[] = array(
                date('H:i:s'),
                'alert',
                $this->get('fhm_tools')->trans('project.install.logs.empty', array('%group%' => $group, '%name%' => 'site'), self::$domain)
            );
        }
        else
        {
            $site = $this->get('fhm_tools')->dmRepository('FhmFhmBundle:Site')->find($request->get('site'));
            // Main
            $object = $this->get('fhm_tools')->dmRepository('FhmNewsBundle:NewsGroup')->findOneByName('home');
            if($object == '')
            {
                $class  = $this->get('fhm.object.manager')->getCurrentModelName('FhmNewsBundle:NewsGroup');
                $object = new $class;
                $object->setName('home');
                $object->setAlias($this->get('fhm_tools')->getAlias('', 'home', 'FhmNewsBundle:NewsGroup'));
                $object->setActive(true);
                $this->get('fhm_tools')->dmPersist($object);
                $site->setNews($object);
                $logs[] = array(
                    date('H:i:s'),
                    '',
                    $this->get('fhm_tools')->trans('project.install.logs.created', array('%group%' => $group, '%name%' => 'home'), self::$domain)
                );
            }
            else
            {
                $site->setNews($object);
                $logs[] = array(
                    date('H:i:s'),
                    'warning',
                    $this->get('fhm_tools')->trans('project.install.logs.exist', array('%group%' => $group, '%name%' => 'home'), self::$domain)
                );
            }
            // Site
            $this->get('fhm_tools')->dmPersist($site);
        }

        // Response
        return new JsonResponse(array(
            'status' => $status,
            'post'   => $site ? array('site' => $site->getId()) : '',
            'logs'   => $logs
        ));
    }

    /**
     * @Route
     * (
     *      path="/partner",
     *      name="project_install_partner"
     * )
     */
    public function partnerAction(Request $request)
    {
        $logs   = array();
        $group  = $this->get('fhm_tools')->trans('project.install.groups.partner', array(), self::$domain);
        $status = 200;
        $site   = '';
        if($request->get('site') == '')
        {
            $group  = $this->get('fhm_tools')->trans('project.install.groups.error', array(), self::$domain);
            $status = 500;
            $logs[] = array(
                date('H:i:s'),
                'alert',
                $this->get('fhm_tools')->trans('project.install.logs.empty', array('%group%' => $group, '%name%' => 'site'), self::$domain)
            );
        }
        else
        {
            $site = $this->get('fhm_tools')->dmRepository('FhmFhmBundle:Site')->find($request->get('site'));
            // Main
            $object = $this->get('fhm_tools')->dmRepository('FhmPartnerBundle:PartnerGroup')->findOneByName('home');
            if($object == '')
            {
                $class  = $this->get('fhm.object.manager')->getCurrentModelName('FhmPartnerBundle:PartnerGroup');
                $object = new $class;
                $object->setName('home');
                $object->setAlias($this->get('fhm_tools')->getAlias('', 'home', 'FhmPartnerBundle:PartnerGroup'));
                $object->setActive(true);
                $this->get('fhm_tools')->dmPersist($object);
                $site->setPartner($object);
                $logs[] = array(
                    date('H:i:s'),
                    '',
                    $this->get('fhm_tools')->trans('project.install.logs.created', array('%group%' => $group, '%name%' => 'home'), self::$domain)
                );
            }
            else
            {
                $site->setPartner($object);
                $logs[] = array(
                    date('H:i:s'),
                    'warning',
                    $this->get('fhm_tools')->trans('project.install.logs.exist', array('%group%' => $group, '%name%' => 'home'), self::$domain)
                );
            }
            // Site
            $this->get('fhm_tools')->dmPersist($site);
        }

        // Response
        return new JsonResponse(array(
            'status' => $status,
            'post'   => $site ? array('site' => $site->getId()) : '',
            'logs'   => $logs
        ));
    }

    /**
     * @Route
     * (
     *      path="/contact",
     *      name="project_install_contact"
     * )
     */
    public function contactAction(Request $request)
    {
        $logs   = array();
        $group  = $this->get('fhm_tools')->trans('project.install.groups.contact', array(), self::$domain);
        $status = 200;
        $site   = '';
        if($request->get('site') == '')
        {
            $group  = $this->get('fhm_tools')->trans('project.install.groups.error', array(), self::$domain);
            $status = 500;
            $logs[] = array(
                date('H:i:s'),
                'alert',
                $this->get('fhm_tools')->trans('project.install.logs.empty', array('%group%' => $group, '%name%' => 'site'), self::$domain)
            );
        }
        else
        {
            $site = $this->get('fhm_tools')->dmRepository('FhmFhmBundle:Site')->find($request->get('site'));
            // Main
            $object = $this->get('fhm_tools')->dmRepository('FhmContactBundle:Contact')->findOneByName('home');
            if($object == '')
            {
                $class  = $this->get('fhm.object.manager')->getCurrentModelName('FhmContactBundle:Contact');
                $object = new $class;
                $object->setName('home');
                $object->setAlias($this->get('fhm_tools')->getAlias('', 'home', 'FhmContactBundle:Contact'));
                $object->setActive(true);
                $this->get('fhm_tools')->dmPersist($object);
                $site->setContact($object);
                $logs[] = array(
                    date('H:i:s'),
                    '',
                    $this->get('fhm_tools')->trans('project.install.logs.created', array('%group%' => $group, '%name%' => 'home'), self::$domain)
                );
            }
            else
            {
                $site->setContact($object);
                $logs[] = array(
                    date('H:i:s'),
                    'warning',
                    $this->get('fhm_tools')->trans('project.install.logs.exist', array('%group%' => $group, '%name%' => 'home'), self::$domain)
                );
            }
            // Site
            $this->get('fhm_tools')->dmPersist($site);
        }

        // Response
        return new JsonResponse(array(
            'status' => $status,
            'post'   => $site ? array('site' => $site->getId()) : '',
            'logs'   => $logs
        ));
    }

    /**
     * @Route
     * (
     *      path="/end",
     *      name="project_install_end"
     * )
     */
    public function endAction(Request $request)
    {
        $logs   = array();
        $status = 200;
        if($request->get('site') == '')
        {
            $group  = $this->get('fhm_tools')->trans('project.install.groups.error', array(), self::$domain);
            $status = 500;
            $logs[] = array(
                date('H:i:s'),
                'alert',
                $this->get('fhm_tools')->trans('project.install.logs.empty', array('%group%' => $group, '%name%' => 'site'), self::$domain)
            );
        }
        else
        {
            $object = $this->get('fhm_tools')->dmRepository('FhmFhmBundle:Site')->find($request->get('site'));
            $object->setDefault(true);
            $this->get('fhm_tools')->dmPersist($object);
            $logs[] = array(
                date('H:i:s'),
                'success',
                $this->get('fhm_tools')->trans('project.install.logs.end', array(), self::$domain)
            );
        }

        // Response
        return new JsonResponse(array(
            'status' => $status,
            'post'   => '',
            'logs'   => $logs
        ));
    }
}