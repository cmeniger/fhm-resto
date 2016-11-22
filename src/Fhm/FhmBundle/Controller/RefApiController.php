<?php
namespace Fhm\FhmBundle\Controller;

use Fhm\FhmBundle\Document\Fhm;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

class RefApiController extends Controller
{
    protected $fhm_tools;
    protected $source;
    protected $class;
    protected $document;
    protected $translation;
    protected $route;
    protected $form;
    protected $container;

    /**
     * @param string $src
     * @param string $bundle
     * @param string $route
     * @param string $document
     */
    public function __construct($src = 'Fhm', $bundle = 'Fhm', $route = '', $document = '')
    {
        $datas             = $this->fhm_tools->initData($src, $bundle, $route, $document, 'api');
        $this->source      = $datas['source'];
        $this->class       = $datas['class'];
        $this->document    = $datas['document'];
        $this->translation = $datas['translation'];
        $this->route       = $datas['route'];
        $this->form        = $datas['form'];
    }

    /**
     * @param \Fhm\FhmBundle\Services\Tools $tools
     *
     * @return $this
     */
    public function setFhmTools(\Fhm\FhmBundle\Services\Tools $tools)
    {
        $this->setContainer($tools->getContainer());
        $this->fhm_tools = $tools;

        return $this;
    }

    /**
     * @return array
     */
    public function indexAction()
    {
        // ERROR - Unknown route
        if(!$this->fhm_tools->routeExists($this->source . '_admin_' . $this->route))
        {
            throw $this->createNotFoundException($this->fhm_tools->trans('fhm.error.route', array(), 'FhmFhmBundle'));
        }

        return array(
            'instance' => $this->fhm_tools->instanceData(),
        );
    }

    /**
     * @param Request $request
     *
     * @return array
     */
    public function autocompleteAction(Request $request)
    {
        $instance       = $this->fhm_tools->instanceData();
        $dataSearch     = $request->get('text');
        $dataPagination = $request->get('pagination');
        $documents      = $this->fhm_tools->dmRepository()->getFrontIndex($dataSearch, $dataPagination, $this->fhm_tools->getParameter(array('autocomplete', 'page'), 'fhm_fhm'), $instance->grouping->current);

        return array(
            'text'       => $dataSearch,
            'field'      => $request->get('field'),
            'documents'  => $documents,
            'pagination' => $this->fhm_tools->setPagination($this->fhm_tools->getParameter(array('autocomplete', 'page'), 'fhm_fhm'), $this->fhm_tools->getParameter(array('autocomplete', 'left'), 'fhm_fhm'), $this->fhm_tools->getParameter(array('autocomplete', 'right'), 'fhm_fhm'))->getPagination($dataPagination, count($documents), $this->fhm_tools->dmRepository()->getFrontCount($dataSearch, $instance->grouping->current)),
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
            throw $this->createNotFoundException($this->fhm_tools->trans('fhm.error.route', array(), 'FhmFhmBundle'));
        }

        return array(
            'document' => $this->fhm_tools->dmRepository($this->repository . 'Historic')->find($request->get('id')),
            'instance' => $this->fhm_tools->instanceData()
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
            throw $this->createNotFoundException($this->fhm_tools->trans('fhm.error.route', array(), 'FhmFhmBundle'));
        }
        $historic = $this->fhm_tools->dmRepository($this->repository . 'Historic')->find($request->get('id'));
        $document = $historic->getHistoricParent();
        // Add
        $this->fhm_tools->historicAdd($document, true);
        // Copy
        $document->historicMerge($this->fhm_tools->dm(), $historic);
        $this->fhm_tools->dmPersist($document);

        return $this->redirect($this->fhm_tools->getLastRoute());
    }
}