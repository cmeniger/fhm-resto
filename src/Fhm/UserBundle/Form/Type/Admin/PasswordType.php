<?php
namespace Fhm\UserBundle\Form\Type\Admin;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class PasswordType
 * @package Fhm\UserBundle\Form\Type\Admin
 */
class PasswordType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add(
            'plainPassword',
            RepeatedType::class,
            array(
                'type' => \Symfony\Component\Form\Extension\Core\Type\PasswordType::class,
                'first_options' => array(
                    'label' => 'user.admin.detail.password.password',
                ),
                'second_options' => array(
                    'label' => 'user.admin.detail.password.password_confirmation',
                ),
                'invalid_message' => 'fos_user.password.mismatch',
            )
        )->add(
            'submit',
            SubmitType::class,
            array('label' => 'user.admin.detail.password.submit')
        );
    }

    /**
     * @return string
     */
    public function getBlockPrefix()
    {
        return 'FhmPassword';
    }

}