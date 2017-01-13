<?php
/**
 * Created by PhpStorm.
 * User: reap
 * Date: 13/01/17
 * Time: 15:14
 */

namespace Fhm\Manager;


abstract class  AbstractManager
{
    protected $manager;

    /**
     * @return string
     */
    public function getCurrentRepository()
    {
        return '';
    }

    /**
     * @return string
     */
    public function getCurrentModelName()
    {
        return '';
    }

    /**
     * @return mixed
     */
    public function getManager()
    {
        return $this->manager;
    }
}