<?php
namespace Fhm\ContactBundle\Form\Type\Template;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class TestType extends AbstractType
{
    protected $instance;

    public function __construct($instance)
    {
        $this->instance = $instance;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstname', 'text', array('label' => $this->instance->translation . '.form.firstname'))
            ->add('lastname', 'text', array('label' => $this->instance->translation . '.form.lastname'))
            ->add('email', 'email', array('label' => $this->instance->translation . '.form.email'))
            ->add('phone', 'text', array('label' => $this->instance->translation . '.form.phone', 'required' => false))
            ->add('content', 'textarea', array('label' => $this->instance->translation . '.form.content'))
            ->add('submit', 'submit', array('label' => $this->instance->translation . '.form.submit'));
    }

    public function getName()
    {
        return 'FhmContactTest';
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