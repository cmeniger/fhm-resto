<?php
namespace Fhm\UserBundle\Form\Type\Admin;

use Fhm\GeolocationBundle\Form\Type\Admin\CreateType as GeolocationType;
use Fhm\MediaBundle\Form\Type\MediaType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class CreateType
 * @package Fhm\UserBundle\Form\Type\Admin
 */
class CreateType extends GeolocationType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);
        $builder->add(
            'username',
            TextType::class,
            array(
                'label' => $options['translation_route'].'.admin.create.form.username',
            )
        )->add(
            'email',
            EmailType::class,
            array('label' => $options['translation_route'].'.admin.create.form.email')
        )->add(
            'first_name',
            TextType::class,
            array('label' => $options['translation_route'].'.admin.create.form.first_name', 'required' => false)
        )->add(
            'last_name',
            TextType::class,
            array('label' => $options['translation_route'].'.admin.create.form.last_name', 'required' => false)
        )->add(
            'birth_date',
            BirthdayType::class,
            array('label' => $options['translation_route'].'.admin.create.form.birth_date', 'required' => false)
        )->add(
            'tel1',
            TextType::class,
            array('label' => $options['translation_route'].'.admin.create.form.tel1', 'required' => false)
        )->add(
            'tel2',
            TextType::class,
            array('label' => $options['translation_route'].'.admin.create.form.tel2', 'required' => false)
        )->add(
            'enabled',
            CheckboxType::class,
            array('label' => $options['translation_route'].'.admin.create.form.enabled', 'required' => false)
        )->add(
            'sign',
            TextareaType::class,
            array(
                'label' => $options['translation_route'].'.admin.create.form.sign',
                'attr' => array('class' => 'editor'),
                'required' => false,
            )
        )->add(
            'sex',
            ChoiceType::class,
            array(
                'choices' => array(
                    'fhm.sex.m'=>'m',
                    'fhm.sex.f'=>'f',
                ),
                'label' => 'fhm.sex.label',
                'translation_domain' => 'FhmFhmBundle',
                'required' => false,
            )
        )->add(
            'avatar',
            MediaType::class,
            array(
                'label' => $options['translation_route'].'.admin.create.form.avatar',
                'filter' => 'image/*',
                'required' => false,
            )
        )->add(
            'social_facebook',
            TextType::class,
            array(
                'label' => $options['translation_route'].'.admin.create.form.social.facebook',
                'required' => false,
                'attr' => array('placeholder' => $options['translation_route'].'.admin.create.form.social.http'),
            )
        )->add(
            'social_facebook_id',
            TextType::class,
            array(
                'label' => $options['translation_route'].'.admin.create.form.social.facebookId',
                'required' => false,
                'attr' => array('placeholder' => $options['translation_route'].'.admin.create.form.social.facebookId'),
            )
        )->add(
            'social_twitter',
            TextType::class,
            array(
                'label' => $options['translation_route'].'.admin.create.form.social.twitter',
                'required' => false,
                'attr' => array('placeholder' => $options['translation_route'].'.admin.create.form.social.http'),
            )
        )->add(
            'social_twitter_id',
            TextType::class,
            array(
                'label' => $options['translation_route'].'.admin.create.form.social.twitterId',
                'required' => false,
                'attr' => array('placeholder' => $options['translation_route'].'.admin.create.form.social.twitterId'),
            )
        )->add(
            'social_google',
            TextType::class,
            array(
                'label' => $options['translation_route'].'.admin.create.form.social.google',
                'required' => false,
                'attr' => array('placeholder' => $options['translation_route'].'.admin.create.form.social.http'),
            )
        )->add(
            'social_google_id',
            TextType::class,
            array(
                'label' => $options['translation_route'].'.admin.create.form.social.googleId',
                'required' => false,
                'attr' => array('placeholder' => $options['translation_route'].'.admin.create.form.social.googleId'),
            )
        )->add(
            'social_instagram',
            TextType::class,
            array(
                'label' => $options['translation_route'].'.admin.create.form.social.instagram',
                'required' => false,
                'attr' => array('placeholder' => $options['translation_route'].'.admin.create.form.social.http'),
            )
        )->add(
            'social_youtube',
            TextType::class,
            array(
                'label' => $options['translation_route'].'.admin.create.form.social.youtube',
                'required' => false,
                'attr' => array('placeholder' => $options['translation_route'].'.admin.create.form.social.http'),
            )
        )->add(
            'social_flux',
            TextType::class,
            array(
                'label' => $options['translation_route'].'.admin.create.form.social.flux',
                'required' => false,
                'attr' => array('placeholder' => $options['translation_route'].'.admin.create.form.social.http'),
            )
        )->add(
            'social_site',
            TextType::class,
            array(
                'label' => $options['translation_route'].'.admin.create.form.social.site',
                'required' => false,
                'attr' => array('placeholder' => $options['translation_route'].'.admin.create.form.social.http'),
            )
        )->remove('name')->remove('description')->remove('active')->remove('share')->remove('global')->remove(
            'grouping'
        );
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
                'user_admin' => '',
            )
        );
    }

}