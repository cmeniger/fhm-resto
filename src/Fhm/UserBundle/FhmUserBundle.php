<?php

namespace Fhm\UserBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * Class FhmUserBundle
 * @package Fhm\UserBundle
 */
class FhmUserBundle extends Bundle
{
    /**
     * @return string
     */
    public function getParent()
    {
        return 'FOSUserBundle';
    }
}
