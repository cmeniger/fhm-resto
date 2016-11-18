<?php
namespace Fhm\NoteBundle\Services;

use Fhm\FhmBundle\Controller\FhmController;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Session\Session;

/**
 * Class Note
 *
 * @package Fhm\NoteBundle\Services
 */
class Note extends FhmController
{
    /**
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        parent::__construct();
    }

    /***
     * @param $id
     *
     * @return float
     */
    public function average($id)
    {
        $sum   = $this->dmRepository('FhmNoteBundle:Note')->getAverageByObject($id);
        $count = $this->dmRepository('FhmNoteBundle:Note')->getCountByObject($id);
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
        return $this->dmRepository('FhmNoteBundle:Note')->getCountByObjectAndValue($document->getId(), $value);
    }
}