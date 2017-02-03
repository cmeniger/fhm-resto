<?php
namespace Fhm\MailBundle\Form\Type\Admin;

use Fhm\FhmBundle\Form\Type\Admin\CreateType as FhmType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class CreateType
 * @package Fhm\MailBundle\Form\Type\Admin
 */
class CreateType extends FhmType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add(
            'to',
            EmailType::class,
            array(
                'label' => $options['translation_route'].'.admin.create.form.to',
                'mapped' => false,
            )
        )->add(
            'object',
            TextType::class,
            array(
                'label' => $options['translation_route'].'.admin.create.form.object',
                'mapped' => false,
            )
        )->add(
            'body',
            TextareaType::class,
            array(
                'label' => $options['translation_route'].'.admin.create.form.body',
                'attr' => array('class' => 'editor'),
                'mapped' => false,
            )
        )->add(
            'submitSave',
            SubmitType::class,
            array(
                'label' => $options['translation_route'].'.admin.create.form.submit.save',
            )
        );
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class' => 'FhmMailBundle:Mail',
                'translation_domain' => 'FhmMailBundle',
                'cascade_validation' => true,
                'translation_route' => 'mail',
                'filter' => '',
                'user_admin' => '',
            )
        );
    }
}