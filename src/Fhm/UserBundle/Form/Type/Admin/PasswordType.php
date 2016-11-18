<?php
namespace Fhm\UserBundle\Form\Type\Admin;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class PasswordType extends AbstractType
{
    protected $instance;

    public function __construct($instance)
    {
        $this->instance = $instance;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('plainPassword', 'repeated', array
            (
                'type'            => 'password',
                'first_options'   => array('label' => $this->instance->translation . '.admin.detail.password.password'),
                'second_options'  => array('label' => $this->instance->translation . '.admin.detail.password.password_confirmation'),
                'invalid_message' => 'fos_user.password.mismatch'
            ))
            ->add('submit', 'submit', array('label' => $this->instance->translation . '.admin.detail.password.submit'));
    }

    public function getName()
    {
        return 'FhmPassword';
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