<?php
namespace Fhm\WorkflowBundle\Form\Type\Admin\Step;

use Fhm\FhmBundle\Form\Type\Admin\UpdateType as FhmType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class UpdateType
 * @package Fhm\WorkflowBundle\Form\Type\Admin\Step
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
            'color',
            TextType::class,
            array(
                'label' => $options['translation_route'].'.admin.update.form.color',
                'attr' => array('class' => 'colorpicker'),
                'required' => false,
            )
        )->add(
            'order',
            IntegerType::class,
            array('label' => $options['translation_route'].'.admin.update.form.order', 'required' => false)
        )->remove('seo_title')->remove('seo_description')->remove('seo_keywords')->remove('languages')->remove(
            'grouping'
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
                'translation_domain' => 'FhmWorkflowBundle',
                'cascade_validation' => true,
                'translation_route' => 'workflow.step',
                'user_admin' => '',
                'object_manager'=>''
            )
        );
    }
}