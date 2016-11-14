<?php
namespace Core\UserBundle\Form\Type\Front;

use Core\FhmBundle\Form\Type\Front\UpdateType as FhmType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Security\Core\Validator\Constraints\UserPassword;

class ResettingType extends FhmType
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
            ->add('plainPassword', 'repeated', array
            (
                'type'            => 'password',
                'first_options'   => array('label' => $this->instance->translation . '.front.resetting.reset.form.password_new'),
                'second_options'  => array('label' => $this->instance->translation . '.front.resetting.reset.form.password_confirmation'),
                'invalid_message' => 'fos_user.password.mismatch',
            ))
            ->remove('name')
            ->remove('description')
            ->remove('submitNew')
            ->remove('submitDuplicate')
            ->remove('submitQuit');
    }

    public function getName()
    {
        return 'FhmResetting';
    }
}