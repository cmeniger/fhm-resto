<?php
namespace Fhm\UserBundle\Form\Type\Front;

use Fhm\GeolocationBundle\Form\Type\Front\UpdateType as GeolocationType;
use Fhm\MediaBundle\Form\Type\MediaType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Validator\Constraints\UserPassword;

/**
 * Class UpdateType
 * @package Fhm\UserBundle\Form\Type\Front
 */
class UpdateType extends GeolocationType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     *
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);
        $builder->add(
            'current_password',
            PasswordType::class,
            array(
                'label' => $options['translation_route'].'.front.update.form.password',
                'mapped' => false,
                'constraints' => new UserPassword(),
            )
        )->add(
            'username',
            TextType::class,
            array(
                'label' => $options['translation_route'].'.front.update.form.username',
            )
        )->add(
            'email',
            EmailType::class,
            array('label' => $options['translation_route'].'.front.update.form.email')
        )->add(
            'first_name',
            TextType::class,
            array('label' => $options['translation_route'].'.front.update.form.first_name', 'required' => false)
        )->add(
            'last_name',
            TextType::class,
            array('label' => $options['translation_route'].'.front.update.form.last_name', 'required' => false)
        )->add(
            'birth_date',
            BirthdayType::class,
            array('label' => $options['translation_route'].'.front.update.form.birth_date', 'required' => false)
        )->add(
            'tel1',
            TextType::class,
            array('label' => $options['translation_route'].'.front.update.form.tel1', 'required' => false)
        )->add(
            'tel2',
            TextType::class,
            array('label' => $options['translation_route'].'.front.update.form.tel2', 'required' => false)
        )->add(
            'sign',
            TextareaType::class,
            array(
                'label' => $options['translation_route'].'.front.update.form.sign',
                'attr' => array('class' => 'editor'),
                'required' => false,
            )
        )->add(
            'sex',
            ChoiceType::class,
            array(
                'choices' => array(
                    'm' => 'fhm.sex.m',
                    'f' => 'fhm.sex.f',
                ),
                'label' => 'fhm.sex.label',
                'translation_domain' => 'FhmFhmBundle',
            )
        )->add(
            'avatar',
            MediaType::class,
            array(
                'label' => $options['translation_route'].'.front.update.form.avatar',
                'filter' => 'image/*',
                'root' => '&user',
                'private' => false,
                'required' => false,
            )
        )->add(
            'social_facebook',
            TextType::class,
            array(
                'label' => $options['translation_route'].'.front.update.form.social.facebook',
                'required' => false,
            )
        )->add(
            'social_facebook_id',
            TextType::class,
            array(
                'label' => $options['translation_route'].'.front.update.form.social.facebookId',
                'required' => false,
            )
        )->add(
            'social_twitter',
            TextType::class,
            array('label' => $options['translation_route'].'.front.update.form.social.twitter', 'required' => false)
        )->add(
            'social_twitter_id',
            TextType::class,
            array(
                'label' => $options['translation_route'].'.front.update.form.social.twitterId',
                'required' => false,
            )
        )->add(
            'social_google',
            TextType::class,
            array('label' => $options['translation_route'].'.front.update.form.social.google', 'required' => false)
        )->add(
            'social_google_id',
            TextType::class,
            array(
                'label' => $options['translation_route'].'.front.update.form.social.googleId',
                'required' => false,
            )
        )->add(
            'social_instagram',
            TextType::class,
            array(
                'label' => $options['translation_route'].'.front.update.form.social.instagram',
                'required' => false,
            )
        )->add(
            'social_youtube',
            TextType::class,
            array('label' => $options['translation_route'].'.front.update.form.social.youtube', 'required' => false)
        )->add(
            'social_flux',
            TextType::class,
            array('label' => $options['translation_route'].'.front.update.form.social.flux', 'required' => false)
        )->add(
            'social_site',
            TextType::class,
            array('label' => $options['translation_route'].'.front.update.form.social.site', 'required' => false)
        )->remove('name')->remove('description')->remove('submitNew')->remove('submitDuplicate')->remove(
            'submitQuit'
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
                'filter' => '',
                'user_admin' => '',
            )
        );
    }
}