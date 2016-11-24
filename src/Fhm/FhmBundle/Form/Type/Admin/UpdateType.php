<?php

namespace Fhm\FhmBundle\Form\Type\Admin;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class UpdateType
 * @package Fhm\FhmBundle\Form\Type\Admin
 */
class UpdateType extends AbstractType
{
    protected $instance;
    protected $document;


    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $data = $builder->getData();
        $this->instance = $data['instance'];
        $this->document = $data['document'];

        $builder
            ->add('name', TextType::class, array('label' => $this->instance->translation.'.admin.update.form.name'))
            ->add(
                'description',
                TextareaType::class,
                array('label' => $this->instance->translation.'.admin.update.form.description', 'required' => false)
            )
            ->add(
                'seo_title',
                TextType::class,
                array('label' => 'fhm.seo.title', 'translation_domain' => 'FhmFhmBundle', 'required' => false)
            )
            ->add(
                'seo_description',
                TextType::class,
                array('label' => 'fhm.seo.description', 'translation_domain' => 'FhmFhmBundle', 'required' => false)
            )
            ->add(
                'seo_keywords',
                TextType::class,
                array('label' => 'fhm.seo.keywords', 'translation_domain' => 'FhmFhmBundle', 'required' => false)
            )
            ->add(
                'active',
                CheckboxType::class,
                array('label' => $this->instance->translation.'.admin.update.form.active', 'required' => false)
            )
            ->add(
                'submitSave',
                SubmitType::class,
                array('label' => $this->instance->translation.'.admin.update.form.submit.save')
            )
            ->add(
                'submitNew',
                SubmitType::class,
                array('label' => $this->instance->translation.'.admin.update.form.submit.new')
            )
            ->add(
                'submitDuplicate',
                SubmitType::class,
                array('label' => $this->instance->translation.'.admin.update.form.submit.duplicate')
            )
            ->add(
                'submitQuit',
                SubmitType::class,
                array('label' => $this->instance->translation.'.admin.update.form.submit.quit')
            )
            ->add(
                'submitConfig',
                SubmitType::class,
                array('label' => $this->instance->translation.'.admin.update.form.submit.config')
            );
        if ($this->instance->language->visible) {
            $builder->add(
                'languages',
                ChoiceType::class,
                array(
                    'choices' => $this->instance->language->available,
                    'multiple' => true,
                )
            );
        }
        if ($this->instance->grouping->visible) {
            $builder
                ->add(
                    'grouping',
                    ChoiceType::class,
                    array(
                        'choices' => $this->instance->grouping->available,
                        'multiple' => true,
                    )
                )
                ->add(
                    'share',
                    CheckboxType::class,
                    array('label' => $this->instance->translation.'.admin.update.form.share', 'required' => false)
                );
            if ($this->instance->user->admin) {
                $builder->add(
                    'global',
                    CheckboxType::class,
                    array('label' => $this->instance->translation.'.admin.update.form.global', 'required' => false)
                );
            }
        }
    }

    /**
     * @return string
     */
    public function getBlockPrefix()
    {
        return 'FhmCreate';
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class' => $this->instance->class,
                'translation_domain' => $this->instance->domain,
                'cascade_validation' => true,
            )
        );
    }
}