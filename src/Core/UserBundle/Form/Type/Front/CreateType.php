<?php
namespace Core\UserBundle\Form\Type\Front;

use Core\FhmBundle\Form\Type\Front\CreateType as FhmType;
use Symfony\Component\Form\FormBuilderInterface;

class CreateType extends FhmType
{
    public function __construct($instance)
    {
        $instance              = new \stdClass();
        $instance->translation = 'user';
        $instance->class       = 'Fhm\\UserBundle\\Document\\User';
        $instance->domain      = 'FhmUserBundle';
        parent::__construct($instance, null);
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);
        $builder
            ->add('username', 'text', array('label' => $this->instance->translation . '.front.create.form.username'))
            ->add('email', 'repeated', array
            (
                'type'            => 'email',
                'first_options'   => array('label' => $this->instance->translation . '.front.create.form.email'),
                'second_options'  => array('label' => $this->instance->translation . '.front.create.form.email_confirmation'),
                'invalid_message' => 'user.email.mismatch',
            ))
            ->add('plainPassword', 'repeated', array
            (
                'type'            => 'password',
                'first_options'   => array('label' => $this->instance->translation . '.front.create.form.password'),
                'second_options'  => array('label' => $this->instance->translation . '.front.create.form.password_confirmation'),
                'invalid_message' => 'user.password.mismatch',
            ))
            ->remove('name')
            ->remove('description')
            ->remove('submitNew')
            ->remove('submitDuplicate')
            ->remove('submitQuit');
    }
}