<?php
namespace Fhm\WorkflowBundle\Form\Type\Admin\Log;

use Doctrine\Bundle\MongoDBBundle\Form\Type\DocumentType;
use Fhm\FhmBundle\Form\Type\Admin\UpdateType as FhmType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;

class UpdateType extends FhmType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);
        $builder
            ->add('task', DocumentType::class, array(
                'label'         => $this->instance->translation . '.admin.update.form.task',
                'class'         => 'FhmWorkflowBundle:WorkflowTask',
                'choice_label'      => 'name',
                'query_builder' => function (\Fhm\WorkflowBundle\Repository\WorkflowTaskRepository $dr)
                {
                    return $dr->getFormEnable($this->instance->grouping->filtered);
                },
                'required'      => false
            ))
            ->add('type', ChoiceType::class, array(
                'label'   => $this->instance->translation . '.admin.update.form.type',
                'choices' => array(
                    '0' => $this->instance->translation . '.type.0',
                    '1' => $this->instance->translation . '.type.1',
                    '2' => $this->instance->translation . '.type.2',
                    '3' => $this->instance->translation . '.type.3',
                    '4' => $this->instance->translation . '.type.4',
                    '5' => $this->instance->translation . '.type.5'
                ),
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