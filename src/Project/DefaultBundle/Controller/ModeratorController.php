<?php

namespace Project\DefaultBundle\Controller;

use Fhm\FhmBundle\Controller\GenericController;
use Project\DefaultBundle\Form\Handler\Moderator\DefaultHandler;
use Project\DefaultBundle\Form\Type\Moderator\SiteType;
use Project\DefaultBundle\Form\Type\Moderator\MenuType;
use Project\DefaultBundle\Form\Type\Moderator\SliderType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Session\Session;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * @Route("/moderator")
 */
class ModeratorController extends GenericController
{
    /**
     * FrontController constructor.
     */
    public function __construct()
    {
        self::$source               = "project";
        self::$domain               = "ProjectDefaultBundle";
        self::$translation          = "project";
        self::$route                = "project";
        self::$form                 = new \stdClass();
        self::$form->defaultHandler = DefaultHandler::class;
    }

    /**
     * @Route
     * (
     *      path="/",
     *      name="project_moderator"
     * )
     * @Template("::ProjectDefault/Moderator/index.html.twig")
     */
    public function indexAction(Request $request)
    {
        $site = $this->get('fhm_tools')->dmRepository('FhmFhmBundle:Site')->getDefault();

        return array(
            'site'          => $site,
            'current'       => $request->get('current'),
            'count_message' => $site->getContact()->getMessages()->count()
        );
    }

    /**
     * @Route
     * (
     *      path="/index/menu",
     *      name="project_moderator_index_menu"
     * )
     * @Template("::ProjectDefault/Moderator/index.menu.html.twig")
     */
    public function indexMenuAction(Request $request)
    {
        $site = $this->get('fhm_tools')->dmRepository('FhmFhmBundle:Site')->getDefault();

        return array(
            'site'    => $site,
            'current' => $request->get('current')
        );
    }

    /**
     * @Route
     * (
     *      path="/show",
     *      name="project_moderator_show"
     * )
     */
    public function showAction(Request $request)
    {
        $site = $this->get('fhm_tools')->dmRepository('FhmFhmBundle:Site')->getDefault();
        if($data = $request->get('show'))
        {
            // Site
            $site->setShowSlider(isset($data['check']) ? true : false);
            $this->get('fhm_tools')->dmPersist($site);
            // Redirect
            $router = $this->get('router');
            $route  = 'project_moderator_' . strtolower($data['route']);
            if($router->getRouteCollection()->get($route))
            {
                return $this->redirect($this->generateUrl($route));
            }
        }

        return new Response();
    }

    /**
     * @Route
     * (
     *      path="/site",
     *      name="project_moderator_site"
     * )
     * @Template("::ProjectDefault/Moderator/site.html.twig")
     */
    public function siteAction(Request $request)
    {
        $message = array('', '');
        $object  = $this->get('fhm_tools')->dmRepository('FhmFhmBundle:Site')->getDefault();
        $class   = $this->get('fhm.object.manager')->getCurrentModelName('FhmFhmBundle:Site');
        $form    = $this->createForm(
            SiteType::class,
            $object,
            array(
                'user_admin'     => $this->getUser()->hasRole('ROLE_SUPER_ADMIN'),
                'data_class'     => $class,
                'object_manager' => $this->get('fhm.object.manager'),
            )
        );
        $handler = new self::$form->defaultHandler($form, $request);
        $process = $handler->process();
        if($process)
        {
            $object->setUserUpdate($this->getUser());
            $this->get('fhm_tools')->dmPersist($object);
            $message = array('success', $this->trans(self::$translation . '.moderator.form.message.success'));
        }

        return array(
            'site'    => $object,
            'form'    => $form->createView(),
            'message' => $message
        );
    }

    /**
     * @Route
     * (
     *      path="/menu",
     *      name="project_moderator_menu"
     * )
     * @Template("::ProjectDefault/Moderator/menu.html.twig")
     */
    public function menuAction(Request $request)
    {
        $message = array('', '');
        $site    = $this->get('fhm_tools')->dmRepository('FhmFhmBundle:Site')->getDefault();

        return array(
            'site'    => $site,
            'message' => $message
        );
    }

    /**
     * @Route
     * (
     *      path="/menu/sort",
     *      name="project_moderator_menu_sort"
     * )
     */
    public function menuSortAction(Request $request)
    {
        $this->get('project_moderator')->menuSort($request);

        return new Response();
    }

    /**
     * @Route
     * (
     *      path="/menu/delete/{menu}/{id}",
     *      name="project_moderator_menu_delete",
     *      requirements={"id"="[a-z0-9]*"}
     * )
     */
    public function menuDeleteAction(Request $request, $menu, $id)
    {
        $this->get('project_moderator')->menuDelete($id);
        $router = $this->get('router');
        $route  = 'project_moderator_menu_' . strtolower($menu);
        if($router->getRouteCollection()->get($route))
        {
            return $this->redirect($this->generateUrl($route));
        }

        return new Response();
    }

    /**
     * @Route
     * (
     *      path="/menu/main/{id}",
     *      name="project_moderator_menu_main",
     *      requirements={"id"=".+"},
     *      defaults={"id"=null}
     * )
     * @Template("::ProjectDefault/Moderator/menu.main.html.twig")
     */
    public function menuMainAction(Request $request, $id)
    {
        $message = array('', '');
        $site    = $this->get('fhm_tools')->dmRepository('FhmFhmBundle:Site')->getDefault();
        $parent  = $site->getMenu();
        $class   = $this->get('fhm.object.manager')->getCurrentModelName('FhmFhmBundle:Menu');
        $object  = is_null($id) ? new $class() : $this->get('fhm_tools')->dmRepository('FhmFhmBundle:Menu')->find($id);
        $form    = $this->createForm(
            MenuType::class,
            $object,
            array(
                'user_admin'     => $this->getUser()->hasRole('ROLE_SUPER_ADMIN'),
                'data_class'     => $class,
                'object_manager' => $this->get('fhm.object.manager'),
            )
        );
        $handler = new self::$form->defaultHandler($form, $request);
        $process = $handler->process();
        if($process)
        {
            // Create
            if(is_null($id))
            {
                $object->setUserCreate($this->getUser());
                $object->setParent($parent->getId());
                $object->setActive(true);
                $parent->addChild($object);
                $message = array('success', $this->trans(self::$translation . '.moderator.form.message.create'));
            }
            // Update
            else
            {
                $object->setUserUpdate($this->getUser());
                $message = array('success', $this->trans(self::$translation . '.moderator.form.message.success'));
            }
            $this->get('fhm_tools')->dmPersist($object);
            $this->get('fhm_tools')->dmPersist($parent);
            // Reset form
            unset($object);
            unset($form);
            $object = new $class();
            $form   = $this->createForm(
                MenuType::class,
                $object,
                array(
                    'user_admin'     => $this->getUser()->hasRole('ROLE_SUPER_ADMIN'),
                    'data_class'     => $class,
                    'object_manager' => $this->get('fhm.object.manager'),
                )
            );
        }

        return array(
            'site'    => $site,
            'id'      => $id,
            'form'    => $form->createView(),
            'message' => $message
        );
    }

    /**
     * @Route
     * (
     *      path="/menu/footer/{id}",
     *      name="project_moderator_menu_footer",
     *      requirements={"id"=".+"},
     *      defaults={"id"=null}
     * )
     * @Template("::ProjectDefault/Moderator/menu.footer.html.twig")
     */
    public function menuFooterAction(Request $request, $id)
    {
        $message = array('', '');
        $site    = $this->get('fhm_tools')->dmRepository('FhmFhmBundle:Site')->getDefault();
        $parent  = $site->getMenuFooter();
        $class   = $this->get('fhm.object.manager')->getCurrentModelName('FhmFhmBundle:Menu');
        $object  = is_null($id) ? new $class() : $this->get('fhm_tools')->dmRepository('FhmFhmBundle:Menu')->find($id);
        $form    = $this->createForm(
            MenuType::class,
            $object,
            array(
                'user_admin'     => $this->getUser()->hasRole('ROLE_SUPER_ADMIN'),
                'data_class'     => $class,
                'object_manager' => $this->get('fhm.object.manager'),
            )
        );
        $handler = new self::$form->defaultHandler($form, $request);
        $process = $handler->process();
        if($process)
        {
            // Create
            if(is_null($id))
            {
                $object->setUserCreate($this->getUser());
                $object->setParent($parent->getId());
                $object->setActive(true);
                $parent->addChild($object);
                $message = array('success', $this->trans(self::$translation . '.moderator.form.message.create'));
            }
            // Update
            else
            {
                $object->setUserUpdate($this->getUser());
                $message = array('success', $this->trans(self::$translation . '.moderator.form.message.success'));
            }
            $this->get('fhm_tools')->dmPersist($object);
            $this->get('fhm_tools')->dmPersist($parent);
            // Reset form
            unset($object);
            unset($form);
            $object = new $class();
            $form   = $this->createForm(
                MenuType::class,
                $object,
                array(
                    'user_admin'     => $this->getUser()->hasRole('ROLE_SUPER_ADMIN'),
                    'data_class'     => $class,
                    'object_manager' => $this->get('fhm.object.manager'),
                )
            );
        }

        return array(
            'site'    => $site,
            'id'      => $id,
            'form'    => $form->createView(),
            'message' => $message
        );
    }

    /**
     * @Route
     * (
     *      path="/menu/home-side/{id}",
     *      name="project_moderator_menu_home_side",
     *      requirements={"id"=".+"},
     *      defaults={"id"=null}
     * )
     * @Template("::ProjectDefault/Moderator/menu.home.side.html.twig")
     */
    public function menuHomeSideAction(Request $request, $id)
    {
        $message = array('', '');
        $site    = $this->get('fhm_tools')->dmRepository('FhmFhmBundle:Site')->getDefault();
        $parent  = $site->getMenuHomeSide();
        $class   = $this->get('fhm.object.manager')->getCurrentModelName('FhmFhmBundle:Menu');
        $object  = is_null($id) ? new $class() : $this->get('fhm_tools')->dmRepository('FhmFhmBundle:Menu')->find($id);
        $form    = $this->createForm(
            MenuType::class,
            $object,
            array(
                'user_admin'     => $this->getUser()->hasRole('ROLE_SUPER_ADMIN'),
                'data_class'     => $class,
                'object_manager' => $this->get('fhm.object.manager'),
            )
        );
        $handler = new self::$form->defaultHandler($form, $request);
        $process = $handler->process();
        if($process)
        {
            // Create
            if(is_null($id))
            {
                $object->setUserCreate($this->getUser());
                $object->setParent($parent->getId());
                $object->setActive(true);
                $parent->addChild($object);
                $message = array('success', $this->trans(self::$translation . '.moderator.form.message.create'));
            }
            // Update
            else
            {
                $object->setUserUpdate($this->getUser());
                $message = array('success', $this->trans(self::$translation . '.moderator.form.message.success'));
            }
            $this->get('fhm_tools')->dmPersist($object);
            $this->get('fhm_tools')->dmPersist($parent);
            // Reset form
            unset($object);
            unset($form);
            $object = new $class();
            $form   = $this->createForm(
                MenuType::class,
                $object,
                array(
                    'user_admin'     => $this->getUser()->hasRole('ROLE_SUPER_ADMIN'),
                    'data_class'     => $class,
                    'object_manager' => $this->get('fhm.object.manager'),
                )
            );
        }

        return array(
            'site'    => $site,
            'id'      => $id,
            'form'    => $form->createView(),
            'message' => $message
        );
    }

    /**
     * @Route
     * (
     *      path="/menu/home-left/{id}",
     *      name="project_moderator_menu_home_left",
     *      requirements={"id"=".+"},
     *      defaults={"id"=null}
     * )
     * @Template("::ProjectDefault/Moderator/menu.home.left.html.twig")
     */
    public function menuHomeLeftAction(Request $request, $id)
    {
        $message = array('', '');
        $site    = $this->get('fhm_tools')->dmRepository('FhmFhmBundle:Site')->getDefault();
        $parent  = $site->getMenuHomeLeft();
        $class   = $this->get('fhm.object.manager')->getCurrentModelName('FhmFhmBundle:Menu');
        $object  = is_null($id) ? new $class() : $this->get('fhm_tools')->dmRepository('FhmFhmBundle:Menu')->find($id);
        $form    = $this->createForm(
            MenuType::class,
            $object,
            array(
                'user_admin'     => $this->getUser()->hasRole('ROLE_SUPER_ADMIN'),
                'data_class'     => $class,
                'object_manager' => $this->get('fhm.object.manager'),
            )
        );
        $handler = new self::$form->defaultHandler($form, $request);
        $process = $handler->process();
        if($process)
        {
            // Create
            if(is_null($id))
            {
                $object->setUserCreate($this->getUser());
                $object->setParent($parent->getId());
                $object->setActive(true);
                $parent->addChild($object);
                $message = array('success', $this->trans(self::$translation . '.moderator.form.message.create'));
            }
            // Update
            else
            {
                $object->setUserUpdate($this->getUser());
                $message = array('success', $this->trans(self::$translation . '.moderator.form.message.success'));
            }
            $this->get('fhm_tools')->dmPersist($object);
            $this->get('fhm_tools')->dmPersist($parent);
            // Reset form
            unset($object);
            unset($form);
            $object = new $class();
            $form   = $this->createForm(
                MenuType::class,
                $object,
                array(
                    'user_admin'     => $this->getUser()->hasRole('ROLE_SUPER_ADMIN'),
                    'data_class'     => $class,
                    'object_manager' => $this->get('fhm.object.manager'),
                )
            );
        }

        return array(
            'site'    => $site,
            'id'      => $id,
            'form'    => $form->createView(),
            'message' => $message
        );
    }

    /**
     * @Route
     * (
     *      path="/menu/home-right/{id}",
     *      name="project_moderator_menu_home_right",
     *      requirements={"id"=".+"},
     *      defaults={"id"=null}
     * )
     * @Template("::ProjectDefault/Moderator/menu.home.right.html.twig")
     */
    public function menuHomeRightAction(Request $request, $id)
    {
        $message = array('', '');
        $site    = $this->get('fhm_tools')->dmRepository('FhmFhmBundle:Site')->getDefault();
        $parent  = $site->getMenuHomeRight();
        $class   = $this->get('fhm.object.manager')->getCurrentModelName('FhmFhmBundle:Menu');
        $object  = is_null($id) ? new $class() : $this->get('fhm_tools')->dmRepository('FhmFhmBundle:Menu')->find($id);
        $form    = $this->createForm(
            MenuType::class,
            $object,
            array(
                'user_admin'     => $this->getUser()->hasRole('ROLE_SUPER_ADMIN'),
                'data_class'     => $class,
                'object_manager' => $this->get('fhm.object.manager'),
            )
        );
        $handler = new self::$form->defaultHandler($form, $request);
        $process = $handler->process();
        if($process)
        {
            // Create
            if(is_null($id))
            {
                $object->setUserCreate($this->getUser());
                $object->setParent($parent->getId());
                $object->setActive(true);
                $parent->addChild($object);
                $message = array('success', $this->trans(self::$translation . '.moderator.form.message.create'));
            }
            // Update
            else
            {
                $object->setUserUpdate($this->getUser());
                $message = array('success', $this->trans(self::$translation . '.moderator.form.message.success'));
            }
            $this->get('fhm_tools')->dmPersist($object);
            $this->get('fhm_tools')->dmPersist($parent);
            // Reset form
            unset($object);
            unset($form);
            $object = new $class();
            $form   = $this->createForm(
                MenuType::class,
                $object,
                array(
                    'user_admin'     => $this->getUser()->hasRole('ROLE_SUPER_ADMIN'),
                    'data_class'     => $class,
                    'object_manager' => $this->get('fhm.object.manager'),
                )
            );
        }

        return array(
            'site'    => $site,
            'id'      => $id,
            'form'    => $form->createView(),
            'message' => $message
        );
    }

    /**
     * @Route
     * (
     *      path="/slider/delete/{id}",
     *      name="project_moderator_slider_delete",
     *      requirements={"id"="[a-z0-9]*"}
     * )
     */
    public function sliderDeleteAction(Request $request, $id)
    {
        $this->get('project_moderator')->sliderDelete($id);

        return $this->redirect($this->generateUrl('project_moderator_slider'));
    }

    /**
     * @Route
     * (
     *      path="/slider/{id}",
     *      name="project_moderator_slider",
     *      requirements={"id"=".+"},
     *      defaults={"id"=null}
     * )
     * @Template("::ProjectDefault/Moderator/slider.html.twig")
     */
    public function sliderAction(Request $request, $id)
    {
        $message = array('', '');
        $site    = $this->get('fhm_tools')->dmRepository('FhmFhmBundle:Site')->getDefault();
        $parent  = $site->getSlider();
        $class   = $this->get('fhm.object.manager')->getCurrentModelName('FhmSliderBundle:SliderItem');
        $object  = is_null($id) ? new $class() : $this->get('fhm_tools')->dmRepository('FhmSliderBundle:SliderItem')->find($id);
        $form    = $this->createForm(
            SliderType::class,
            $object,
            array(
                'user_admin'     => $this->getUser()->hasRole('ROLE_SUPER_ADMIN'),
                'data_class'     => $class,
                'object_manager' => $this->get('fhm.object.manager'),
            )
        );
        $handler = new self::$form->defaultHandler($form, $request);
        $process = $handler->process();
        if($process)
        {
            // Show
            // Create
            if(is_null($id))
            {
                $object->setUserCreate($this->getUser());
                $object->setActive(true);
                $object->addSlider($parent);
                $message = array('success', $this->trans(self::$translation . '.moderator.form.message.create'));
            }
            // Update
            else
            {
                $object->setUserUpdate($this->getUser());
                $message = array('success', $this->trans(self::$translation . '.moderator.form.message.success'));
            }
            $this->get('fhm_tools')->dmPersist($object);
            $this->get('fhm_tools')->dmPersist($parent);
            // Reset form
            unset($object);
            unset($form);
            $object = new $class();
            $form   = $this->createForm(
                SliderType::class,
                $object,
                array(
                    'user_admin'     => $this->getUser()->hasRole('ROLE_SUPER_ADMIN'),
                    'data_class'     => $class,
                    'object_manager' => $this->get('fhm.object.manager'),
                )
            );
        }

        return array(
            'site'    => $site,
            'id'      => $id,
            'form'    => $form->createView(),
            'message' => $message
        );
    }
}