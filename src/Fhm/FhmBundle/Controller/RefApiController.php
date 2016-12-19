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