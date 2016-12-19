<?php
namespace Fhm\WorkflowBundle\Form\Type\Admin\Log;

use Doctrine\Bundle\MongoDBBundle\Form\Type\DocumentType;
use Fhm\FhmBundle\Form\Type\Admin\CreateType as FhmType;
use Fhm\WorkflowBundle\Repository\WorkflowTaskRepository;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class CreateType
 * @package Fhm\WorkflowBundle\Form\Type\Admin\Log
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
            'task',
            DocumentType::class,
            array(
                'label' => $options['translation_route'].'.admin.create.form.task',
                'class' => 'FhmWorkflowBundle:WorkflowTask',
                'choice_label' => 'name',
                'query_builder' => function (WorkflowTaskRepository $dr) use ($options) {
                    return $dr->getFormEnable($options['filter']);
                },
                'required' => false,
            )
        )->add(
            'type',
            ChoiceType::class,
            array(
                'label' => $options['translation_route'].'.admin.create.form.type',
                'choices' => array(
                    '0' => $options['translation_route'].'.type.0',
                    '1' => $options['translation_route'].'.type.1',
                    '2' => $options['translation_route'].'.type.2',
                    '3' => $options['translation_route'].'.type.3',
                    '4' => $options['translation_route'].'.type.4',
                    '5' => $options['translation_route'].'.type.5',
                ),
            )
        )->remove('seo_title')->remove('seo_description')->remove('seo_keywords')->remove('languages')->remove(
            'grouping'
        )->remove('share')->remove('global');
    }

}