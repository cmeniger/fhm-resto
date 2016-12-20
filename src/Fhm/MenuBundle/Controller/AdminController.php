<?php
namespace Fhm\MenuBundle\Controller;

use Fhm\FhmBundle\Controller\RefAdminController as FhmController;
use Fhm\FhmBundle\Form\Handler\Admin\CreateHandler;
use Fhm\FhmBundle\Form\Handler\Admin\UpdateHandler;
use Fhm\FhmBundle\Services\Tools;
use Fhm\MenuBundle\Document\Menu;
use Fhm\MenuBundle\Form\Type\Admin\CreateType;
use Fhm\MenuBundle\Form\Type\Admin\UpdateType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\Common\Collections\ArrayCollection;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * @Route("/admin/menu")
 * -----------------------------------
 * Class AdminController
 * @package Fhm\MenuBundle\Controller
 */
class AdminController extends FhmController
{
    /**
     * AdminController constructor.
     * @param string $repository
     * @param string $source
     * @param string $domain
     * @param string $translation
     * @param $document
     * @param string $route
     */
    public function __construct(
        $repository = "FhmMenuBundle:Menu",
        $source = "fhm",
        $domain = "FhmMenuBundle",
        $translation = "menu",
        $document = Menu::class,
        $route = 'menu'
    ) {
        self::$repository = $repository;
        self::$source = $source;
        self::$domain = $domain;
        self::$translation = $translation;
        self::$document = new $document();
        self::$class = get_class(self::$document);
        self::$route = $route;
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
        $document = self::$document;
        self::$form = new \stdClass();
        self::$form->type = CreateType::class;
        self::$form->handler = CreateHandler::class;
        $form = $this->createForm(
            self::$form->type,
            $document,
            array(
                'data_class' => self::$class,
                'translation_domain' => self::$domain,
                'translation_route' => self::$translation,
            )
        );
        $handler = new self::$form->handler($form, $request);
        $process = $handler->process();
        if ($process) {
            $data = $request->get($form->getName());
            // Persist
            $document->setUserCreate($this->getUser());
            $document->setAlias(
                $this->get('fhm_tools')->getAlias($document->getId(), $document->getName(), self::$repository)
            );
            $this->get('fhm_tools')->dmPersist($document);
            // Menu
            if ($data['id']) {
                $this->__treeDuplicate($data['id'], $document->getId());
            }
            // Message
            $this->get('session')->getFlashBag()->add(
                'notice',
                $this->get('translator')->trans(
                    self::$translation.'.admin.create.flash.ok',
                    array(),
                    self::$domain
                )
            );
            // Redirect
            $redirect = $this->redirect(
                $this->generateUrl(
                    self::$source.'_admin_'.self::$route
                )
            );
            $redirect = isset($data['submitSave']) ? $this->redirect(
                $this->generateUrl(
                    self::$source.'_admin_'.self::$route.'_update',
                    array('id' => $document->getId())
                )
            ) : $redirect;
            $redirect = isset($data['submitDuplicate']) ? $this->redirect(
                $this->generateUrl(
                    self::$source.'_admin_'.self::$route.'_duplicate',
                    array('id' => $document->getId())
                )
            ) : $redirect;
            $redirect = isset($data['submitNew']) ? $this->redirect(
                $this->generateUrl(self::$source.'_admin_'.self::$route.'_create')
            ) : $redirect;
            $redirect = isset($data['submitConfig']) ? $this->redirect(
                $this->generateUrl(
                    self::$source.'_admin_'.self::$route.'_detail',
                    array('id' => $document->getId())
                )
            ) : $redirect;

            return $redirect;
        }

        return array(
            'form' => $form->createView(),
            'document' => $document,
            'modules' => $this->getParameters('modules', 'fhm_menu'),
            'breadcrumbs' => array(
                array(
                    'link' => $this->get('router')->generate('project_home'),
                    'text' => $this->get('translator')->trans(
                        'project.home.breadcrumb',
                        array(),
                        'ProjectDefaultBundle'
                    ),
                ),
                array(
                    'link' => $this->get('router')->generate('fhm_admin'),
                    'text' => $this->get('translator')->trans('fhm.admin.breadcrumb', array(), 'FhmFhmBundle'),
                ),
                array(
                    'link' => $this->get('router')->generate(self::$source.'_admin_'.self::$route),
                    'text' => $this->get('translator')->trans(
                        self::$translation.'.admin.index.breadcrumb',
                        array(),
                        self::$domain
                    ),
                ),
                array(
                    'link' => $this->get('router')->generate(self::$source.'_admin_'.self::$route.'_create'),
                    'text' => $this->get('translator')->trans(
                        self::$translation.'.admin.create.breadcrumb',
                        array(),
                        self::$domain
                    ),
                    'current' => true,
                ),
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
     * @Template("::FhmMenu/Admin/detail.html.twig")
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
     * @Template("::FhmMenu/Admin/update.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        self::$form = new \stdClass();
        self::$form->type = UpdateType::class;
        self::$form->handler = UpdateHandler::class;
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
        $document = $this->get('fhm_tools')->dmRepository(self::$repository)->find($id);
        $delete = ($document->getDelete()) ? true : false;
        $this->__treeDelete($id, $document, $delete);
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
        $datas = $request->get('FhmAdd');
        $document = new Menu();
        $documentParent = $this->get('fhm_tools')->dmRepository(self::$repository)->find($datas['parent']);
        if ($datas['module'] != '' && $datas['data'] != '') {
            if ($datas['module'] == "newsGroup") {
                $documentMenu = $this->get('fhm_tools')->dmRepository('FhmNewsBundle:'.ucfirst($datas['module']))->find(
                    $datas['data']
                );
            } else {
                $documentMenu = $this->get('fhm_tools')->dmRepository(
                    'Fhm'.ucfirst($datas['module']).'Bundle:'.ucfirst($datas['module'])
                )->find($datas['data']);
            }
            if (!empty($datas['name'])) {
                $document->setName($datas['name']);
            } else {
                $document->setName($documentMenu->getName());
            }
            $document->setRoute($this->__getRoute($datas['module'], $documentMenu));
            $document->setModule(
                array(
                    'type' => $datas['module'],
                    'id' => $datas['data'],
                    "name" => $documentMenu->getName(),
                )
            );
        } else {
            $document->setName($datas['name']);
            $document->setRoute($datas['route']);
        }
        $document->setParent($datas['parent']);
        $document->setDescription($datas['description']);
        $document->setIcon($datas['icon']);
        $document->setAlias($this->getAlias($document->getId(), $document->getName()));
        $documentParent->addChild($document);
        $this->get('fhm_tools')->dmPersist($document);
        $this->get('fhm_tools')->dmPersist($documentParent);

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
        $document = $this->get('fhm_tools')->dmRepository(self::$repository)->find($datas['id']);
        if ($document == "") {
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
                $documentMenu = $this->get('fhm_tools')->dmRepository('FhmNewsBundle:'.ucfirst($datas['module']))->find(
                    $datas['data']
                );
            } else {
                $documentMenu = $this->get('fhm_tools')->dmRepository(
                    'Fhm'.ucfirst($datas['module']).'Bundle:'.ucfirst($datas['module'])
                )->find($datas['data']);
            }
            if (!empty($datas['name'])) {
                $document->setName($datas['name']);
            } else {
                $document->setName($documentMenu->getName());
            }
            $document->setRoute($this->__getRoute($datas['module'], $documentMenu));
            $document->setModule(
                array('type' => $datas['module'], 'id' => $datas['data'], "name" => $documentMenu->getName())
            );
        } else {
            $document->setName($datas['name']);
            $document->setRoute($datas['route']);
        }
        $document->setDescription($datas['description']);
        $document->setIcon($datas['icon']);
        $document->setAlias($this->get('fhm_tools')->getAlias($document->getId(), $document->getName()));
        $this->get('fhm_tools')->dmPersist($document);

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
     * @Template("::FhmMenu/Admin/data.html.twig")
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
        $document = $this->get('fhm_tools')->dmRepository(self::$repository)->find($datas['id']);
        if ($datas['module'] != '' && $datas['data'] != '') {
            if ($datas['module'] == "newsGroup") {
                $documentMenu = $this->get('fhm_tools')->dmRepository('FhmNewsBundle:'.ucfirst($datas['module']))->find(
                    $datas['data']
                );
            } else {
                $documentMenu = $this->get('fhm_tools')->dmRepository(
                    'Fhm'.ucfirst($datas['module']).'Bundle:'.ucfirst($datas['module'])
                )->find($datas['data']);
            }
            $document->setRoute($this->__getRoute($datas['module'], $documentMenu));
            $document->setModule(
                array('type' => $datas['module'], 'id' => $datas['data'], "name" => $documentMenu->getName())
            );
            $this->get('fhm_tools')->dmPersist($document);
        }

        return $this->redirect($this->get('fhm_tools')->getLastRoute($request));
    }

    /**
     * Tree delete
     *
     * @param String $idp
     * @param         $document
     * @param Boolean $delete
     *
     * @return self
     */
    private function __treeDelete($idp, $document, $delete)
    {
        $sons = $this->get('fhm_tools')->dmRepository(self::$repository)->getSons($idp);
        // remove childs form parent
        if ($delete && $document->getParent() != '0') {
            $documentParent = $this->get('fhm_tools')->dmRepository(self::$repository)->find($document->getParent());
            $documentParent->removeChild($document);
            $this->get('fhm_tools')->dmPersist($documentParent);
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
        $documentParent = $this->get('fhm_tools')->dmRepository(self::$repository)->find($idp);
        foreach ($documentParent->getChilds() as $son) {
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
        $documentParent = $this->get('fhm_tools')->dmRepository(self::$repository)->find($idp);
        foreach ($documentParent->getChilds() as $son) {
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
        $documentParent = $this->get('fhm_tools')->dmRepository(self::$repository)->find($parent);
        $tabChilds = $documentParent->getChilds();
        $documentParent->setChilds(new ArrayCollection());
        foreach ($list as $obj) {
            $document = $this->get('fhm_tools')->dmRepository(self::$repository)->find($obj->id);
            if (isset($obj->children)) {
                $this->__treeSort($obj->id, $obj->children);
            }
            // change order in parent
            foreach ($tabChilds as $key => $son) {
                if ($son->getId() == $obj->id) {
                    $documentParent->addChild($son);
                }
            }
            //add new child in parent and remove child in old parent
            if ($parent == $documentParent->getId()) {
                $documentOldParent = $this->get('fhm_tools')->dmRepository(self::$repository)->find(
                    $document->getParent()
                );
                $documentOldParent->removeChild($document);
                $documentParent->addChild($document);
                $this->get('fhm_tools')->dmPersist($documentOldParent);
            }
            $document->setOrder($order);
            $document->setParent($parent);
            $this->get('fhm_tools')->dmPersist($document);
            $this->get('fhm_tools')->dmPersist($documentParent);
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
        $documentSource = $this->get('fhm_tools')->dmRepository(self::$repository)->find($ids);
        $documentTarget = $this->get('fhm_tools')->dmRepository(self::$repository)->find($idt);
        if ($documentSource and $documentSource->getChilds()) {
            foreach ($documentSource->getChilds() as $child) {
                $document = new Menu();
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
                $document->setModule($child->getModule());
                $document->setParent($idt);
                $document->setAlias($this->getAlias($document->getId(), $document->getName()));
                $this->get('fhm_tools')->dmRepository(self::$repository)->dmPersist($document);
                $documentTarget->addChild($document);
                $this->__treeDuplicate($child->getId(), $document->getId());
            }
            $this->get('fhm_tools')->dmPersist($documentTarget);
        }

        return $this;
    }

    /**
     * @param $module
     * @param $document
     *
     * @return string
     */
    private function __getRoute($module, $document)
    {
        if ($module === "media") {
            $route = $this->get($this->getParameters('service', 'fhm_media'))->setDocument(
                $document
            )->getPathWeb();
        } else {
            $route = '/'.$module.'/detail/'.$document->getAlias();
        }

        return $route;
    }
}