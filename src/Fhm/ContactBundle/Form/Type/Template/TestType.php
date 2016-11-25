<?php
namespace Fhm\ContactBundle\Form\Type\Template;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class TestType
 * @package Fhm\ContactBundle\Form\Type\Template
 */
class TestType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'firstname',
                TextType::class,
                array(
                    'label' => 'contact.form.firstname'
                )
            )
            ->add(
                'lastname',
                TextType::class,
                array(
                    'label' => 'contact.form.lastname'
                )
            )
            ->add(
                'email',
                EmailType::class,
                array(
                    'label' => 'contact.form.email'
                )
            )
            ->add(
                'phone',
                TextType::class,
                array(
                    'label' => 'contact.form.phone', 'required' => false
                )
            )
            ->add(
                'content',
                TextareaType::class,
                array(
                    'label' => 'contact.form.content'
                )
            )
            ->add(
                'submit',
                SubmitType::class,
                array(
                    'label' => 'contact.form.submit'
                )
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
                'data_class' => 'Fhm\ContactBundle\Document\Contact',
                'translation_domain' => 'FhmContactBundle',
                'cascade_validation' => true,
            )
        );
    }
}