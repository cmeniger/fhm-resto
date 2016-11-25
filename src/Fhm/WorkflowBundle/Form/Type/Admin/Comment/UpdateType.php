<?php
namespace Fhm\WorkflowBundle\Form\Type\Admin\Comment;

use Fhm\FhmBundle\Form\Type\Admin\UpdateType as FhmType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UpdateType extends FhmType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->setTranslation('workflow');
        parent::buildForm($builder, $options);
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class' => 'Fhm\WorkflowBundle\Document\WorkflowComment',
                'translation_domain' => 'FhmWorkflowBundle',
                'cascade_validation' => true,
            )
        );
    }
}