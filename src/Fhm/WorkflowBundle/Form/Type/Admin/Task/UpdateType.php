<?php
namespace Fhm\WorkflowBundle\Form\Type\Admin\Task;

use Fhm\FhmBundle\Form\Type\Admin\UpdateType as FhmType;
use Symfony\Component\Form\FormBuilderInterface;

class UpdateType extends FhmType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);
        $builder
            ->add('step', 'document', array(
                'label'         => $this->instance->translation . '.admin.update.form.step',
                'class'         => 'FhmWorkflowBundle:WorkflowStep',
                'property'      => 'name',
                'query_builder' => function (\Fhm\WorkflowBundle\Repository\WorkflowStepRepository $dr)
                {
                    return $dr->getFormEnable($this->instance->grouping->filtered);
                },
                'required'      => false
            ))
            ->add('action', 'document', array(
                'label'         => $this->instance->translation . '.admin.update.form.action',
                'class'         => 'FhmWorkflowBundle:WorkflowAction',
                'property'      => 'name',
                'query_builder' => function (\Fhm\WorkflowBundle\Repository\WorkflowActionRepository $dr)
                {
                    return $dr->getFormEnable($this->instance->grouping->filtered);
                }
            ))
            ->add('parents', 'document', array(
                'label'         => $this->instance->translation . '.admin.update.form.parents',
                'class'         => 'FhmWorkflowBundle:WorkflowTask',
                'property'      => 'name',
                'query_builder' => function (\Fhm\WorkflowBundle\Repository\WorkflowTaskRepository $dr)
                {
                    return $dr->getFormEnable($this->instance->grouping->filtered);
                },
                'multiple'      => true,
                'required'      => false,
                'by_reference'  => false
            ))
            ->add('sons', 'document', array(
                'label'         => $this->instance->translation . '.admin.update.form.sons',
                'class'         => 'FhmWorkflowBundle:WorkflowTask',
                'property'      => 'name',
                'query_builder' => function (\Fhm\WorkflowBundle\Repository\WorkflowTaskRepository $dr)
                {
                    return $dr->getFormEnable($this->instance->grouping->filtered);
                },
                'multiple'      => true,
                'required'      => false,
                'by_reference'  => false
            ))
            ->remove('seo_title')
            ->remove('seo_description')
            ->remove('seo_keywords')
            ->remove('languages')
            ->remove('grouping')
            ->remove('share')
            ->remove('global');
    }
}