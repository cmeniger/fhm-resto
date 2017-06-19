<?php

namespace Fhm\CardBundle\Form\Type\Model;

use Fhm\MediaBundle\Form\Type\MediaType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class M001ProductCreateType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'name',
                TextType::class,
                array(
                    'label' => $options['translation_route'] . '.api.create.form.name'
                ))
            ->add(
                'ingredient',
                TextareaType::class,
                array(
                    'label'    => $options['translation_route'] . '.api.create.form.ingredient',
                    'required' => false,
                    'mapped'   => false
                ))
            ->add(
                'price',
                TextType::class,
                array(
                    'label'    => $options['translation_route'] . '.api.create.form.price',
                    'required' => false
                ))
            ->add(
                'currency',
                TextType::class,
                array(
                    'label'    => $options['translation_route'] . '.api.create.form.currency',
                    'required' => false
                ))
            ->add(
                'forward',
                CheckboxType::class,
                array(
                    'label'    => $options['translation_route'] . '.api.create.form.forward',
                    'required' => false
                ))
            ->add(
                'image',
                MediaType::class,
                array(
                    'label'    => $options['translation_route'] . '.api.create.form.image',
                    'filter'   => 'image/*',
                    'required' => false
                ))
            ->add(
                'submitSave',
                SubmitType::class,
                array(
                    'label' => $options['translation_route'] . '.api.create.form.submit.save'
                ));
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class'         => '',
                'translation_domain' => 'FhmCardBundle',
                'translation_route'  => 'card.product',
                'cascade_validation' => true,
                'user_admin'         => '',
                'object_manager'     => '',
                'card'               => ''
            )
        );
    }
}