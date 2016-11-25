<?php
namespace Fhm\WorkflowBundle\Form\Type\Admin\Action;

use Doctrine\Bundle\MongoDBBundle\Form\Type\DocumentType;
use Fhm\FhmBundle\Form\Type\Admin\UpdateType as FhmType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UpdateType extends FhmType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->setTranslation('workflow');
        parent::buildForm($builder, $options);
        $builder
            ->add('validate_check', CheckboxType::class, array('label' => $this->translation . '.admin.update.form.validate_check', 'required' => false))
            ->add('validate_users', DocumentType::class, array(
                'label'         => $this->translation . '.admin.update.form.validate_users',
                'class'         => 'FhmUserBundle:User',
                'choice_label'      => 'name',
                'query_builder' => function (\Fhm\UserBundle\Repository\UserRepository $dr)
                {
                    return $dr->getFormEnable();
                },
                'multiple'      => true,
                'required'      => false,
                'by_reference'  => false
            ))
            ->add('dismiss_check', CheckboxType::class, array('label' => $this->translation . '.admin.update.form.dismiss_check', 'required' => false))
            ->add('dismiss_users', DocumentType::class, array(
                'label'         => $this->translation . '.admin.update.form.dismiss_users',
                'class'         => 'FhmUserBundle:User',
                'choice_label'      => 'name',
                'query_builder' => function (\Fhm\UserBundle\Repository\UserRepository $dr)
                {
                    return $dr->getFormEnable();
                },
                'multiple'      => true,
                'required'      => false,
                'by_reference'  => false
            ))
            ->add('cancel_check', CheckboxType::class, array('label' => $this->translation . '.admin.update.form.cancel_check', 'required' => false))
            ->add('cancel_users', DocumentType::class, array(
                'label'         => $this->translation . '.admin.update.form.cancel_users',
                'class'         => 'FhmUserBundle:User',
                'choice_label'      => 'name',
                'query_builder' => function (\Fhm\UserBundle\Repository\UserRepository $dr)
                {
                    return $dr->getFormEnable();
                },
                'multiple'      => true,
                'required'      => false,
                'by_reference'  => false
            ))
            ->add('upload_check', CheckboxType::class, array('label' => $this->translation . '.admin.update.form.upload_check', 'required' => false))
            ->add('upload_users', DocumentType::class, array(
                'label'         => $this->translation . '.admin.update.form.upload_users',
                'class'         => 'FhmUserBundle:User',
                'choice_label'      => 'name',
                'query_builder' => function (\Fhm\UserBundle\Repository\UserRepository $dr)
                {
                    return $dr->getFormEnable();
                },
                'multiple'      => true,
                'required'      => false,
                'by_reference'  => false
            ))
            ->add('download_check', CheckboxType::class, array('label' => $this->translation . '.admin.update.form.download_check', 'required' => false))
            ->add('download_users', DocumentType::class, array(
                'label'         => $this->translation . '.admin.update.form.download_users',
                'class'         => 'FhmUserBundle:User',
                'choice_label'      => 'name',
                'query_builder' => function (\Fhm\UserBundle\Repository\UserRepository $dr)
                {
                    return $dr->getFormEnable();
                },
                'multiple'      => true,
                'required'      => false,
                'by_reference'  => false
            ))
            ->add('comment_check',CheckboxType::class , array('label' => $this->translation . '.admin.update.form.comment_check', 'required' => false))
            ->add('comment_users', DocumentType::class, array(
                'label'         => $this->translation . '.admin.update.form.comment_users',
                'class'         => 'FhmUserBundle:User',
                'choice_label'      => 'name',
                'query_builder' => function (\Fhm\UserBundle\Repository\UserRepository $dr)
                {
                    return $dr->getFormEnable();
                },
                'multiple'      => true,
                'required'      => false,
                'by_reference'  => false
            ))
            ->add('tasks', DocumentType::class, array(
                'label'         => $this->translation . '.admin.update.form.tasks',
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

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class' => 'Fhm\WorkflowBundle\Document\WorkflowAction',
                'translation_domain' => 'FhmWorkflowBundle',
                'cascade_validation' => true,
            )
        );
    }
}