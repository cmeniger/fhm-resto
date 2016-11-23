<?php
namespace Fhm\TestimonyBundle\Services;
/**
 * Class Testimony
 *
 * @package Fhm\TestimonyBundle\Services
 */
class Testimony
{
    private $fhm_tools;

    /**
     * Testimony constructor.
     *
     * @param \Fhm\FhmBundle\Services\Tools $tools
     */
    public function __construct(\Fhm\FhmBundle\Services\Tools $tools)
    {
        $this->fhm_tools = $tools;
    }
}