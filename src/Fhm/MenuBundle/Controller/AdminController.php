<?php
namespace Fhm\MenuBundle\Controller;

use Fhm\FhmBundle\Controller\RefAdminController as FhmController;
use Fhm\MenuBundle\Document\Menu;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\Common\Collections\ArrayCollection;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * @Route("/admin/menu")
 */
class AdminController extends FhmController
{
    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct('Fhm', 'Menu', 'menu');
        $this->setParent(true);
    }

    /**
     * @Route
     * (
     *      path="/",
     *      name="fhm_admin_menu"
     * )
     * @Template("::FhmMenu/Admin/index.html.twig")
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
     * @Template("::FhmMenu/Admin/create.html.twig")
     */
    public function createAction(Request $request)
    {
        // ERROR - Unknown route
        if(!$this->routeExists($this->source . '_admin_' . $this->route) || !$this->routeExists($this->source . '_admin_' . $this->route . '_create'))
        {
            throw $this->createNotFoundException($this->get('translator')->trans('fhm.error.route', array(), 'FhmFhmBundle'));
        }
        $document     = $this->document;
        $instance     = $this->instanceData();
        $classType    = $this->form->type->create;
        $classHandler = $this->form->handler->create;
        $form         = $this->createForm(new $classType($instance, $document), $document);
        $handler      = new $classHandler($form, $request);
        $process      = $handler->process();
        if($process)
        {
            $data = $request->get($form->getName());
            // Persist
            $document->setUserCreate($this->getUser());
            $document->setAlias($this->getAlias($document->getId(), $document->getName()));
            $this->dmPersist($document);
            // Menu
            if($data['id'])
            {
                $this->_treeDuplicate($data['id'], $document->getId());
            }
            // Message
            $this->get('session')->getFlashBag()->add('notice', $this->get('translator')->trans($this->translation[1] . '.admin.create.flash.ok', array(), $this->translation[0]));
            // Redirect
            $redirect = $this->redirect($this->generateUrl($this->source . '_admin_' . $this->route));
            $redirect = isset($data['submitSave']) ? $this->redirect($this->generateUrl($this->source . '_admin_' . $this->route . '_update', array('id' => $document->getId()))) : $redirect;
            $redirect = isset($data['submitDuplicate']) ? $this->redirect($this->generateUrl($this->source . '_admin_' . $this->route . '_duplicate', array('id' => $document->getId()))) : $redirect;
            $redirect = isset($data['submitNew']) ? $this->redirect($this->generateUrl($this->source . '_admin_' . $this->route . '_create')) : $redirect;
            $redirect = isset($data['submitConfig']) ? $this->redirect($this->generateUrl($this->source . '_admin_' . $this->route . '_detail', array('id' => $document->getId()))) : $redirect;

            return $redirect;
        }

        return array(
            'form'        => $form->createView(),
            'instance'    => $instance,
            'document'    => $document,
            'modules'     => $this->getParameters('modules', 'fhm_menu'),
            'breadcrumbs' => array(
                array(
                    'link' => $this->get('router')->generate('project_home'),
                    'text' => $this->get('translator')->trans('project.home.breadcrumb', array(), 'ProjectDefaultBundle'),
                ),
                array(
                    'link' => $this->get('router')->generate('fhm_admin'),
                    'text' => $this->get('translator')->trans('fhm.admin.breadcrumb', array(), 'FhmFhmBundle'),
                ),
                array(
                    'link' => $this->get('router')->generate($this->source . '_admin_' . $this->route),
                    'text' => $this->get('translator')->trans($this->translation[1] . '.admin.index.breadcrumb', array(), $this->translation[0]),
                ),
                array(
                    'link'    => $this->get('router')->generate($this->source . '_admin_' . $this->route . '_create'),
                    'text'    => $this->get('translator')->trans($this->translation[1] . '.admin.create.breadcrumb', array(), $this->translation[0]),
                    'current' => true
                )
            )
        );
    }

    /**
     * @Route
     * (
     *      path="/detail/{id}",
     *      name="fhm_admin_menu_detail",
     *      requirements={"id"="[a-z0-9]*"}
     * )
     * @Template("::FhmMenu/Admin/detail.html.twig")
     */
    public function detailAction($id)
    {
        $tree    = $this->dmRepository()->getTree($id);
        $treemap = $this->dmRepository()->getTreeMap($id);

        return array_merge
        (
            parent::detailAction($id),
            array
            (
                "tree"    => $tree,
                "treemap" => $treemap,
                "modules" => $this->getParameters('modules', 'fhm_menu')
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
     * @Template("::FhmMenu/Admin/update.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $response = parent::updateAction($request, $id);
        $path     = (is_object($response)) ? $response : '';

        return (is_array($response)) ? array_merge($response, array("modules" => $this->getParameters('modules', 'fhm_menu'))) : $path;
    }

    /**
     * @Route
     * (
     *      path="/duplicate/{id}",
     *      name="fhm_admin_menu_duplicate",
     *      requirements={"id"="[a-z0-9]*"}
     * )
     * @Template("::FhmMenu/Admin/create.html.twig")
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
        $document = $this->dmRepository()->find($id);
        $delete   = ($document->getDelete()) ? true : false;
        $this->_treeDelete($id, $document, $delete);
        parent::deleteAction($id);

        return $this->redirect($this->getLastRoute());
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
        $this->_treeUndelete($id);

        return $this->redirect($this->getLastRoute());
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
        $this->_treeActive($id, true);

        return $this->redirect($this->getLastRoute());
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
        $this->_treeActive($id, false);

        return $this->redirect($this->getLastRoute());
    }

    /**
     * @Route
     * (
     *      path="/import",
     *      name="fhm_admin_menu_import"
     * )
     * @Template("::FhmMenu/Admin/import.html.twig")
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
     * @Template("::FhmMenu/Admin/export.html.twig")
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
        $datas          = $request->get('FhmAdd');
        $instance       = $this->instanceData();
        $document       = new Menu();
        $documentParent = $this->dmRepository()->find($datas['parent']);
        if($datas['module'] != '' && $datas['data'] != '')
        {
            if($datas['module'] == "newsGroup")
            {
                $documentMenu = $this->dmRepository('FhmNewsBundle:' . ucfirst($datas['module']))->find($datas['data']);
            }
            else
            {
                $documentMenu = $this->dmRepository('Fhm' . ucfirst($datas['module']) . 'Bundle:' . ucfirst($datas['module']))->find($datas['data']);
            }
            if(!empty($datas['name']))
            {
                $document->setName($datas['name']);
            }
            else
            {
                $document->setName($documentMenu->getName());
            }
            $document->setRoute($this->_getRoute($datas['module'], $documentMenu));
            $document->setModule(array('type' => $datas['module'], 'id' => $datas['data'], "name" => $documentMenu->getName()));
        }
        else
        {
            $document->setName($datas['name']);
            $document->setRoute($datas['route']);
        }
        $document->setParent($datas['parent']);
        $document->setDescription($datas['description']);
        $document->setIcon($datas['icon']);
        $document->setAlias($this->getAlias($document->getId(), $document->getName()));
        $documentParent->addChild($document);
        $document->addGrouping($instance->grouping->used);
        $this->dmPersist($document);
        $this->dmPersist($documentParent);

        return $this->redirect($this->getLastRoute());
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
        $datas    = $request->get('FhmUpdate');
        $instance = $this->instanceData();
        $document = $this->dmRepository()->find($datas['id']);
        if($document == "")
        {
            throw $this->createNotFoundException($this->get('translator')->trans($this->translation[1] . '.error.unknown', array(), $this->translation[0]));
        }
        if($datas['module'] != '' && $datas['data'] != '')
        {
            if($datas['module'] == "newsGroup")
            {
                $documentMenu = $this->dmRepository('FhmNewsBundle:' . ucfirst($datas['module']))->find($datas['data']);
            }
            else
            {
                $documentMenu = $this->dmRepository('Fhm' . ucfirst($datas['module']) . 'Bundle:' . ucfirst($datas['module']))->find($datas['data']);
            }
            if(!empty($datas['name']))
            {
                $document->setName($datas['name']);
            }
            else
            {
                $document->setName($documentMenu->getName());
            }
            $document->setRoute($this->_getRoute($datas['module'], $documentMenu));
            $document->setModule(array('type' => $datas['module'], 'id' => $datas['data'], "name" => $documentMenu->getName()));
        }
        else
        {
            $document->setName($datas['name']);
            $document->setRoute($datas['route']);
        }
        $document->setDescription($datas['description']);
        $document->setIcon($datas['icon']);
        $document->setAlias($this->getAlias($document->getId(), $document->getName()));
        $document->addGrouping($instance->grouping->used);
        $this->dmPersist($document);

        return $this->redirect($this->getLastRoute());
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
        $id   = $request->get('master');
        $list = json_decode($request->get('list'));
        $this->_treeSort($id, $list);

        return new Response();
    }

    /**
     * @Route
     * (
     *      path="/module",
     *      name="fhm_admin_menu_module"
     * )
     * @Template("::FhmMenu/Admin/data.html.twig")
     */
    public function moduleAction(Request $request)
    {
        $module = $request->get('module');
        if($module == "newsGroup")
        {
            $datasRepository = $this->dmRepository('FhmNewsBundle:' . ucfirst($module))->findAll();
        }
        else
        {
            $datasRepository = $this->dmRepository('Fhm' . ucfirst($module) . 'Bundle:' . ucfirst($module))->findAll();
        }

        return array(
            'instance' => $this->instanceData(),
            'datas'    => $datasRepository,
            'module'   => $module
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
        $datas    = $request->get('FhmAdd');
        $document = $this->dmRepository()->find($datas['id']);
        if($datas['module'] != '' && $datas['data'] != '')
        {
            $instance = $this->instanceData();
            if($datas['module'] == "newsGroup")
            {
                $documentMenu = $this->dmRepository('FhmNewsBundle:' . ucfirst($datas['module']))->find($datas['data']);
            }
            else
            {
                $documentMenu = $this->dmRepository('Fhm' . ucfirst($datas['module']) . 'Bundle:' . ucfirst($datas['module']))->find($datas['data']);
            }
            $document->setRoute($this->_getRoute($datas['module'], $documentMenu));
            $document->setModule(array('type' => $datas['module'], 'id' => $datas['data'], "name" => $documentMenu->getName()));
            $this->dmPersist($document);
        }

        return $this->redirect($this->getLastRoute());
    }

    /**
     * Tree delete
     *
     * @param String  $idp
     * @param         $document
     * @param Boolean $delete
     *
     * @return self
     */
    private function _treeDelete($idp, $document, $delete)
    {
        $sons = $this->dmRepository()->getSons($idp);
        // remove childs form parent
        if($delete && $document->getParent() != '0')
        {
            $documentParent = $this->dmRepository()->find($document->getParent());
            $documentParent->removeChild($document);
            $this->dmPersist($documentParent);
        }
        //delete all childs
        foreach($sons as $son)
        {
            $this->_treeDelete($son->getId(), $son, $delete);
            if($delete)
            {
                $this->dmRemove($son);
            }
            else
            {
                $son->setDelete(true);
                $this->dmPersist($son);
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
    private function _treeUndelete($idp)
    {
        $documentParent = $this->dmRepository()->find($idp);
        foreach($documentParent->getChilds() as $son)
        {
            $this->_treeUndelete($son->getId());
            $son->setDelete(false);
            $this->dmPersist($son);
        }

        return $this;
    }

    /**
     * Tree active
     *
     * @param String  $idp
     * @param Boolean $active
     *
     * @return self
     */
    private function _treeActive($idp, $active)
    {
        $documentParent = $this->dmRepository()->find($idp);
        foreach($documentParent->getChilds() as $son)
        {
            $this->_treeActive($son->getId(), $active);
            $son->setActive($active);
            $this->dmPersist($son);
        }

        return $this;
    }

    /**
     * Tree sort
     *
     * @param Array  $list
     * @param String $parent
     *
     * @return self
     */
    private function _treeSort($parent, $list)
    {
        $order          = 1;
        $documentParent = $this->dmRepository()->find($parent);
        $tabChilds      = $documentParent->getChilds();
        $documentParent->setChilds(new ArrayCollection());
        foreach($list as $obj)
        {
            $document = $this->dmRepository()->find($obj->id);
            if(isset($obj->children))
            {
                $this->_treeSort($obj->id, $obj->children);
            }
            // change order in parent
            foreach($tabChilds as $key => $son)
            {
                if($son->getId() == $obj->id)
                {
                    $documentParent->addChild($son);
                }
            }
            //add new child in parent and remove child in old parent
            if($parent == $documentParent->getId())
            {
                $documentOldParent = $this->dmRepository()->find($document->getParent());
                $documentOldParent->removeChild($document);
                $documentParent->addChild($document);
                $this->dmPersist($documentOldParent);
            }
            $document->setOrder($order);
            $document->setParent($parent);
            $this->dmPersist($document);
            $this->dmPersist($documentParent);
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
    private function _treeDuplicate($ids, $idt)
    {
        $documentSource = $this->dmRepository()->find($ids);
        $documentTarget = $this->dmRepository()->find($idt);
        if($documentSource and $documentSource->getChilds())
        {
            foreach($documentSource->getChilds() as $child)
            {
                $document = new \Fhm\MenuBundle\Document\Menu;
                $document->setName($child->getName());
                $document->setDescription($child->getDescription());
                $document->setRoute($child->getRoute());
                $document->setIcon($child->getIcon());
                $document->setUserCreate($this->getUser());
                $document->setDelete($child->getDelete());
                $document->setActive($child->getActive());
                $document->setShare($child->getShare());
                $document->setGlobal($child->getGlobal());
                $document->setOrder($child->getOrder());
                $document->addGrouping($child->getGrouping());
                $document->setModule($child->getModule());
                $document->setParent($idt);
                $document->setAlias($this->getAlias($document->getId(), $document->getName()));
                $this->dmPersist($document);
                $documentTarget->addChild($document);
                $this->_treeDuplicate($child->getId(), $document->getId());
            }
            $this->dmPersist($documentTarget);
        }

        return $this;
    }

    /**
     * @param $module
     * @param $document
     *
     * @return string
     */
    private function _getRoute($module, $document)
    {
        if($module === "media")
        {
            $route = $this->get($this->getParameters('service','fhm_media'))->setDocument($document)->getPathWeb();
        }
        else
        {
            $route = '/' . $module . '/detail/' . $document->getAlias();
        }

        return $route;
    }
}