<?php

namespace Fhm\FhmBundle\Form\Type\Front;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class UpdateType extends AbstractType
{
    protected $instance;
    protected $document;

    public function __construct($instance, $document)
    {
        $this->instance = $instance;
        $this->document = $document;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', 'text', array('label' => $this->instance->translation . '.front.update.form.name'))
            ->add('description', 'textarea', array('label' => $this->instance->translation . '.front.update.form.description', 'required' => false))
            ->add('submitSave', 'submit', array('label' => $this->instance->translation . '.front.update.form.submit.save'))
            ->add('submitNew', 'submit', array('label' => $this->instance->translation . '.front.update.form.submit.new'))
            ->add('submitDuplicate', 'submit', array('label' => $this->instance->translation . '.front.update.form.submit.duplicate'))
            ->add('submitQuit', 'submit', array('label' => $this->instance->translation . '.front.update.form.submit.quit'));
    }

    public function getName()
    {
        return 'FhmUpdate';
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults
        (
            array(
                'data_class'         => $this->instance->class,
                'translation_domain' => $this->instance->domain,
                'cascade_validation' => true
            )
        );
    }
}