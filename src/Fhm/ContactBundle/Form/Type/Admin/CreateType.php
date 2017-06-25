<?php

namespace Fhm\ContactBundle\Form\Type\Admin;

use Fhm\GeolocationBundle\Form\Type\Admin\CreateType as GeolocationType;
use Fhm\MediaBundle\Form\Type\MediaType;
use Fhm\FhmBundle\Form\Type\SchedulesType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class CreateType
 *
 * @package Fhm\ContactBundle\Form\Type\Admin
 */
class CreateType extends GeolocationType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);
        $builder
            ->add(
                'order',
                IntegerType::class,
                array('label' => $options['translation_route'] . '.admin.create.form.order', 'required' => false)
            )
            ->add(
                'phone',
                TextType::class,
                array('label' => $options['translation_route'] . '.admin.create.form.phone', 'required' => false)
            )
            ->add(
                'fax',
                TextType::class,
                array('label' => $options['translation_route'] . '.admin.create.form.fax', 'required' => false)
            )
            ->add(
                'email',
                EmailType::class,
                array('label' => $options['translation_route'] . '.admin.create.form.email', 'required' => false)
            )
            ->add(
                'schedules',
                SchedulesType::class,
                array('subscriber' => $options)
            )
            ->add(
                'form',
                CheckboxType::class,
                array('label' => $options['translation_route'] . '.admin.create.form.form.check', 'required' => false)
            )
            ->add(
                'form_template',
                TextType::class,
                array('label' => $options['translation_route'] . '.admin.create.form.form.template', 'required' => false)
            )
            ->add(
                'profile',
                CheckboxType::class,
                array('label' => $options['translation_route'] . '.admin.create.form.profile.check', 'required' => false)
            )
            ->add(
                'profile_template',
                TextType::class,
                array(
                    'label'    => $options['translation_route'] . '.admin.create.form.profile.template',
                    'required' => false,
                )
            )
            ->add(
                'profile_image',
                MediaType::class,
                array(
                    'label'    => $options['translation_route'] . '.admin.create.form.profile.image',
                    'filter'   => 'image/*',
                    'required' => false,
                )
            )
            ->add(
                'social_facebook',
                TextType::class,
                array(
                    'label'    => $options['translation_route'] . '.admin.create.form.social.facebook',
                    'required' => false,
                    'attr'     => array('placeholder' => $options['translation_route'] . '.admin.create.form.social.http'),
                )
            )
            ->add(
                'social_twitter',
                TextType::class,
                array(
                    'label'    => $options['translation_route'] . '.admin.create.form.social.twitter',
                    'required' => false,
                    'attr'     => array('placeholder' => $options['translation_route'] . '.admin.create.form.social.http'),
                )
            )
            ->add(
                'social_google',
                TextType::class,
                array(
                    'label'    => $options['translation_route'] . '.admin.create.form.social.google',
                    'required' => false,
                    'attr'     => array('placeholder' => $options['translation_route'] . '.admin.create.form.social.http'),
                )
            )
            ->add(
                'social_instagram',
                TextType::class,
                array(
                    'label'    => $options['translation_route'] . '.admin.create.form.social.instagram',
                    'required' => false,
                    'attr'     => array('placeholder' => $options['translation_route'] . '.admin.create.form.social.http'),
                )
            )
            ->add(
                'social_youtube',
                TextType::class,
                array(
                    'label'    => $options['translation_route'] . '.admin.create.form.social.youtube',
                    'required' => false,
                    'attr'     => array('placeholder' => $options['translation_route'] . '.admin.create.form.social.http'),
                )
            )
            ->add(
                'social_flux',
                TextType::class,
                array(
                    'label'    => $options['translation_route'] . '.admin.create.form.social.flux',
                    'required' => false,
                    'attr'     => array('placeholder' => $options['translation_route'] . '.admin.create.form.social.http'),
                )
            )
            ->add(
                'social_site',
                TextType::class,
                array(
                    'label'    => $options['translation_route'] . '.admin.create.form.social.site',
                    'required' => false,
                    'attr'     => array('placeholder' => $options['translation_route'] . '.admin.create.form.social.http'),
                )
            )
            ->remove('share')->remove('global');
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class'         => '',
                'translation_domain' => 'FhmContactBundle',
                'cascade_validation' => true,
                'translation_route'  => 'contact',
                'user_admin'         => '',
                'object_manager'     => ''
            )
        );
    }
}