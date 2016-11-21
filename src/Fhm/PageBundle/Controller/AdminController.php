<?php
namespace Fhm\PageBundle\Controller;

use Fhm\FhmBundle\Controller\RefAdminController as FhmController;
use Fhm\PageBundle\Document\Page;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Validator\Constraints\DateTime;

/**
 * @Route("/admin/page")
 */
class AdminController extends FhmController
{
    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct('Fhm', 'Page', 'page');
    }

    /**
     * @Route
     * (
     *      path="/",
     *      name="fhm_admin_page"
     * )
     * @Template("::FhmPage/Admin/index.html.twig")
     */
    public function indexAction()
    {
        return parent::indexAction();
    }

    /**
     * @Route
     * (
     *      path="/create",
     *      name="fhm_admin_page_create"
     * )
     * @Template("::FhmPage/Admin/create.html.twig")
     */
    public function createAction(Request $request)
    {
        return parent::createAction($request);
    }

    /**
     * @Route
     * (
     *      path="/add",
     *      name="fhm_admin_page_add"
     * )
     */
    public function addAction(Request $request)
    {
        $datas = $request->get('FhmAdd');
        if ($datas['module'] != '' && $datas['data'] != '') {
            $document = $documents = $this->dmRepository()->find($datas['parent']);
            $document->addModule(array($datas['data'] => array('type' => $datas['module'], 'id' => $datas['data'], "create" => new \DateTime("now"), "update" => new \DateTime("now"),  "delete" => false, "active" => true)));
            $documentUse=$this->dmRepository('Fhm' . ucfirst($datas['module']) . 'Bundle:' . ucfirst($datas['module']))->find($datas['data']);
            $currentClass=explode('\\',get_class($document));
            $documentUse->addUse(array($document->getId() => array('id' => $document->getId(),'type' => lcfirst($currentClass[3]),'attr'=>'page' )));
            $this->dmPersist($document);
            $this->dmPersist($documentUse);
        }
        return $this->redirect($this->getLastRoute());
    }

    /**
     * @Route
     * (
     *      path="/module",
     *      name="fhm_admin_page_module"
     * )
     * @Template("::FhmPage/Admin/data.html.twig")
     */
    public function moduleAction(Request $request)
    {
        $module = $request->get('module');

        $datasRepository = $this->dmRepository('Fhm' . ucfirst($module) . 'Bundle:' . ucfirst($module))->findAll();
        if ($module == 'news') {
            $datasRepository = $this->dmRepository('Fhm' . ucfirst($module) . 'Bundle:' . ucfirst($module))->findAllParent();
        }

        return array(
            'instance' => $this->instanceData(),
            'datas' => $datasRepository,
            'module' => $module
        );
    }

    /**
     * @Route
     * (
     *      path="/sort",
     *      name="fhm_admin_page_sort"
     * )
     */
    public function sortAction(Request $request)
    {
        $id = $request->get('master');
        $list = json_decode($request->get('list'));
        $this->_pageSort($id, $list);

        return new Response();
    }

    /**
     * @Route
     * (
     *      path="/detail/{id}",
     *      name="fhm_admin_page_detail",
     *      requirements={"id"="[a-z0-9]*"}
     * )
     * @Template("::FhmPage/Admin/detail.html.twig")
     */
    public function detailAction($id)
    {
        $childs = array();

        $document = $this->dmRepository()->find($id);
        $sons = $document->getModule();

        // ERROR - son
        if ($document->getParent() != '0') {
            throw new HttpException(403, $this->get('translator')->trans($this->translation[1] . '.error.forbidden', array(), $this->translation[0]));
        }

        foreach ($sons as &$son) {
            foreach ($son as &$sonV) {
                $childs[$sonV['id']]['option'] = $sonV;

                $childs[$sonV['id']]['object'] = $this->dmRepository('Fhm' . ucfirst($sonV['type']) . 'Bundle:' . ucfirst($sonV['type']))->find($sonV['id']);
            }
        }
        return array_merge
        (
            parent::detailAction($id),
            array
            (
                "sons" => $childs,
                "modules" => $this->getParameters('modules', 'fhm_page')
            )
        );
    }

    /**
     * @Route
     * (
     *      path="/update/{id}",
     *      name="fhm_admin_page_update",
     *      requirements={"id"="[a-z0-9]*"}
     * )
     * @Template("::FhmPage/Admin/update.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        return parent::updateAction($request, $id);
    }

    /**
     * @Route
     * (
     *      path="/duplicate/{id}",
     *      name="fhm_admin_page_duplicate",
     *      requirements={"id"="[a-z0-9]*"}
     * )
     * @Template("::FhmPage/Admin/create.html.twig")
     */
    public function duplicateAction(Request $request, $id)
    {
        return parent::duplicateAction($request, $id);
    }

    /**
     * @Route
     * (
     *      path="/delete/{id}",
     *      name="fhm_admin_page_delete",
     *      requirements={"id"="[a-z0-9]*"}
     * )
     */
    public function deleteAction($id)
    {
        parent::deleteAction($id);

        return $this->redirect($this->getLastRoute());
    }

    /**
     * @Route
     * (
     *      path="/deletechild/{parent}/{id}",
     *      name="fhm_admin_page_deletechild",
     *      requirements={"id"="[a-z0-9]*"}
     * )
     */
    public function deletechildAction($parent, $id)
    {
        // ERROR - Unknown route
        if (!$this->routeExists('fhm_admin_' . $this->route)) {
            throw $this->createNotFoundException($this->get('translator')->trans('fhm.error.route', array(), 'FhmFhmBundle'));
        }
        $document = $this->dmRepository()->find($parent);
        $modules = $document->getModule();

        if (!empty($modules)) {
            foreach ($modules as $key => &$module) {
                if ((array_key_exists($id, $module))) {

                    // ERROR - Forbidden
                    if ($module[$id]['delete'] && !$this->getUser()->isSuperAdmin()) {
                        throw new HttpException(403, $this->get('translator')->trans($this->translation[1] . '.error.forbidden', array(), $this->translation[0]));
                    }
                    // Delete
                    if ($module[$id]['delete']) {
                        unset($modules[$key]);
                        //delete this object in the use attribut of the used document
                        $documentUse=$this->dmRepository('Fhm' . ucfirst($module[$id]['type']) . 'Bundle:' . ucfirst($module[$id]['type']))->find($id);
                        $documentUse->removeUse($parent,'page');
                        $this->dmPersist($documentUse);

                    } else {
                        $module[$id]['delete'] = true;
                        $module[$id]['update'] = new \DateTime("now");
                    }
                    $document->setModule($modules);
                    $this->dmPersist($document);

                    // Message
                    $this->get('session')->getFlashBag()->add('notice', $this->get('translator')->trans($this->translation[1] . '.admin.delete.flash.ok', array(), $this->translation[0]));
                }

            }
        }


        return $this->redirect($this->getLastRoute());
    }

    /**
     * @Route
     * (
     *      path="/activatechild/{parent}/{id}",
     *      name="fhm_admin_page_activatechild",
     *      requirements={"id"="[a-z0-9]*"}
     * )
     */
    public function activatechildAction($parent, $id)
    {
        // ERROR - Unknown route
        if (!$this->routeExists('fhm_admin_' . $this->route)) {
            throw $this->createNotFoundException($this->get('translator')->trans('fhm.error.route', array(), 'FhmFhmBundle'));
        }
        $document = $this->dmRepository()->find($parent);
        $modules = $document->getModule();

        if (!empty($modules)) {
            foreach ($modules as $key => &$module) {
                if ((array_key_exists($id, $module))) {

                    // ERROR - Forbidden
                    if ($module[$id]['active'] && !$this->getUser()->isSuperAdmin()) {
                        throw new HttpException(403, $this->get('translator')->trans($this->translation[1] . '.error.forbidden', array(), $this->translation[0]));
                    }
                    // Delete
                    if ($module[$id]['active']) {
                        $module[$id]['active'] = false;
                        $module[$id]['update'] = new \DateTime("now");
                    } else {
                        $module[$id]['active'] = true;
                        $module[$id]['update'] = new \DateTime("now");
                    }
                    $document->setModule($modules);
                    $this->dmPersist($document);

                    // Message
                    $this->get('session')->getFlashBag()->add('notice', $this->get('translator')->trans($this->translation[1] . '.admin.delete.flash.ok', array(), $this->translation[0]));
                }

            }
        }


        return $this->redirect($this->getLastRoute());
    }

    /**
     * @Route
     * (
     *      path="/undeletechild/{parent}/{id}",
     *      name="fhm_admin_page_undeletechild",
     *      requirements={"id"="[a-z0-9]*"}
     * )
     */
    public function undeletechildAction($parent, $id)
    {
        // ERROR - Unknown route
        if (!$this->routeExists('fhm_admin_' . $this->route)) {
            throw $this->createNotFoundException($this->get('translator')->trans('fhm.error.route', array(), 'FhmFhmBundle'));
        }
        $document = $this->dmRepository()->find($parent);
        $modules = $document->getModule();

        if (!empty($modules)) {
            foreach ($modules as $key => &$module) {
                if ((array_key_exists($id, $module))) {

                    // ERROR - Forbidden
                    if ($module[$id]['delete'] && !$this->getUser()->isSuperAdmin()) {
                        throw new HttpException(403, $this->get('translator')->trans($this->translation[1] . '.error.forbidden', array(), $this->translation[0]));
                    }
                    // Delete
                    if ($module[$id]['delete']) {
                        $module[$id]['delete'] = false;
                        $module[$id]['update'] = new \DateTime("now");
                    }
                    $document->setModule($modules);
                    $this->dmPersist($document);

                    // Message
                    $this->get('session')->getFlashBag()->add('notice', $this->get('translator')->trans($this->translation[1] . '.admin.delete.flash.ok', array(), $this->translation[0]));
                }

            }
        }
        return $this->redirect($this->getLastRoute());
    }

    /**
     * @Route
     * (
     *      path="/undelete/{id}",
     *      name="fhm_admin_page_undelete",
     *      requirements={"id"="[a-z0-9]*"}
     * )
     */
    public
    function undeleteAction($id)
    {
        parent::undeleteAction($id);

        return $this->redirect($this->getLastRoute());
    }

    /**
     * @Route
     * (
     *      path="/activate/{id}",
     *      name="fhm_admin_page_activate",
     *      requirements={"id"="[a-z0-9]*"}
     * )
     */
    public
    function activateAction($id)
    {
        parent::activateAction($id);

        return $this->redirect($this->getLastRoute());
    }

    /**
     * @Route
     * (
     *      path="/deactivate/{id}",
     *      name="fhm_admin_page_deactivate",
     *      requirements={"id"="[a-z0-9]*"}
     * )
     */
    public
    function deactivateAction($id)
    {
        parent::deactivateAction($id);

        return $this->redirect($this->getLastRoute());
    }

    /**
     * @Route
     * (
     *      path="/import",
     *      name="fhm_admin_page_import"
     * )
     * @Template("::FhmPage/Admin/import.html.twig")
     */
    public
    function importAction(Request $request)
    {
        return parent::importAction($request);
    }

    /**
     * @Route
     * (
     *      path="/export",
     *      name="fhm_admin_page_export"
     * )
     * @Template("::FhmPage/Admin/export.html.twig")
     */
    public
    function exportAction(Request $request)
    {
        return parent::exportAction($request);
    }

    /**
     * Tree sort
     *
     * @param Array $list
     * @param String $parent
     *
     * @return self
     */
    private
    function _pageSort($parent, $list)
    {
        $document = $this->dmRepository()->find($parent);
        $modules=$document->getModule();
        $modulesOrder=array();

        foreach ($list as $obj) {
            foreach($modules  as  $module)
            {
               foreach($module as $key => $son)
               {
                   if($key==$obj->id) $modulesOrder[]=$module;
                }
            }
        }
        $document->setModule($modulesOrder);
        $this->dmPersist($document);
        return $this;
    }
}