<?php
namespace Fhm\MapPickerBundle\Form\Type\Admin;

use Fhm\FhmBundle\Form\Type\Admin\CreateType as FhmType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class CreateType
 * @package Fhm\MapPickerBundle\Form\Type\Admin
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
            'order',
            NumberType::class,
            array('label' => $options['translation_route'].'.admin.create.form.order', 'required' => false)
        )->add(
            'map',
            ChoiceType::class,
            array(
                'choices' => function () use ($options) {
                    $mapsArray['nomap'] = $options['translation_route'].'.nomap.choice';
                    foreach ($options['map'] as $map) {
                        $mapsArray[$map] = $options['translation_route'].'.'.$map.'.choice';
                    }

                    return $mapsArray;
                },
                'label' => $options['translation_route'].'.admin.create.form.map',
            )
        )->remove('global');
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class' => 'Fhm\FhmBundle\Document\Fhm',
                'translation_domain' => 'FhmFhmBundle',
                'cascade_validation' => true,
                'translation_route' => '',
                'filter' => '',
                'lang_visible' => '',
                'lang_available' => '',
                'grouping_visible' => '',
                'grouping_available' => '',
                'user_admin' => '',
                'map' => '',
            )
        );
    }
}