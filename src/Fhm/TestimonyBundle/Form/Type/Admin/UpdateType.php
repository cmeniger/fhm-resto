<?php
namespace Fhm\TestimonyBundle\Form\Type\Admin;

use Fhm\FhmBundle\Form\Type\Admin\UpdateType as FhmType;
use Fhm\MediaBundle\Form\Type\MediaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class UpdateType
 * @package Fhm\TestimonyBundle\Form\Type\Admin
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
                'image',
                MediaType::class,
                array(
                    'label' => $options['translation_route'].'.admin.update.form.image',
                    'filter' => 'image/*',
                    'required' => false,
                )
            );
    }
    
}