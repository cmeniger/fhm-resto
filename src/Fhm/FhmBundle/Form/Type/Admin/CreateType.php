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
 * Class CreateType
 * @package Fhm\FhmBundle\Form\Type\Admin
 */
class CreateType extends AbstractType
{
    protected $instance;

    protected $translation;

    /**
     * @param $domaine
     */
    public function setTranslation($domaine)
    {
        $this->translation = $domaine;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->instance = $options['translation_domain'];

        $builder
            ->add('name', TextType::class, array('label' => $this->instance.'.admin.create.form.name'))
            ->add(
                'description',
                TextareaType::class,
                array('label' => $this->instance.'.admin.create.form.description', 'required' => false)
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
                array('label' => $this->instance.'.admin.create.form.active', 'required' => false)
            )
            ->add(
                'submitSave',
                SubmitType::class,
                array('label' => $this->instance.'.admin.create.form.submit.save')
            )
            ->add(
                'submitNew',
                SubmitType::class,
                array('label' => $this->instance.'.admin.create.form.submit.new')
            )
            ->add(
                'submitDuplicate',
                SubmitType::class,
                array('label' => $this->instance.'.admin.create.form.submit.duplicate')
            )
            ->add(
                'submitQuit',
                SubmitType::class,
                array('label' => $this->instance.'.admin.create.form.submit.quit')
            )
            ->add(
                'submitConfig',
                SubmitType::class,
                array('label' => $this->instance.'.admin.create.form.submit.config')
            );
//        if ($this->instance->language->visible) {
//            $builder->add(
//                'languages',
//                ChoiceType::class,
//                array(
//                    'choices' => $this->instance->language->available,
//                    'multiple' => true,
//                )
//            );
//        }
//        if ($this->instance->grouping->visible) {
//            $builder
//                ->add(
//                    'grouping',
//                    ChoiceType::class,
//                    array(
//                        'choices' => $this->instance->grouping->available,
//                        'multiple' => true,
//                    )
//                )
//                ->add(
//                    'share',
//                    CheckboxType::class,
//                    array('label' => $this->instance->translation.'.admin.create.form.share', 'required' => false)
//                );
//            if ($this->instance->user->admin) {
//                $builder->add(
//                    'global',
//                    CheckboxType::class,
//                    array('label' => $this->instance->translation.'.admin.create.form.global', 'required' => false)
//                );
//            }
//        }
    }

    /**
     * {@inheritdoc}
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
                'data_class' => 'Fhm\FhmBundle\Document\Fhm',
                'translation_domain' => 'FhmFhmBundle',
                'cascade_validation' => true,
            )
        );
    }
}