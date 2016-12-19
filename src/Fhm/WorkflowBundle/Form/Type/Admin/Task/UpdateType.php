<?php
namespace Fhm\WorkflowBundle\Form\Type\Admin\Task;

use Doctrine\Bundle\MongoDBBundle\Form\Type\DocumentType;
use Fhm\FhmBundle\Form\Type\Admin\UpdateType as FhmType;
use Fhm\WorkflowBundle\Repository\WorkflowActionRepository;
use Fhm\WorkflowBundle\Repository\WorkflowStepRepository;
use Fhm\WorkflowBundle\Repository\WorkflowTaskRepository;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class UpdateType
 * @package Fhm\WorkflowBundle\Form\Type\Admin\Task
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
            'step',
            DocumentType::class,
            array(
                'label' => $options['translation_route'].'.admin.update.form.step',
                'class' => 'FhmWorkflowBundle:WorkflowStep',
                'choice_label' => 'name',
                'query_builder' => function (WorkflowStepRepository $dr) use ($options) {
                    return $dr->getFormEnable($options['filter']);
                },
                'required' => false,
            )
        )->add(
            'action',
            DocumentType::class,
            array(
                'label' => $options['translation_route'].'.admin.update.form.action',
                'class' => 'FhmWorkflowBundle:WorkflowAction',
                'choice_label' => 'name',
                'query_builder' => function (WorkflowActionRepository $dr) use ($options) {
                    return $dr->getFormEnable($options['filter']);
                },
            )
        )->add(
            'parents',
            DocumentType::class,
            array(
                'label' => $options['translation_route'].'.admin.update.form.parents',
                'class' => 'FhmWorkflowBundle:WorkflowTask',
                'choice_label' => 'name',
                'query_builder' => function (WorkflowTaskRepository $dr) use ($options) {
                    return $dr->getFormEnable($options['filter']);
                },
                'multiple' => true,
                'required' => false,
                'by_reference' => false,
            )
        )->add(
            'sons',
            DocumentType::class,
            array(
                'label' => $options['translation_route'].'.admin.update.form.sons',
                'class' => 'FhmWorkflowBundle:WorkflowTask',
                'choice_label' => 'name',
                'query_builder' => function (WorkflowTaskRepository $dr) use ($options) {
                    return $dr->getFormEnable($options['filter']);
                },
                'multiple' => true,
                'required' => false,
                'by_reference' => false,
            )
        )->remove('seo_title')->remove('seo_description')->remove('seo_keywords')->remove('languages')->remove(
            'grouping'
        )->remove('share')->remove('global');
    }
}