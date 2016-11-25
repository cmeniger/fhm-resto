<?php
namespace Fhm\WorkflowBundle\Form\Type\Admin\Log;

use Doctrine\Bundle\MongoDBBundle\Form\Type\DocumentType;
use Fhm\FhmBundle\Form\Type\Admin\UpdateType as FhmType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UpdateType extends FhmType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->setTranslation('workflow');
        parent::buildForm($builder, $options);
        $builder
            ->add(
                'task',
                DocumentType::class,
                array(
                    'label' => $this->translation.'.admin.update.form.task',
                    'class' => 'FhmWorkflowBundle:WorkflowTask',
                    'choice_label' => 'name',
                    'query_builder' => function (\Fhm\WorkflowBundle\Repository\WorkflowTaskRepository $dr) {
                        return $dr->getFormEnable();
                    },
                    'required' => false,
                )
            )
            ->add(
                'type',
                ChoiceType::class,
                array(
                    'label' => $this->translation.'.admin.update.form.type',
                    'choices' => array(
                        '0' => $this->translation.'.type.0',
                        '1' => $this->translation.'.type.1',
                        '2' => $this->translation.'.type.2',
                        '3' => $this->translation.'.type.3',
                        '4' => $this->translation.'.type.4',
                        '5' => $this->translation.'.type.5',
                    ),
                )
            )
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
                'data_class' => 'Fhm\WorkflowBundle\Document\WorkflowLog',
                'translation_domain' => 'FhmWorkflowBundle',
                'cascade_validation' => true,
            )
        );
    }
}