<?php
namespace Fhm\UserBundle\Form\Type\Front;

use Fhm\FhmBundle\Form\Type\Front\UpdateType as FhmType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Validator\Constraints\UserPassword;

/**
 * Class ResettingType
 * @package Fhm\UserBundle\Form\Type\Front
 */
class ResettingType extends FhmType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);
        $builder->add(
            'plainPassword',
            RepeatedType::class,
            array(
                'type' => PasswordType::class,
                'first_options' => array(
                    'label' => $options['translation_route'].'.front.resetting.reset.form.password_new',
                ),
                'second_options' => array(
                    'label' => $options['translation_route'].'.front.resetting.reset.form.password_confirmation',
                ),
                'invalid_message' => 'fos_user.password.mismatch',
            )
        )->remove('name')->remove('description')->remove('submitNew')->remove('submitDuplicate')->remove(
            'submitQuit'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'FhmResetting';
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
                'translation_route' => 'user',
            )
        );
    }
}