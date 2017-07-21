<?php

namespace Project\DefaultBundle\Form\Type\Moderator;

use Fhm\MediaBundle\Form\Type\MediaType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class SiteType
 *
 * @package Project\DefaultBundle\Form\Type\Moderator
 */
class SiteType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'demo',
                CheckboxType::class,
                array('label' => $options['translation_route'] . '.moderator.form.site.demo', 'required' => false)
            )
            ->add(
                'title',
                TextType::class,
                array('label' => $options['translation_route'] . '.moderator.form.site.title', 'required' => false)
            )
            ->add(
                'subtitle',
                TextType::class,
                array('label' => $options['translation_route'] . '.moderator.form.site.subtitle', 'required' => false)
            )
            ->add(
                'logo',
                MediaType::class,
                array(
                    'label'    => $options['translation_route'] . '.moderator.form.site.logo',
                    'filter'   => 'image/*',
                    'required' => false,
                )
            )
            ->add(
                'background_top',
                MediaType::class,
                array(
                    'label'    => $options['translation_route'] . '.moderator.form.site.background_top',
                    'filter'   => 'image/*',
                    'required' => false,
                )
            )
            ->add(
                'background_card',
                MediaType::class,
                array(
                    'label'    => $options['translation_route'] . '.moderator.form.site.background_card',
                    'filter'   => 'image/*',
                    'required' => false,
                )
            )
            ->add(
                'background_testimony',
                MediaType::class,
                array(
                    'label'    => $options['translation_route'] . '.moderator.form.site.background_testimony',
                    'filter'   => 'image/*',
                    'required' => false,
                )
            )
            ->add(
                'social_facebook',
                TextType::class,
                array(
                    'label'    => $options['translation_route'] . '.moderator.form.site.social.facebook',
                    'attr'     => ['placeholder' => $options['translation_route'] . '.moderator.form.site.social.placeholder'],
                    'required' => false,
                )
            )
            ->add(
                'social_twitter',
                TextType::class,
                array(
                    'label'    => $options['translation_route'] . '.moderator.form.site.social.twitter',
                    'attr'     => ['placeholder' => $options['translation_route'] . '.moderator.form.site.social.placeholder'],
                    'required' => false,
                )
            )
            ->add(
                'social_google',
                TextType::class,
                array(
                    'label'    => $options['translation_route'] . '.moderator.form.site.social.google',
                    'attr'     => ['placeholder' => $options['translation_route'] . '.moderator.form.site.social.placeholder'],
                    'required' => false,
                )
            )
            ->add(
                'social_instagram',
                TextType::class,
                array(
                    'label'    => $options['translation_route'] . '.moderator.form.site.social.instagram',
                    'attr'     => ['placeholder' => $options['translation_route'] . '.moderator.form.site.social.placeholder'],
                    'required' => false,
                )
            )
            ->add(
                'social_youtube',
                TextType::class,
                array(
                    'label'    => $options['translation_route'] . '.moderator.form.site.social.youtube',
                    'attr'     => ['placeholder' => $options['translation_route'] . '.moderator.form.site.social.placeholder'],
                    'required' => false,
                )
            )
            ->add(
                'social_flux',
                TextType::class,
                array(
                    'label'    => $options['translation_route'] . '.moderator.form.site.social.flux',
                    'attr'     => ['placeholder' => $options['translation_route'] . '.moderator.form.site.social.placeholder'],
                    'required' => false,
                )
            );
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'ModeratorSite';
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class'         => '',
                'translation_domain' => 'ProjectDefaultBundle',
                'cascade_validation' => true,
                'translation_route'  => 'project',
                'user_admin'         => '',
                'object_manager'     => ''
            )
        );
    }
}