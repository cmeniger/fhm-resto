<?php
namespace Fhm\TestimonyBundle\Form\Type\Admin;

use Fhm\FhmBundle\Form\Type\Admin\CreateType as FhmType;
use Fhm\MediaBundle\Form\Type\MediaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class CreateType
 * @package Fhm\TestimonyBundle\Form\Type\Admin
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
            'image',
            MediaType::class,
            array(
                'label' => $options['translation_route'].'.admin.create.form.image',
                'filter' => 'image/*',
                'required' => false,
            )
        );
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class' => 'Fhm\TestimonyBundle\Document\Testimony',
                'translation_domain' => 'FhmTestimonyBundle',
                'cascade_validation' => true,
                'translation_route' => 'testimony',
                'filter' => '',
                'user_admin' => '',
                'map' => '',
            )
        );
    }
}