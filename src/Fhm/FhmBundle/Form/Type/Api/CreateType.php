<?php

namespace Fhm\FhmBundle\Form\Type\Api;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class CreateType
 * @package Fhm\FhmBundle\Form\Type\Api
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
        $data = $builder->getData();
        $this->instance = $data['instance'];

        $builder
            ->add('name', TextType::class, array('label' => $this->instance->translation.'.front.create.form.name'))
            ->add(
                'description',
                TextareaType::class,
                array('label' => $this->instance->translation.'.front.create.form.description', 'required' => false)
            )
            ->add(
                'submitSave',
                SubmitType::class,
                array('label' => $this->instance->translation.'.front.create.form.submit.save')
            )
            ->add(
                'submitNew',
                SubmitType::class,
                array('label' => $this->instance->translation.'.front.create.form.submit.new')
            )
            ->add(
                'submitDuplicate',
                SubmitType::class,
                array('label' => $this->instance->translation.'.front.create.form.submit.duplicate')
            )
            ->add(
                'submitQuit',
                SubmitType::class,
                array('label' => $this->instance->translation.'.front.create.form.submit.quit')
            );
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
                'data_class' => $this->instance->class,
                'translation_domain' => $this->instance->domain,
                'cascade_validation' => true,
            )
        );
    }
}