<?php
/**
 * Created by PhpStorm.
 * User: fhm
 * Date: 10/07/15
 * Time: 11:44
 */

namespace Fhm\MenuBundle\Form\Type\Extension;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class LinkType extends  AbstractType {

    public function getParent()
    {
        return 'text';
    }

    public function getName()
    {
        return 'link';
    }

} 