<?php
namespace Fhm\GeolocationBundle\Form\Type\Front;

use Fhm\FhmBundle\Form\Type\Front\UpdateType as FhmType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class UpdateType
 * @package Fhm\GeolocationBundle\Form\Type\Front
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
        $builder
            ->add(
                'address_search',
                TextType::class,
                array(
                    'label' => $options['translation_route'].'.front.update.form.address_search',
                    'mapped' => false,
                    'required' => false,
                )
            )
            ->add(
                'address_main',
                TextType::class,
                array('label' => $options['translation_route'].'.front.update.form.address_main', 'required' => false)
            )
            ->add(
                'address_additional',
                TextType::class,
                array(
                    'label' => $options['translation_route'].'.front.update.form.address_additional',
                    'required' => false,
                )
            )
            ->add(
                'zip_code',
                TextType::class,
                array('label' => $options['translation_route'].'.front.update.form.zip_code', 'required' => false)
            )
            ->add(
                'city',
                TextType::class,
                array('label' => $options['translation_route'].'.front.update.form.city', 'required' => false)
            )
            ->add(
                'country',
                TextType::class,
                array('label' => $options['translation_route'].'.front.update.form.country', 'required' => false)
            )
            ->add(
                'latitude',
                HiddenType::class,
                array('label' => $options['translation_route'].'.front.update.form.latitude', 'required' => false)
            )
            ->add(
                'longitude',
                HiddenType::class,
                array('label' => $options['translation_route'].'.front.update.form.longitude', 'required' => false)
            );
    }
}