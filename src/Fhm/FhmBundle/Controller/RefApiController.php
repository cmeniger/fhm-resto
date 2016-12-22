<?php
namespace Fhm\FhmBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * Class RefApiController
 * @package Fhm\FhmBundle\Controller
 */
class RefApiController extends GenericController
{
    /**
     * @return array
     */
    public function indexAction()
    {
        return array();
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

    /**
     * @param Request $request
     * @param         $id
     *
     * @return array
     */
    public function historicDeleteAction(Request $request, $id)
    {
        $historic  = $this->get('fhm_tools')->dmRepository("FhmFhmBundle:Historic")->find($id);
        $this->get('fhm_tools')->dm()->persist($historic);

        return $this->redirect($this->getLastRoute());
    }

    /**
     * @param Request $request
     * @param         $id
     *
     * @return array
     */
    public function historicRestaureAction(Request $request,$id)
    {
        return $this->get('fhm_historic')->restaure($id);
    }



    /**
     * @param Request $request
     *
     * @return array
     */
    public function autocompleteAction(Request $request)
    {
        $dataSearch = $request->get('text');
        $documents = $this->get('fhm_tools')->dmRepository(self::$repository)->getFrontIndex(
            $dataSearch
        );

        return array(
            'text' => $dataSearch,
            'field' => $request->get('field'),
            'documents' => $documents,
        );
    }
}