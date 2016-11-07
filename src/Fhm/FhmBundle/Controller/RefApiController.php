<?php
namespace Fhm\FhmBundle\Controller;

use Fhm\FhmBundle\Document\Fhm;
use Fhm\FhmBundle\Controller\FhmController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class RefApiController extends FhmController
{
    /**
     * @param string $src
     * @param string $bundle
     * @param string $route
     * @param string $document
     */
    public function __construct($src = 'Fhm', $bundle = 'Fhm', $route = '', $document = '')
    {
        parent::__construct();
        $this->source      = strtolower($src);
        $this->repository  = $src . $bundle . 'Bundle:' . ($document ? $document : $bundle);
        $this->class       = $src . '\\' . $bundle . 'Bundle' . '\\Document\\' . ($document ? $document : $bundle);
        $this->document    = new $this->class();
        $this->translation = array($src . $bundle . 'Bundle', strtolower($bundle));
        $this->view        = $src . $bundle;
        $this->route       = $route;
        $this->bundle      = strtolower($bundle);
        $this->section     = "Api";
        $this->initForm($src . '\\' . $bundle . 'Bundle');
    }

    /**
     * @return array
     */
    public function indexAction()
    {
        // ERROR - Unknown route
        if(!$this->routeExists($this->source . '_admin_' . $this->route))
        {
            throw $this->createNotFoundException($this->get('translator')->trans('fhm.error.route', array(), 'FhmFhmBundle'));
        }

        return array(
            'instance' => $this->instanceData(),
        );
    }

    /**
     * @param Request $request
     *
     * @return array
     */
    public function autocompleteAction(Request $request)
    {
        $instance       = $this->instanceData();
        $dataSearch     = $request->get('text');
        $dataPagination = $request->get('pagination');
        $documents      = $this->dmRepository()->getFrontIndex($dataSearch, $dataPagination, $this->getParameter(array('autocomplete', 'page'), 'fhm_fhm'), $instance->grouping->current);

        return array(
            'text'       => $dataSearch,
            'field'      => $request->get('field'),
            'documents'  => $documents,
            'pagination' => $this->setPagination($this->getParameter(array('autocomplete', 'page'), 'fhm_fhm'), $this->getParameter(array('autocomplete', 'left'), 'fhm_fhm'), $this->getParameter(array('autocomplete', 'right'), 'fhm_fhm'))->getPagination($dataPagination, count($documents), $this->dmRepository()->getFrontCount($dataSearch, $instance->grouping->current)),
            'instance'   => $instance,
        );
    }

    /**
     * @param Request $request
     *
     * @return array
     */
    public function historicAction(Request $request)
    {
        $class = $this->class . 'Historic';
        if(!class_exists($class))
        {
            throw $this->createNotFoundException($this->get('translator')->trans('fhm.error.route', array(), 'FhmFhmBundle'));
        }

        return array(
            'document' => $this->dmRepository($this->repository . 'Historic')->find($request->get('id')),
            'instance' => $this->instanceData()
        );
    }

    /**
     * @param Request $request
     * @param         $id
     *
     * @return array
     */
    public function historicCopyAction(Request $request, $id)
    {
        $class = $this->class . 'Historic';
        if(!class_exists($class))
        {
            throw $this->createNotFoundException($this->get('translator')->trans('fhm.error.route', array(), 'FhmFhmBundle'));
        }
        $historic = $this->dmRepository($this->repository . 'Historic')->find($request->get('id'));
        $document = $historic->getHistoricParent();
        // Add
        $this->historicAdd($document, true);
        // Copy
        $document->historicMerge($this->dm(), $historic);
        $this->dmPersist($document);

        return $this->redirect($this->getLastRoute());
    }
}