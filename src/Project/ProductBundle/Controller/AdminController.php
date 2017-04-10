<?php
namespace Project\ProductBundle\Controller;

use Fhm\FhmBundle\Controller\RefAdminController as FhmController;
use Fhm\FhmBundle\Form\Handler\Front\CreateHandler;
use Fhm\FhmBundle\Form\Handler\Front\UpdateHandler;
use Project\ProductBundle\Form\Type\Admin\CreateType;
use Project\ProductBundle\Form\Type\Admin\UpdateType;
use Project\ProductBundle\Document\Product;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * @Route("/admin/product")
 */
class AdminController extends FhmController
{
    /**
     * Constructor
     */
    public function __construct()
    {
        self::$repository  = "FhmProductBundle:Product";
        self::$domain      = "FhmProductBundle";
        self::$translation = "product";
        self::$route       = "product";
        self::$source      = "fhm";

        self::$form  = new \stdClass();

        self::$form->createType    = CreateType::class;
        self::$form->updateType    = UpdateType::class;
        self::$form->createHandler = CreateHandler::class;
        self::$form->updateHandler = UpdateHandler::class;
    }

    /**
     * @Route
     * (
     *      path="/",
     *      name="fhm_admin_product"
     * )
     * @Template("::FhmProduct/Admin/index.html.twig")
     */
    public function indexAction()
    {
        return parent::indexAction();
    }

    /**
     * @Route
     * (
     *      path="/create",
     *      name="fhm_admin_product_create"
     * )
     * @Template("::FhmProduct/Admin/create.html.twig")
     */
    public function createAction(Request $request)
    {
        self::$class = $this->get('fhm.object.manager')->getCurrentModelName(self::$repository);
        $object = new self::$class;
        $form = $this->createForm(
            self::$form->createType,
            $object,
            array(
                'user_admin' => $this->getUser()->hasRole('ROLE_ADMIN'),
                'data_class' => self::$class,
                'object_manager' => $this->get('fhm.object.manager'),
            )
        );
        $handler = new self::$form->createHandler($form, $request);
        $process = $handler->process();
        if ($process) {
            $data = $request->get($form->getName());
            if(isset($data['ingredient']) && $data['ingredient']) {
                $tag = $this->get('fhm_tools')->dmRepository('FhmProductBundle:ProductIngredient')->getByName($data['ingredient']);
                if ($tag == "") {
                    $tag = new \Project\ProductBundle\Document\ProductIngredient();
                    $tag->setName($data['ingredient']);
                    $tag->setActive(true);
                }
                if (isset($data['parent']) && $data['parent']) {
                    $tag->setParent($this->get('fhm_tools')->dmRepository('FhmProductBundle:ProductIngredient')->find($data['parent']));
                }
                $this->get('fhm_tools')->dmPersist($tag);
            }

            $object->setUserCreate($this->getUser());
            $object->setAlias($this->get('fhm_tools')->getAlias($object->getId(), $object->getName(), self::$repository));
            $this->get('fhm_tools')->dmPersist($object);
            $this->get('session')->getFlashBag()->add(
                'notice',
                $this->trans(self::$translation.'.admin.create.flash.ok')
            );
            $redirect = $this->redirect($this->generateUrl(self::$source . '_admin_' . self::$route));
            $redirect = isset($data['submitSave']) ? $this->redirect($this->generateUrl(self::$source . '_admin_' . self::$route . '_update', array('id' => $object->getId()))) : $redirect;
            $redirect = isset($data['submitDuplicate']) ? $this->redirect($this->generateUrl(self::$source . '_admin_' . self::$route . '_duplicate', array('id' => $object->getId()))) : $redirect;
            $redirect = isset($data['submitNew']) ? $this->redirect($this->generateUrl(self::$source . '_admin_' . self::$route . '_create')) : $redirect;
            $redirect = isset($data['submitConfig']) ? $this->redirect($this->generateUrl(self::$source . '_admin_' . self::$route . '_detail', array('id' => $object->getId()))) : $redirect;

            return $redirect;
        }

        return array(
            'form' => $form->createView(),
            'breadcrumbs' => $this->get('fhm_tools')->generateBreadcrumbs(
                array(
                    'domain' => self::$domain,
                    '_route' => $this->get('request_stack')->getCurrentRequest()->get('_route'),
                )
            ),
        );
    }

    /**
     * @Route
     * (
     *      path="/duplicate/{id}",
     *      name="fhm_admin_product_duplicate",
     *      requirements={"id"="[a-z0-9]*"}
     * )
     * @Template("::FhmProduct/Admin/create.html.twig")
     */
    public function duplicateAction(Request $request, $id)
    {
        return parent::duplicateAction($request, $id);
    }

    /**
     * @Route
     * (
     *      path="/update/{id}",
     *      name="fhm_admin_product_update",
     *      requirements={"id"="[a-z0-9]*"}
     * )
     * @Template("::FhmProduct/Admin/update.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN', null, $this->trans(self::$translation.'.error.forbidden'));
        $document     = $this->get('fhm_tools')->dmRepository(self::$repository)->find($id);
        self::$class = $this->get('fhm.object.manager')->getCurrentModelName(self::$repository);
        $form = $this->createForm(
            self::$form->updateType,
            $document,
            array(
                'user_admin' => $this->getUser()->hasRole('ROLE_SUPER_ADMIN'),
                'data_class' => self::$class,
                'object_manager' => $this->get('fhm.object.manager'),
            )
        );
        $handler = new self::$form->updateHandler($form, $request);
        $process = $handler->process();
        if($process)
        {
            $data = $request->get($form->getName());
            if(isset($data['ingredient']) && $data['ingredient'])
            {
                $tag = $this->get('fhm_tools')->dmRepository('FhmProductBundle:ProductIngredient')->getByName($data['ingredient']);
                if($tag == "")
                {
                    $tag = new \Project\ProductBundle\Document\ProductIngredient();
                    $tag->setName($data['ingredient']);
                    $tag->setActive(true);
                }
                if(isset($data['parent']) && $data['parent'])
                {
                    $tag->setParent($this->get('fhm_tools')->dmRepository('FhmProductBundle:ProductIngredient')->find($data['parent']));
                }
                $this->get('fhm_tools')->dmPersist($tag);
                $document->addIngredient($tag);
            }
            // Persist
            $document->setUserUpdate($this->getUser());
            $document->setAlias($this->get('fhm_tools')->getAlias($document->getId(), $document->getName(), self::$repository));
            $this->get('fhm_tools')->dmPersist($document);
            // Message
            $this->get('session')->getFlashBag()->add('notice', $this->get('translator')->trans(self::$translation . '.admin.update.flash.ok', array(), self::$translation));
            // Redirect
            return $this->redirectUrl($data, $document);
        }

        return array(
            'object'    => $document,
            'form'        => $form->createView(),
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
                    'link' => $this->get('router')->generate(self::$source . '_admin_' . self::$route),
                    'text' => $this->get('translator')->trans(self::$translation . '.admin.index.breadcrumb', array(), self::$translation),
                ),
                array(
                    'link' => $this->get('router')->generate(self::$source . '_admin_' . self::$route . '_detail', array('id' => $id)),
                    'text' => $this->get('translator')->trans(self::$translation . '.admin.detail.breadcrumb', array('%name%' => $document->getName()), self::$translation),
                ),
                array(
                    'link'    => $this->get('router')->generate(self::$source . '_admin_' . self::$route . '_update', array('id' => $id)),
                    'text'    => $this->get('translator')->trans(self::$translation . '.admin.update.breadcrumb', array(), self::$translation),
                    'current' => true
                )
            )
        );
    }

    /**
     * @Route
     * (
     *      path="/detail/{id}",
     *      name="fhm_admin_product_detail",
     *      requirements={"id"="[a-z0-9]*"}
     * )
     * @Template("::FhmProduct/Admin/detail.html.twig")
     */
    public function detailAction($id)
    {
        return parent::detailAction($id);
    }

    /**
     * @Route
     * (
     *      path="/delete/{id}",
     *      name="fhm_admin_product_delete",
     *      requirements={"id"="[a-z0-9]*"}
     * )
     */
    public function deleteAction($id)
    {
        return parent::deleteAction($id);
    }

    /**
     * @Route
     * (
     *      path="/undelete/{id}",
     *      name="fhm_admin_product_undelete",
     *      requirements={"id"="[a-z0-9]*"}
     * )
     */
    public function undeleteAction($id)
    {
        return parent::undeleteAction($id);
    }

    /**
     * @Route
     * (
     *      path="/activate/{id}",
     *      name="fhm_admin_product_activate",
     *      requirements={"id"="[a-z0-9]*"}
     * )
     */
    public function activateAction($id)
    {
        return parent::activateAction($id);
    }

    /**
     * @Route
     * (
     *      path="/deactivate/{id}",
     *      name="fhm_admin_product_deactivate",
     *      requirements={"id"="[a-z0-9]*"}
     * )
     */
    public function deactivateAction($id)
    {
        return parent::deactivateAction($id);
    }

    /**
     * @Route
     * (
     *      path="/import",
     *      name="fhm_admin_product_import"
     * )
     * @Template("::FhmProduct/Admin/import.html.twig")
     */
    public function importAction(Request $request)
    {
        return parent::importAction($request);
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class' => '',
                'translation_domain' => 'FhmProductBundle',
                'cascade_validation' => true,
                'translation_route' => 'product',
                'user_admin' => '',
                'object_manager' => ''
            )
        );
    }
    /**
     * @Route
     * (
     *      path="/export",
     *      name="fhm_admin_product_export"
     * )
     * @Template("::FhmProduct/Admin/export.html.twig")
     */
    public function exportAction(Request $request)
    {
        return parent::exportAction($request);
    }

    /**
     * @Route
     * (
     *      path="/grouping",
     *      name="fhm_admin_product_grouping"
     * )
     */
    public function groupingAction(Request $request)
    {
        return parent::groupingAction($request);
    }
}