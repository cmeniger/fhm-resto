<?php
namespace Fhm\NoteBundle\Services;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Session\Session;

/**
 * Class Note
 *
 * @package Fhm\NoteBundle\Services
 */
class Note
{
    private $fhm_tools;

    /**
     * Note constructor.
     *
     * @param \Fhm\FhmBundle\Services\Tools $tools
     */
    public function __construct(\Fhm\FhmBundle\Services\Tools $tools)
    {
        $this->fhm_tools = $tools;
    }

    /***
     * @param $id
     *
     * @return float
     */
    public function average($id)
    {
        $sum   = $this->fhm_tools->dmRepository('FhmNoteBundle:Note')->getAverageByObject($id);
        $count = $this->fhm_tools->dmRepository('FhmNoteBundle:Note')->getCountByObject($id);
        if($count > 0)
        {
            return $sum / $count;
        }
        else
        {
            return 0;
        }
    }

    /**
     * @param $document
     * @param $value
     *
     * @return int
     */
    public function count($document, $value)
    {
        return $this->fhm_tools->dmRepository('FhmNoteBundle:Note')->getCountByObjectAndValue($document->getId(), $value);
    }
}