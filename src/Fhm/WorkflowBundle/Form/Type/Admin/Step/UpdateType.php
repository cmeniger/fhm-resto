<?php
namespace Fhm\WorkflowBundle\Form\Type\Admin\Step;

use Fhm\FhmBundle\Form\Type\Admin\UpdateType as FhmType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
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
                'color',
                TextType::class,
                array(
                    'label' => $this->translation.'.admin.update.form.color',
                    'attr' => array('class' => 'colorpicker'),
                    'required' => false,
                )
            )
            ->add(
                'order',
                IntegerType::class,
                array('label' => $this->translation.'.admin.update.form.order', 'required' => false)
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
                'data_class' => 'Fhm\WorkflowBundle\Document\WorkflowStep',
                'translation_domain' => 'FhmWorkflowBundle',
                'cascade_validation' => true,
            )
        );
    }
}