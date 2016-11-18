<?php
namespace Fhm\WorkflowBundle\Form\Type\Admin\Action;

use Fhm\FhmBundle\Form\Type\Admin\CreateType as FhmType;
use Symfony\Component\Form\FormBuilderInterface;

class CreateType extends FhmType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);
        $builder
            ->add('validate_check', 'checkbox', array('label' => $this->instance->translation . '.admin.create.form.validate_check', 'required' => false))
            ->add('validate_users', 'document', array(
                'label'         => $this->instance->translation . '.admin.create.form.validate_users',
                'class'         => 'FhmUserBundle:User',
                'property'      => 'name',
                'query_builder' => function (\Fhm\UserBundle\Repository\UserRepository $dr)
                {
                    return $dr->getFormEnable($this->instance->grouping->filtered);
                },
                'multiple'      => true,
                'required'      => false,
                'by_reference'  => false
            ))
            ->add('dismiss_check', 'checkbox', array('label' => $this->instance->translation . '.admin.create.form.dismiss_check', 'required' => false))
            ->add('dismiss_users', 'document', array(
                'label'         => $this->instance->translation . '.admin.create.form.dismiss_users',
                'class'         => 'FhmUserBundle:User',
                'property'      => 'name',
                'query_builder' => function (\Fhm\UserBundle\Repository\UserRepository $dr)
                {
                    return $dr->getFormEnable($this->instance->grouping->filtered);
                },
                'multiple'      => true,
                'required'      => false,
                'by_reference'  => false
            ))
            ->add('cancel_check', 'checkbox', array('label' => $this->instance->translation . '.admin.create.form.cancel_check', 'required' => false))
            ->add('cancel_users', 'document', array(
                'label'         => $this->instance->translation . '.admin.create.form.cancel_users',
                'class'         => 'FhmUserBundle:User',
                'property'      => 'name',
                'query_builder' => function (\Fhm\UserBundle\Repository\UserRepository $dr)
                {
                    return $dr->getFormEnable($this->instance->grouping->filtered);
                },
                'multiple'      => true,
                'required'      => false,
                'by_reference'  => false
            ))
            ->add('upload_check', 'checkbox', array('label' => $this->instance->translation . '.admin.create.form.upload_check', 'required' => false))
            ->add('upload_users', 'document', array(
                'label'         => $this->instance->translation . '.admin.create.form.upload_users',
                'class'         => 'FhmUserBundle:User',
                'property'      => 'name',
                'query_builder' => function (\Fhm\UserBundle\Repository\UserRepository $dr)
                {
                    return $dr->getFormEnable($this->instance->grouping->filtered);
                },
                'multiple'      => true,
                'required'      => false,
                'by_reference'  => false
            ))
            ->add('download_check', 'checkbox', array('label' => $this->instance->translation . '.admin.create.form.download_check', 'required' => false))
            ->add('download_users', 'document', array(
                'label'         => $this->instance->translation . '.admin.create.form.download_users',
                'class'         => 'FhmUserBundle:User',
                'property'      => 'name',
                'query_builder' => function (\Fhm\UserBundle\Repository\UserRepository $dr)
                {
                    return $dr->getFormEnable($this->instance->grouping->filtered);
                },
                'multiple'      => true,
                'required'      => false,
                'by_reference'  => false
            ))
            ->add('comment_check', 'checkbox', array('label' => $this->instance->translation . '.admin.create.form.comment_check', 'required' => false))
            ->add('comment_users', 'document', array(
                'label'         => $this->instance->translation . '.admin.create.form.comment_users',
                'class'         => 'FhmUserBundle:User',
                'property'      => 'name',
                'query_builder' => function (\Fhm\UserBundle\Repository\UserRepository $dr)
                {
                    return $dr->getFormEnable($this->instance->grouping->filtered);
                },
                'multiple'      => true,
                'required'      => false,
                'by_reference'  => false
            ))
            ->add('tasks', 'document', array(
                'label'         => $this->instance->translation . '.admin.create.form.tasks',
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