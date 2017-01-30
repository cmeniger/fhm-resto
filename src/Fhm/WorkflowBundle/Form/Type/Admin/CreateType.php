<?php
namespace Fhm\WorkflowBundle\Form\Type\Admin;

use Doctrine\Bundle\MongoDBBundle\Form\Type\DocumentType;
use Fhm\FhmBundle\Form\Type\Admin\CreateType as FhmType;
use Fhm\FhmBundle\Manager\TypeManager;
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
            TypeManager::getType($options['object_manager']->getDBDriver()),
            array(
                'label' => $options['translation_route'].'.admin.create.form.tasks',
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
        );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class' => '',
                'translation_domain' => 'FhmWorkflowBundle',
                'cascade_validation' => true,
                'translation_route' => 'workflow',
                'user_admin' => '',
                'object_manager'=>''
            )
        );
    }

}