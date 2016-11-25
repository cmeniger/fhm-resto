<?php
namespace Fhm\WorkflowBundle\Form\Type\Admin\Task;

use Doctrine\Bundle\MongoDBBundle\Form\Type\DocumentType;
use Fhm\FhmBundle\Form\Type\Admin\UpdateType as FhmType;
use Symfony\Component\Form\FormBuilderInterface;

class UpdateType extends FhmType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->setTranslation('workflow');
        parent::buildForm($builder, $options);
        $builder
            ->add('step', DocumentType::class, array(
                'label'         => $this->translation . '.admin.update.form.step',
                'class'         => 'FhmWorkflowBundle:WorkflowStep',
                'choice_label'      => 'name',
                'query_builder' => function (\Fhm\WorkflowBundle\Repository\WorkflowStepRepository $dr)
                {
                    return $dr->getFormEnable();
                },
                'required'      => false
            ))
            ->add('action', DocumentType::class, array(
                'label'         => $this->translation . '.admin.update.form.action',
                'class'         => 'FhmWorkflowBundle:WorkflowAction',
                'choice_label'      => 'name',
                'query_builder' => function (\Fhm\WorkflowBundle\Repository\WorkflowActionRepository $dr)
                {
                    return $dr->getFormEnable();
                }
            ))
            ->add('parents', DocumentType::class, array(
                'label'         => $this->translation . '.admin.update.form.parents',
                'class'         => 'FhmWorkflowBundle:WorkflowTask',
                'choice_label'      => 'name',
                'query_builder' => function (\Fhm\WorkflowBundle\Repository\WorkflowTaskRepository $dr)
                {
                    return $dr->getFormEnable();
                },
                'multiple'      => true,
                'required'      => false,
                'by_reference'  => false
            ))
            ->add('sons', DocumentType::class, array(
                'label'         => $this->translation . '.admin.update.form.sons',
                'class'         => 'FhmWorkflowBundle:WorkflowTask',
                'choice_label'      => 'name',
                'query_builder' => function (\Fhm\WorkflowBundle\Repository\WorkflowTaskRepository $dr)
                {
                    return $dr->getFormEnable();
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