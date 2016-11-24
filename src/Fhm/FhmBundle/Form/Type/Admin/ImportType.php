<?php

namespace Fhm\FhmBundle\Form\Type\Admin;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class ImportType
 * @package Fhm\FhmBundle\Form\Type\Admin
 */
class ImportType extends AbstractType
{
    protected $instance;

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $data = $builder->getData();
        $this->instance = $data['instance'];
        $builder
            ->add('file', FileType::class, array('label' => $this->instance->translation.'.admin.import.form.file'))
            ->add(
                'submit',
                SubmitType::class,
                array('label' => $this->instance->translation.'.admin.import.form.submit')
            );
    }

    /**
     * @return string
     */
    public function getBlockPrefix()
    {
        return 'FhmImport';
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class' => null,
                'translation_domain' => $this->instance->domain,
                'cascade_validation' => true,
            )
        );
    }
}