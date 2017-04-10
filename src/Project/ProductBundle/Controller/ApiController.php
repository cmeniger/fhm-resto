<?php
namespace Project\ProductBundle\Controller;

use Fhm\FhmBundle\Controller\RefApiController as FhmController;
use Fhm\ProductBundle\Document\Product;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * @Route("/api/product")
 */
class ApiController extends FhmController
{
    /**
     * Constructor
     */
    public function __construct()
    {
        self::$repository = "ProjectProductBundle:Product";
        self::$domain = "ProjectProductBundle";
        self::$translation = "product";
        self::$route = "product";
        self::$source = "project";
    }

    /**
     * @Route
     * (
     *      path="/",
     *      name="fhm_api_product"
     * )
     * @Template("::FhmProduct/Api/index.html.twig")
     */
    public function indexAction()
    {
        return parent::indexAction();
    }

    /**
     * @Route
     * (
     *      path="/data/admin",
     *      name="fhm_api_product_data_admin"
     * )
     * @Template("::FhmProduct/Template/data.admin.html.twig")
     */
    public function dataAdminAction(Request $request)
    {
        $data       = $request->get('product');
        $ingredientMains   = $this->get('fhm_tools')->dmRepository('ProjectProductBundle:ProductIngredient')->setParent(true)->getAllEnable();
        $ingredientSons    = (isset($data['ingredient'])) ? $this->get('fhm_tools')->dmRepository('ProjectProductBundle:ProductIngredient')->getSonsEnable($data['ingredient']) : '';
        $ingredient        = (isset($data['ingredient'])) ? $this->get('fhm_tools')->dmRepository('ProjectProductBundle:ProductIngredient')->find($data['ingredient']) : '';
        $pagination = $request->get('FhmPagination');
        $search     = (isset($data['search'])) ? $data['search'] : '';
        $documents  = $this->get('fhm_tools')->dmRepository(self::$repository)->setIngredient((isset($data['ingredient'])) ? $data['ingredient'] : '')->getAdminIndex($search, 1, $this->getParameter(array('pagination', 'page'), 'fhm_fhm'), 0, $this->getUser()->hasRole('ROLE_SUPER_ADMIN'));










        //        // Ajax pagination request
        //        if(isset($pagination['pagination']))
        //        {
        //            $documents = $this->dmRepository()->getAdminIndex($search, $pagination['pagination'], $this->getParameter(array('pagination', 'page'), 'fhm_fhm'), $instance->grouping->used, $instance->user->super);
        //
        //            return array(
        //                'documents'  => $documents,
        //                'pagination' => $this->getPagination($pagination['pagination'], count($documents), $this->dmRepository()->getAdminCount($search, $instance->grouping->used, $instance->user->super), 'pagination', $this->formRename($form->getName(), $dataSearch)),
        //                'instance'   => $instance,
        //            );
        //        }
        //        // Router request
        //        else
        //        {
        //            $documents = $this->dmRepository()->getAdminIndex($dataSearch['search'], 1, $this->getParameter(array('pagination', 'page'), 'fhm_fhm'), $instance->grouping->used, $instance->user->super);
        //
        //            return array(
        //                'documents'   => $documents,
        //                'pagination'  => $this->getPagination(1, count($documents), $this->dmRepository()->getAdminCount($dataSearch['search'], $instance->grouping->used, $instance->user->super), 'pagination', $this->formRename($form->getName(), $dataSearch)),
        //                'form'        => $form->createView(),
        //                'instance'    => $instance,
        //                'breadcrumbs' => array(
        //                    array(
        //                        'link' => $this->get('router')->generate('project_home'),
        //                        'text' => $this->get('translator')->trans('project.home.breadcrumb', array(), 'ProjectDefaultBundle'),
        //                    ),
        //                    array(
        //                        'link' => $this->get('router')->generate('fhm_admin'),
        //                        'text' => $this->get('translator')->trans('fhm.admin.breadcrumb', array(), 'FhmFhmBundle'),
        //                    ),
        //                    array(
        //                        'link'    => $this->get('router')->generate('fhm_admin_' . $this->route),
        //                        'text'    => $this->get('translator')->trans($this->translation[1] . '.admin.index.breadcrumb', array(), $this->translation[0]),
        //                        'current' => true
        //                    )
        //                )
        //            );
        //        }
        //$documents = $this->dmRepository()->setIngredient((isset($data['ingredient'])) ? $data['ingredient'] : '')->getAdminIndex();
        return array(
            'ingredient'       => $ingredient,
            'ingredientMains'  => $ingredientMains,
            'ingredientSons'   => $ingredientSons,
            'documents' => $documents,
        );
    }
}