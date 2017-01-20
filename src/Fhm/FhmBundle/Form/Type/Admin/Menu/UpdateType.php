<?php
namespace Fhm\FhmBundle\Form\Type\Admin\Menu;

use Fhm\FhmBundle\Form\Type\Admin\UpdateType as FhmType;
use Fhm\FhmBundle\Form\Type\Extension\LinkType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class UpdateType
 * @package Fhm\FhmBundle\Form\Type\Admin
 */
class UpdateType extends FhmType
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
            array('label' => $options['translation_route'].'.admin.update.form.icon', 'required' => false)
        )->add(
            'route',
            LinkType::class,
            array('label' => $options['translation_route'].'.admin.update.form.route', 'required' => false)
        )->remove('share')->remove('global');
    }
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class' => '',
                'translation_domain' => 'FhmFhmMenu',
                'cascade_validation' => true,
                'translation_route' => 'menu',
                'user_admin' => '',
                'object_manager'=>''
            )
        );
    }
}