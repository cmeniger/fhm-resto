<?php
namespace Fhm\MenuBundle\Form\Type\Admin;

use Fhm\FhmBundle\Form\Type\Admin\CreateType as FhmType;
use Fhm\MenuBundle\Form\Type\Extension\LinkType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class CreateType
 * @package Fhm\MenuBundle\Form\Type\Admin
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
        $builder->add(
            'icon',
            TextType::class,
            array('label' => $options['translation_route'].'.admin.create.form.icon', 'required' => false)
        )->add(
            'route',
            LinkType::class,
            array('label' => $options['translation_route'].'.admin.create.form.route', 'required' => false)
        )->add(
            'id',
            HiddenType::class,
            array('mapped' => false, 'attr' => array('value' => $builder->getData()->getId()))
        )->remove('share')->remove('global');
    }

}