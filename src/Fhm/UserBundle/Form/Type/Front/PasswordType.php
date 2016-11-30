<?php
namespace Fhm\UserBundle\Form\Type\Front;

use Fhm\FhmBundle\Form\Type\Front\UpdateType as FhmType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Validator\Constraints\UserPassword;
use Symfony\Component\Form\Extension\Core\Type\PasswordType as PasswordTypeBase;

class PasswordType extends FhmType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->setTranslation('user');
        parent::buildForm($builder, $options);
        $builder
            ->add(
                'current_password',
                PasswordTypeBase::class,
                array(
                    'label' => $options['translation_route'].'.front.password.form.password',
                    'mapped' => false,
                    'constraints' => new UserPassword(),
                )
            )
            ->add(
                'plainPassword',
                RepeatedType::class,
                array
                (
                    'type' => PasswordTypeBase::class,
                    'first_options' => array(
                        'label' => $options['translation_route'].'.front.password.form.password_new'),
                    'second_options' => array(
                        'label' => $options['translation_route'].'.front.password.form.password_confirmation'),
                    'invalid_message' => 'fos_user.password.mismatch',
                )
            )
            ->remove('name')
            ->remove('description')
            ->remove('submitNew')
            ->remove('submitDuplicate')
            ->remove('submitQuit');
    }

    public function getName()
    {
        return 'FhmPassword';
    }
    
}