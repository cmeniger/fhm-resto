<?php
namespace Fhm\WorkflowBundle\Form\Type\Admin;

use Doctrine\Bundle\MongoDBBundle\Form\Type\DocumentType;
use Fhm\FhmBundle\Form\Type\Admin\CreateType as FhmType;
use Fhm\WorkflowBundle\Repository\WorkflowTaskRepository;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class CreateType
 * @package Fhm\WorkflowBundle\Form\Type\Admin
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
            'tasks',
            DocumentType::class,
            array(
                'label' => $options['translation_route'].'.admin.create.form.tasks',
                'class' => 'FhmWorkflowBundle:WorkflowTask',
                'choice_label' => 'name',
                'query_builder' => function (WorkflowTaskRepository $dr) use ($options) {
                    return $dr->getFormParent($options['filter']);
                },
                'multiple' => true,
                'required' => false,
                'by_reference' => false,
            )
        );
    }

}