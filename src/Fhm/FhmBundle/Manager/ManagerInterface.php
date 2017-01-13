<?php
/**
 * Created by PhpStorm.
 * User: reap
 * Date: 13/01/17
 * Time: 15:14
 */

namespace Fhm\Manager;


interface ManagerInterface
{
    public function getCurrentRepository();
    public function getCurrentModelName();
}