<?php
namespace Fhm\UserBundle\Form\Type\Front;

use Fhm\FhmBundle\Form\Type\Front\CreateType as FhmType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

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
        $this->setTranslation('user');
        parent::buildForm($builder, $options);
        $builder
            ->add('username', TextType::class, array('label' => $this->translation . '.front.create.form.username'))
            ->add('email', RepeatedType::class, array
            (
                'type'            => EmailType::class,
                'first_options'   => array('label' => $this->translation . '.front.create.form.email'),
                'second_options'  => array('label' => $this->translation . '.front.create.form.email_confirmation'),
                'invalid_message' => 'user.email.mismatch',
            ))
            ->add('plainPassword', RepeatedType::class, array
            (
                'type'            => PasswordType::class,
                'first_options'   => array('label' => $this->translation . '.front.create.form.password'),
                'second_options'  => array('label' => $this->translation . '.front.create.form.password_confirmation'),
                'invalid_message' => 'user.password.mismatch',
            ))
            ->remove('name')
            ->remove('description')
            ->remove('submitNew')
            ->remove('submitDuplicate')
            ->remove('submitQuit');
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class' => 'Fhm\UserBundle\Document\User',
                'translation_domain' => 'FhmUserBundle',
                'cascade_validation' => true,
            )
        );
    }
}