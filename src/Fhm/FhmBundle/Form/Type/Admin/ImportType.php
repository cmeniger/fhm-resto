<?php

namespace Fhm\FhmBundle\Form\Type\Admin;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ImportType extends AbstractType
{
    protected $instance;

    public function __construct($instance)
    {
        $this->instance = $instance;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('file', 'file', array('label' => $this->instance->translation . '.admin.import.form.file'))
            ->add('submit', 'submit', array('label' => $this->instance->translation . '.admin.import.form.submit'));
    }

    public function getName()
    {
        return 'FhmImport';
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults
            (
                array(
                    'data_class'         => null,
                    'translation_domain' => $this->instance->domain,
                    'cascade_validation' => true
                )
            );
    }
}