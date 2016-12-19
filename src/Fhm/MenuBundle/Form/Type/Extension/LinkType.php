<?php
/**
 * Created by PhpStorm.
 * User: fhm
 * Date: 10/07/15
 * Time: 11:44
 */
namespace Fhm\MenuBundle\Form\Type\Extension;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Class LinkType
 * @package Fhm\MenuBundle\Form\Type\Extension
 */
class LinkType extends AbstractType
{
    /**
     * @return mixed
     */
    public function getParent()
    {
        return TextType::class;
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'link';
    }

} 