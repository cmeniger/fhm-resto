<?php
namespace Fhm\GeolocationBundle\Form\Type\Admin;

use Fhm\FhmBundle\Form\Type\Admin\CreateType as FhmType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class CreateType
 * @package Fhm\GeolocationBundle\Form\Type\Admin
 */
class CreateType extends FhmType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->setTranslation('geolocation');
        parent::buildForm($builder, $options);
        $builder
            ->add(
                'address_search',
                TextType::class,
                array(
                    'label' => $this->translation.'.admin.create.form.address_search',
                    'mapped' => false,
                    'required' => false,
                )
            )
            ->add(
                'address_main',
                TextType::class,
                array('label' => $this->translation.'.admin.create.form.address_main', 'required' => false)
            )
            ->add(
                'address_additional',
                TextType::class,
                array(
                    'label' => $this->translation.'.admin.create.form.address_additional',
                    'required' => false,
                )
            )
            ->add(
                'zip_code',
                TextType::class,
                array('label' => $this->translation.'.admin.create.form.zip_code', 'required' => false)
            )
            ->add(
                'city',
                TextType::class,
                array('label' => $this->translation.'.admin.create.form.city', 'required' => false)
            )
            ->add(
                'country',
                TextType::class,
                array('label' => $this->translation.'.admin.create.form.country', 'required' => false)
            )
            ->add(
                'latitude',
                HiddenType::class,
                array('label' => $this->translation.'.admin.create.form.latitude', 'required' => false)
            )
            ->add(
                'longitude',
                HiddenType::class,
                array('label' => $this->translation.'.admin.create.form.longitude', 'required' => false)
            );
    }
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class' => 'Fhm\GeolocationBundle\Document\Geolocation',
                'translation_domain' => 'FhmGeolocationBundle',
                'cascade_validation' => true,
            )
        );
    }
}