<?php
namespace Fhm\ContactBundle\Controller;

use Fhm\FhmBundle\Controller\RefFrontController as FhmController;
use Fhm\ContactBundle\Document\Contact;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * @Route("/contact")
 */
class FrontController extends FhmController
{
    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct('Fhm', 'Contact', 'contact');
    }

    /**
     * @Route
     * (
     *      path="/",
     *      name="fhm_contact"
     * )
     * @Template("::FhmContact/Front/index.html.twig")
     */
    public function indexAction()
    {
        // ERROR - Unknown route
        if(!$this->routeExists($this->source . '_' . $this->route))
        {
            throw $this->createNotFoundException($this->get('translator')->trans('fhm.error.route', array(), 'FhmFhmBundle'));
        }
        $instance  = $this->instanceData();
        $classType = $this->form->type->search;
        $form      = $this->createForm(new $classType($instance), null);
        $form->setData($this->get('request')->get($form->getName()));
        $dataSearch     = $form->getData();
        $dataPagination = $this->get('request')->get('FhmPagination');
        // Ajax pagination request
        if(isset($dataPagination['pagination']))
        {
            $documents = $this->dmRepository()->getFrontIndex($dataSearch['search'], $dataPagination['pagination'], $this->getParameters(array('pagination', 'front', 'page'), 'fhm_fhm'), $instance->grouping->current);

            return array(
                'documents'  => $documents,
                'pagination' => $this->getPagination($dataPagination['pagination'], count($documents), $this->dmRepository()->getFrontCount($dataSearch['search'], $instance->grouping->current), 'pagination', $this->formRename($form->getName(), $dataSearch)),
                'instance'   => $instance,
            );
        }
        // Router request
        else
        {
            $documents = $this->dmRepository()->getFrontIndex($dataSearch['search'], 1, $this->getParameters(array('pagination', 'front', 'page'), 'fhm_fhm'), $instance->grouping->current);

            return array(
                'documents'   => $documents,
                'pagination'  => $this->getPagination(1, count($documents), $this->dmRepository()->getFrontCount($dataSearch['search'], $instance->grouping->current), 'pagination', $this->formRename($form->getName(), $dataSearch)),
                'form'        => $form->createView(),
                'instance'    => $instance,
                'breadcrumbs' => array(
                    array(
                        'link' => $this->get('router')->generate('project_home'),
                        'text' => $this->get('translator')->trans('project.home.breadcrumb', array(), 'ProjectDefaultBundle'),
                    ),
                    array(
                        'link' => $this->get('router')->generate($this->source . '_' . $this->route),
                        'text' => $this->get('translator')->trans($this->translation[1] . '.front.index.breadcrumb', array(), $this->translation[0]),
                        'current' => true
                    )
                )
            );
        }
    }

    /**
     * @Route
     * (
     *      path="/detail/{id}",
     *      name="fhm_contact_detail",
     *      requirements={"id"=".+"}
     * )
     * @Template("::FhmContact/Front/detail.html.twig")
     */
    public function detailAction($id)
    {
        return parent::detailAction($id);
    }

    /**
     * @Route
     * (
     *      path="/create",
     *      name="fhm_contact_create"
     * )
     * @Template("::FhmContact/Front/create.html.twig")
     */
    public function createAction(Request $request)
    {
        // For activate this route, delete next line
        throw $this->createNotFoundException($this->get('translator')->trans('fhm.error.route', array(), 'FhmFhmBundle'));

        return parent::createAction($request);
    }

    /**
     * @Route
     * (
     *      path="/update/{id}",
     *      name="fhm_contact_update",
     *      requirements={"id"="[a-z0-9]*"}
     * )
     * @Template("::FhmContact/Front/update.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        // For activate this route, delete next line
        throw $this->createNotFoundException($this->get('translator')->trans('fhm.error.route', array(), 'FhmFhmBundle'));

        return parent::updateAction($request, $id);
    }

    /**
     * @Route
     * (
     *      path="/delete/{id}",
     *      name="fhm_contact_delete",
     *      requirements={"id"="[a-z0-9]*"}
     * )
     */
    public function deleteAction($id)
    {
        // For activate this route, delete next line
        throw $this->createNotFoundException($this->get('translator')->trans('fhm.error.route', array(), 'FhmFhmBundle'));

        return parent::deleteAction($id);
    }

    /**
     * @Route
     * (
     *      path="/{id}",
     *      name="fhm_contact_lite",
     *      requirements={"id"=".+"}
     * )
     * @Template("::FhmContact/Front/detail.html.twig")
     */
    public function liteAction($id)
    {
        return $this->detailAction($id);
    }
}