<?php
namespace Fhm\ContactBundle\Form\Type\Template;

use EWZ\Bundle\RecaptchaBundle\Validator\Constraints\True as RecaptchaTrue;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class DefaultType
 * @package Fhm\ContactBundle\Form\Type\Template
 */
class DefaultType extends AbstractType
{

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add(
            'firstname',
            TextType::class,
            array(
                'label' => 'contact.front.form.firstname',
                'mapped' => false,
            )
        )->add(
            'lastname',
            TextType::class,
            array(
                'label' => 'contact.front.form.lastname',
                'mapped' => false,
            )
        )->add(
            'email',
            EmailType::class,
            array('label' => 'contact.front.form.email', 'mapped' => false)
        )->add(
            'phone',
            TextType::class,
            array(
                'label' => 'contact.front.form.phone',
                'mapped' => false,
                'required' => false,
            )
        )->add(
            'content',
            TextareaType::class,
            array('label' => 'contact.front.form.content', 'mapped' => false)
        )->add(
            'submit',
            SubmitType::class,
            array('label' => 'contact.front.form.submit')
        );
    }

    /**
     * @return string
     */
    public function getBlockPrefix()
    {
        return 'FhmContactDefault';
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class' => '',
                'translation_domain' => 'FhmContactBundle',
                'cascade_validation' => true,
            )
        );
    }
}