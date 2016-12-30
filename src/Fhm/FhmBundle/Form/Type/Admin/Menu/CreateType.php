<?php
namespace Fhm\FhmBundle\Form\Type\Front\Menu;

use Fhm\FhmBundle\Form\Type\Front\CreateType as FhmType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class CreateType
 * @package Fhm\FhmBundle\Form\Type\Front\Menu
 */
class CreateType extends FhmType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);
    }
}