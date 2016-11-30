<?php
namespace Fhm\ContactBundle\Form\Type\Admin;

use Fhm\GeolocationBundle\Form\Type\Admin\UpdateType as GeolocationType;
use Fhm\MediaBundle\Form\Type\MediaType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class UpdateType
 * @package Fhm\ContactBundle\Form\Type\Admin
 */
class UpdateType extends GeolocationType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);
        $builder
            ->add(
                'order',
                IntegerType::class,
                array('label' => $options['translation_route'].'.admin.update.form.order', 'required' => false)
            )
            ->add(
                'phone',
                TextType::class,
                array('label' => $options['translation_route'].'.admin.update.form.phone', 'required' => false)
            )
            ->add(
                'fax',
                TextType::class,
                array('label' => $options['translation_route'].'.admin.update.form.fax', 'required' => false)
            )
            ->add(
                'email',
                EmailType::class,
                array('label' => $options['translation_route'].'.admin.update.form.email', 'required' => false)
            )
            ->add(
                'form',
                CheckboxType::class,
                array('label' => $options['translation_route'].'.admin.update.form.form.check', 'required' => false)
            )
            ->add(
                'form_template',
                TextType::class,
                array('label' => $options['translation_route'].'.admin.update.form.form.template', 'required' => false)
            )
            ->add(
                'profile',
                CheckboxType::class,
                array('label' => $options['translation_route'].'.admin.update.form.profile.check', 'required' => false)
            )
            ->add(
                'profile_template',
                TextType::class,
                array(
                    'label' => $options['translation_route'].'.admin.update.form.profile.template',
                    'required' => false,
                )
            )
            ->add(
                'profile_image',
                MediaType::class,
                array(
                    'label' => $options['translation_route'].'.admin.update.form.profile.image',
                    'filter' => 'image/*',
                    'required' => false,
                )
            )
            ->add(
                'social_facebook',
                TextType::class,
                array('label' => $options['translation_route'].'.admin.update.form.social.facebook',
                      'required' => false)
            )
            ->add(
                'social_twitter',
                TextType::class,
                array('label' => $options['translation_route'].'.admin.update.form.social.twitter', 'required' => false)
            )
            ->add(
                'social_google',
                TextType::class,
                array('label' => $options['translation_route'].'.admin.update.form.social.google', 'required' => false)
            )
            ->add(
                'social_instagram',
                TextType::class,
                array(
                    'label' => $options['translation_route'].'.admin.update.form.social.instagram',
                    'required' => false,
                )
            )
            ->add(
                'social_youtube',
                TextType::class,
                array('label' => $options['translation_route'].'.admin.update.form.social.youtube', 'required' => false)
            )
            ->add(
                'social_flux',
                TextType::class,
                array('label' => $options['translation_route'].'.admin.update.form.social.flux', 'required' => false)
            )
            ->add(
                'social_site',
                TextType::class,
                array('label' => $options['translation_route'].'.admin.update.form.social.site', 'required' => false)
            )
            ->remove('share')
            ->remove('global');
    }
   
}

