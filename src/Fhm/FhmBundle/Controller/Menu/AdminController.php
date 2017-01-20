<?php
namespace Fhm\FhmBundle\Controller\Menu;

use Fhm\FhmBundle\Controller\RefAdminController as FhmController;
use Fhm\FhmBundle\Form\Handler\Admin\CreateHandler;
use Fhm\FhmBundle\Form\Handler\Admin\UpdateHandler;
use Fhm\FhmBundle\Form\Type\Admin\Menu\CreateType;
use Fhm\FhmBundle\Form\Type\Admin\Menu\UpdateType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\Common\Collections\ArrayCollection;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * @Route("/admin/menu")
 * -----------------------------------
 * Class AdminController
 * @package Fhm\FhmBundle\Controller\Menu
 */
class AdminController extends FhmController
{
    /**
     * AdminController constructor.
     */
    public function __construct()
    {
        self::$repository = "FhmFhmBundle:Menu";
        self::$source = "fhm";
        self::$domain = "FhmFhmMenu";
        self::$translation = "menu";
        self::$route = "menu";
        self::$form = new \stdClass();
        self::$form->createType = CreateType::class;
        self::$form->createHandler = CreateHandler::class;
        self::$form->updateType = UpdateType::class;
        self::$form->updateHandler = UpdateHandler::class;
    }

    /**
     * @Route
     * (
     *      path="/",
     *      name="fhm_admin_menu"
     * )
     * @Template("::FhmFhm/Menu/Admin/index.html.twig")
     */
    public function indexAction()
    {
        return parent::indexAction();
    }

    /**
     * @Route
     * (
     *      path="/create",
     *      name="fhm_admin_menu_create"
     * )
     * @Template("::FhmFhm/Menu/Admin/create.html.twig")
     */
    public function createAction(Request $request)
    {
        self::$class = $this->get('fhm.object.manager')->getCurrentModelName(self::$repository);
        $object = new self::$class;
        $form = $this->createForm(
            self::$form->createType,
            $object,
            ['data_class' => self::$class, 'object_manager' => $this->get('fhm.object.manager')]
        );
        $handler = new self::$form->createHandler($form, $request);
        $process = $handler->process();
        if ($process) {
            $data = $request->get($form->getName());
            $this->get('fhm_tools')->dmPersist($object);
            if ($data['id']) {
                $this->__treeDuplicate($data['id'], $object->getId());
            }
            $this->get('session')->getFlashBag()->add(
                'notice',
                $this->get('translator')->trans(
                    self::$translation.'.admin.create.flash.ok',
                    array(),
                    self::$domain
                )
            );
            return $this->redirectUrl($data, $object);
        }

        return array(
            'form' => $form->createView(),
            'object' => $object,
            'modules' => $this->getParameters('modules', 'fhm_menu'),
            'breadcrumbs' => $this->get('fhm_tools')->generateBreadcrumbs(
                array(
                    'domain' => self::$domain,
                    '_route' => $this->get('request_stack')->getCurrentRequest()->get('_route'),
                ),
                $object
            ),
        );
    }

    /**
     * @Route
     * (
     *      path="/detail/{id}",
     *      name="fhm_admin_menu_detail",
     *      requirements={"id"="[a-z0-9]*"}
     * )
     * @Template("::FhmFhm/Menu/Admin/detail.html.twig")
     */
    public function detailAction($id)
    {
        $tree = $this->get('fhm_tools')->dmRepository(self::$repository)->getTree($id);
        $treemap = $this->get('fhm_tools')->dmRepository(self::$repository)->getTreeMap($id);

        return array_merge(
            parent::detailAction($id),
            array(
                "tree" => $tree,
                "treemap" => $treemap,
                "modules" => $this->getParameters('modules', 'fhm_menu'),
            )
        );
    }

    /**
     * @Route
     * (
     *      path="/update/{id}",
     *      name="fhm_admin_menu_update",
     *      requirements={"id"="[a-z0-9]*"}
     * )
     * @Template("::FhmFhm/Menu/Admin/update.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $response = parent::updateAction($request, $id);
        $path = (is_object($response)) ? $response : '';

        return (is_array($response)) ? array_merge(
            $response,
            array("modules" => $this->getParameters('modules', 'fhm_menu'))
        ) : $path;
    }

    /**
     * @Route
     * (
     *      path="/duplicate/{id}",
     *      name="fhm_admin_menu_duplicate",
     *      requirements={"id"="[a-z0-9]*"}
     * )
     * @Template("::FhmFhm/Menu/Admin/create.html.twig")
     */
    public function duplicateAction(Request $request, $id)
    {
        return parent::duplicateAction($request, $id);
    }

    /**
     * @Route
     * (
     *      path="/delete/{id}",
     *      name="fhm_admin_menu_delete",
     *      requirements={"id"="[a-z0-9]*"}
     * )
     */
    public function deleteAction($id)
    {
        $object = $this->get('fhm_tools')->dmRepository(self::$repository)->find($id);
        $delete = ($object->getDelete()) ? true : false;
        $this->__treeDelete($id, $object, $delete);
        parent::deleteAction($id);

        return $this->redirect($this->get('fhm_tools')->getLastRoute($this->get('request_stack')->getCurrentRequest()));
    }

    /**
     * @Route
     * (
     *      path="/undelete/{id}",
     *      name="fhm_admin_menu_undelete",
     *      requirements={"id"="[a-z0-9]*"}
     * )
     */
    public function undeleteAction($id)
    {
        parent::undeleteAction($id);
        $this->__treeUndelete($id);

        return $this->redirect($this->get('fhm_tools')->getLastRoute($this->get('request_stack')->getCurrentRequest()));
    }

    /**
     * @Route
     * (
     *      path="/activate/{id}",
     *      name="fhm_admin_menu_activate",
     *      requirements={"id"="[a-z0-9]*"}
     * )
     */
    public function activateAction($id)
    {
        parent::activateAction($id);
        $this->__treeActive($id, true);

        return $this->redirect($this->get('fhm_tools')->getLastRoute($this->get('request_stack')->getCurrentRequest()));
    }

    /**
     * @Route
     * (
     *      path="/deactivate/{id}",
     *      name="fhm_admin_menu_deactivate",
     *      requirements={"id"="[a-z0-9]*"}
     * )
     */
    public function deactivateAction($id)
    {
        parent::deactivateAction($id);
        $this->__treeActive($id, false);

        return $this->redirect($this->get('fhm_tools')->getLastRoute($this->get('request_stack')->getCurrentRequest()));
    }

    /**
     * @Route
     * (
     *      path="/import",
     *      name="fhm_admin_menu_import"
     * )
     * @Template("::FhmFhm/Menu/Admin/import.html.twig")
     */
    public function importAction(Request $request)
    {
        return parent::importAction($request);
    }

    /**
     * @Route
     * (
     *      path="/export",
     *      name="fhm_admin_menu_export"
     * )
     * @Template("::FhmFhm/Menu/Admin/export.html.twig")
     */
    public function exportAction(Request $request)
    {
        return parent::exportAction($request);
    }

    /**
     * @Route
     * (
     *      path="/add",
     *      name="fhm_admin_menu_add"
     * )
     */
    public function addAction(Request $request)
    {
        $datas = $request->get('FhmAdd');
        self::$class = $this->get('fhm.object.manager')->getCurrentModelName(self::$repository);
        $object = new self::$class;
        $objectParent = $this->get('fhm_tools')->dmRepository(self::$repository)->find($datas['parent']);
        if ($datas['module'] != '' && $datas['data'] != '') {
            if ($datas['module'] == "newsGroup") {
                $objectMenu = $this->get('fhm_tools')->dmRepository('FhmNewsBundle:'.ucfirst($datas['module']))->find(
                    $datas['data']
                );
            } else {
                $objectMenu = $this->get('fhm_tools')->dmRepository(
                    'Fhm'.ucfirst($datas['module']).'Bundle:'.ucfirst($datas['module'])
                )->find($datas['data']);
            }
            if (!empty($datas['name'])) {
                $object->setName($datas['name']);
            } else {
                $object->setName($objectMenu->getName());
            }
            $object->setRoute($this->__getRoute($datas['module'], $objectMenu));
            $object->setModule(
                array(
                    'type' => $datas['module'],
                    'id' => $datas['data'],
                    "name" => $objectMenu->getName(),
                )
            );
        } else {
            $object->setName($datas['name']);
            $object->setRoute($datas['route']);
        }
        $object->setParent($datas['parent']);
        $object->setDescription($datas['description']);
        $object->setIcon($datas['icon']);
        $object->setAlias($this->getAlias($object->getId(), $object->getName(), self::$repository));
        $objectParent->addChild($object);
        $this->get('fhm_tools')->dmPersist($object);
        $this->get('fhm_tools')->dmPersist($objectParent);

        return $this->redirect($this->get('fhm_tools')->getLastRoute($this->get('request_stack')->getCurrentRequest()));
    }

    /**
     * @Route
     * (
     *      path="/edit",
     *      name="fhm_admin_menu_edit"
     * )
     */
    public function editAction(Request $request)
    {
        $datas = $request->get('FhmUpdate');
        $object = $this->get('fhm_tools')->dmRepository(self::$repository)->find($datas['id']);
        if ($object == "") {
            throw $this->createNotFoundException(
                $this->get('translator')->trans(
                    self::$source.'.error.unknown',
                    array(),
                    self::$domain
                )
            );
        }
        if ($datas['module'] != '' && $datas['data'] != '') {
            if ($datas['module'] == "newsGroup") {
                $objectMenu = $this->get('fhm_tools')->dmRepository('FhmNewsBundle:'.ucfirst($datas['module']))->find(
                    $datas['data']
                );
            } else {
                $objectMenu = $this->get('fhm_tools')->dmRepository(
                    'Fhm'.ucfirst($datas['module']).'Bundle:'.ucfirst($datas['module'])
                )->find($datas['data']);
            }
            if (!empty($datas['name'])) {
                $object->setName($datas['name']);
            } else {
                $object->setName($objectMenu->getName());
            }
            $object->setRoute($this->__getRoute($datas['module'], $objectMenu));
            $object->setModule(
                array('type' => $datas['module'], 'id' => $datas['data'], "name" => $objectMenu->getName())
            );
        } else {
            $object->setName($datas['name']);
            $object->setRoute($datas['route']);
        }
        $object->setDescription($datas['description']);
        $object->setIcon($datas['icon']);
        $object->setAlias($this->get('fhm_tools')->getAlias($object->getId(), $object->getName(), self::$repository));
        $this->get('fhm_tools')->dmPersist($object);

        return $this->redirect($this->get('fhm_tools')->getLastRoute($request));
    }

    /**
     * @Route
     * (
     *      path="/sort",
     *      name="fhm_admin_menu_sort"
     * )
     */
    public function sortAction(Request $request)
    {
        $id = $request->get('master');
        $list = json_decode($request->get('list'));
        $this->__treeSort($id, $list);

        return new Response();
    }

    /**
     * @Route
     * (
     *      path="/module",
     *      name="fhm_admin_menu_module"
     * )
     * @Template("::FhmFhm/Menu/Admin/data.html.twig")
     */
    public function moduleAction(Request $request)
    {
        $module = $request->get('module');
        if ($module == "newsGroup") {
            $datasRepository = $this->get('fhm_tools')->dmRepository('FhmNewsBundle:'.ucfirst($module))->findAll();
        } else {
            $datasRepository = $this->get('fhm_tools')->dmRepository(
                'Fhm'.ucfirst($module).'Bundle:'.ucfirst($module)
            )->findAll();
        }

        return array(
            'instance' => $this->getProperties(),
            'datas' => $datasRepository,
            'module' => $module,
        );
    }


    /**
     * @Route
     * (
     *      path="/addmodule",
     *      name="fhm_admin_menu_addmodule"
     * )
     */
    public function addmoduleAction(Request $request)
    {
        $datas = $request->get('FhmAdd');
        $object = $this->get('fhm_tools')->dmRepository(self::$repository)->find($datas['id']);
        if ($datas['module'] != '' && $datas['data'] != '') {
            if ($datas['module'] == "newsGroup") {
                $objectMenu = $this->get('fhm_tools')->dmRepository('FhmNewsBundle:'.ucfirst($datas['module']))->find(
                    $datas['data']
                );
            } else {
                $objectMenu = $this->get('fhm_tools')->dmRepository(
                    'Fhm'.ucfirst($datas['module']).'Bundle:'.ucfirst($datas['module'])
                )->find($datas['data']);
            }
            $object->setRoute($this->__getRoute($datas['module'], $objectMenu));
            $object->setModule(
                array('type' => $datas['module'], 'id' => $datas['data'], "name" => $objectMenu->getName())
            );
            $this->get('fhm_tools')->dmPersist($object);
        }

        return $this->redirect($this->get('fhm_tools')->getLastRoute($request));
    }

    /**
     * Tree delete
     *
     * @param String $idp
     * @param         $object
     * @param Boolean $delete
     *
     * @return self
     */
    private function __treeDelete($idp, $object, $delete)
    {
        $sons = $this->get('fhm_tools')->dmRepository(self::$repository)->getSons($idp);
        // remove childs form parent
        if ($delete && $object->getParent() != '0') {
            $objectParent = $this->get('fhm_tools')->dmRepository(self::$repository)->find($object->getParent());
            $objectParent->removeChild($object);
            $this->get('fhm_tools')->dmPersist($objectParent);
        }
        //delete all childs
        foreach ($sons as $son) {
            $this->__treeDelete($son->getId(), $son, $delete);
            if ($delete) {
                $this->get('fhm_tools')->dmRemove($son);
            } else {
                $son->setDelete(true);
                $this->get('fhm_tools')->dmPersist($son);
            }
        }

        return $this;
    }

    /**
     * Tree undelete
     *
     * @param String $idp
     *
     * @return self
     */
    private function __treeUndelete($idp)
    {
        $objectParent = $this->get('fhm_tools')->dmRepository(self::$repository)->find($idp);
        foreach ($objectParent->getChilds() as $son) {
            $this->__treeUndelete($son->getId());
            $son->setDelete(false);
            $this->get('fhm_tools')->dmPersist($son);
        }

        return $this;
    }

    /**
     * Tree active
     *
     * @param String $idp
     * @param Boolean $active
     *
     * @return self
     */
    private function __treeActive($idp, $active)
    {
        $objectParent = $this->get('fhm_tools')->dmRepository(self::$repository)->find($idp);
        foreach ($objectParent->getChilds() as $son) {
            $this->__treeActive($son->getId(), $active);
            $son->setActive($active);
            $this->get('fhm_tools')->dmPersist($son);
        }

        return $this;
    }

    /**
     * Tree sort
     *
     * @param Array $list
     * @param String $parent
     *
     * @return self
     */
    private function __treeSort($parent, $list)
    {
        $order = 1;
        $objectParent = $this->get('fhm_tools')->dmRepository(self::$repository)->find($parent);
        $tabChilds = $objectParent->getChilds();
        $objectParent->setChilds(new ArrayCollection());
        foreach ($list as $obj) {
            $object = $this->get('fhm_tools')->dmRepository(self::$repository)->find($obj->id);
            if (isset($obj->children)) {
                $this->__treeSort($obj->id, $obj->children);
            }
            // change order in parent
            foreach ($tabChilds as $key => $son) {
                if ($son->getId() == $obj->id) {
                    $objectParent->addChild($son);
                }
            }
            //add new child in parent and remove child in old parent
            if ($parent == $objectParent->getId()) {
                $objectOldParent = $this->get('fhm_tools')->dmRepository(self::$repository)->find(
                    $object->getParent()
                );
                $objectOldParent->removeChild($object);
                $objectParent->addChild($object);
                $this->get('fhm_tools')->dmPersist($objectOldParent);
            }
            $object->setOrder($order);
            $object->setParent($parent);
            $this->get('fhm_tools')->dmPersist($object);
            $this->get('fhm_tools')->dmPersist($objectParent);
            $order++;
        }

        return $this;
    }

    /**
     * Tree duplicate
     *
     * @param $ids
     * @param $idt
     *
     * @return $this
     */
    private function __treeDuplicate($ids, $idt)
    {
        $objectSource = $this->get('fhm_tools')->dmRepository(self::$repository)->find($ids);
        $objectTarget = $this->get('fhm_tools')->dmRepository(self::$repository)->find($idt);
        self::$class  = $this->get('fhm.object.manager')->getCurrentModelName(self::$repository);
        if ($objectSource and $objectSource->getChilds()) {
            foreach ($objectSource->getChilds() as $child) {
                $object = new self::$class;
                $object->setName($child->getName());
                $object->setDescription($child->getDescription());
                $object->setRoute($child->getRoute());
                $object->setIcon($child->getIcon());
                $object->setUserCreate($this->getUser());
                $object->setDelete($child->getDelete());
                $object->setActive($child->getActive());
                $object->setShare($child->getShare());
                $object->setGlobal($child->getGlobal());
                $object->setOrder($child->getOrder());
                $object->setModule($child->getModule());
                $object->setParent($idt);
                $object->setAlias($this->getAlias($object->getId(), $object->getName()));
                $this->get('fhm_tools')->dmRepository(self::$repository)->dmPersist($object);
                $objectTarget->addChild($object);
                $this->__treeDuplicate($child->getId(), $object->getId());
            }
            $this->get('fhm_tools')->dmPersist($objectTarget);
        }

        return $this;
    }

    /**
     * @param $module
     * @param $object
     *
     * @return string
     */
    private function __getRoute($module, $object)
    {
        if ($module === "media") {
            $route = $this->get($this->getParameters('service', 'fhm_media'))->setDocument(
                $object
            )->getPathWeb();
        } else {
            $route = '/'.$module.'/detail/'.$object->getAlias();
        }

        return $route;
    }
}