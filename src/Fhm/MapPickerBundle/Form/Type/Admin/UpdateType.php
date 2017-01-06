<?php
namespace Fhm\MapPickerBundle\Form\Type\Admin;

use Fhm\FhmBundle\Form\Type\Admin\UpdateType as FhmType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class UpdateType
 * @package Fhm\MapPickerBundle\Form\Type\Admin
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
            'order',
            NumberType::class,
            array('label' => $options['translation_route'].'.admin.create.form.order', 'required' => false)
        )->add(
            'map',
            ChoiceType::class,
            array(
                'label' => $options['translation_route'].'.admin.update.form.map', 'required' => false,
                'choices' => $this->getMapChoices($options),
            )
        )->remove('global');
    }

    /***
     * @param $options
     * @return mixed
     */
    public function getMapChoices($options)
    {
        $mapsArray[$options['translation_route'].'.nomap.choice'] = 'nomap';
        foreach ($options['map'] as $map) {
            $mapsArray[$options['translation_route'].'.'.$map.'.choice'] = $map;
        }

        return $mapsArray;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class' => 'Fhm\MapPickerBundle\Document\MapPicker',
                'translation_domain' => 'FhmMapPickerBundle',
                'cascade_validation' => true,
                'translation_route' => 'mappicker',
                'filter' => '',
                'user_admin' => '',
                'map' => '',
            )
        );
    }
}