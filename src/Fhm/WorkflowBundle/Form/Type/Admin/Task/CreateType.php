<?php
namespace Fhm\WorkflowBundle\Form\Type\Admin\Task;

use Doctrine\Bundle\MongoDBBundle\Form\Type\DocumentType;
use Fhm\FhmBundle\Form\Type\Admin\CreateType as FhmType;
use Fhm\FhmBundle\Manager\TypeManager;
use Fhm\WorkflowBundle\Repository\WorkflowActionRepository;
use Fhm\WorkflowBundle\Repository\WorkflowStepRepository;
use Fhm\WorkflowBundle\Repository\WorkflowTaskRepository;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class CreateType
 * @package Fhm\WorkflowBundle\Form\Type\Admin\Task
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
            'step',
            TypeManager::getType($options['object_manager']->getDBDriver()),
            array(
                'label' => $options['translation_route'].'.admin.create.form.step',
                'class' => 'FhmWorkflowBundle:WorkflowStep',
                'choice_label' => 'name',
                'query_builder' => function () use ($options) {
                    $dr = $options['object_manager']->getCurrentRepository('FhmWorkflowBundle:WorkflowStep');
                    return $dr->getFormEnable();
                },
                'required' => false,
            )
        )->add(
            'action',
            TypeManager::getType($options['object_manager']->getDBDriver()),
            array(
                'label' => $options['translation_route'].'.admin.create.form.action',
                'class' => 'FhmWorkflowBundle:WorkflowAction',
                'choice_label' => 'name',
                'query_builder' => function () use ($options) {
                    $dr = $options['object_manager']->getCurrentRepository('FhmWorkflowBundle:WorkflowAction');
                    return $dr->getFormEnable();
                },
            )
        )->add(
            'parents',
            TypeManager::getType($options['object_manager']->getDBDriver()),
            array(
                'label' => $options['translation_route'].'.admin.create.form.parents',
                'class' => 'FhmWorkflowBundle:WorkflowTask',
                'choice_label' => 'name',
                'query_builder' => function () use ($options) {
                    $dr = $options['object_manager']->getCurrentRepository('FhmWorkflowBundle:WorkflowTask');
                    return $dr->getFormEnable();
                },
                'multiple' => true,
                'required' => false,
                'by_reference' => false,
            )
        )->add(
            'sons',
            TypeManager::getType($options['object_manager']->getDBDriver()),
            array(
                'label' => $options['translation_route'].'.admin.create.form.sons',
                'class' => 'FhmWorkflowBundle:WorkflowTask',
                'choice_label' => 'name',
                'query_builder' => function () use ($options) {
                    $dr = $options['object_manager']->getCurrentRepository('FhmWorkflowBundle:WorkflowTask');
                    return $dr->getFormEnable();
                },
                'multiple' => true,
                'required' => false,
                'by_reference' => false,
            )
        )->remove('seo_title')->remove('seo_description')->remove('seo_keywords')->remove('languages')->remove(
            'grouping'
        )->remove('share')->remove('global');
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class' => '',
                'translation_domain' => 'FhmWorkflowBundle',
                'cascade_validation' => true,
                'translation_route' => 'workflow.task',
                'user_admin' => '',
                'object_manager'=>''
            )
        );
    }

}