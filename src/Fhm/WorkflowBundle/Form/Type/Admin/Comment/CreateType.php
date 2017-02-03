<?php
namespace Fhm\WorkflowBundle\Form\Type\Admin\Comment;

use Fhm\FhmBundle\Form\Type\Admin\CreateType as FhmType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class CreateType
 * @package Fhm\WorkflowBundle\Form\Type\Admin\Comment
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
                'translation_route' => 'workflow.comment',
                'user_admin' => '',
                'object_manager'=>''
            )
        );
    }
}