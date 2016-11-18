<?php

namespace Fhm\UserBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class FhmUserBundle extends Bundle
{
    public function getParent()
    {
        return 'FOSUserBundle';
    }
}
