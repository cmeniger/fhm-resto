<?php
namespace Fhm\FhmBundle\Controller;

use Symfony\Component\HttpFoundation\Request;

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
        $objects = $this->get('fhm_tools')->dmRepository(self::$repository)->getFrontIndex(
            $dataSearch
        );

        return array(
            'text' => $dataSearch,
            'field' => $request->get('field'),
            'objects' => $objects,
        );
    }
}