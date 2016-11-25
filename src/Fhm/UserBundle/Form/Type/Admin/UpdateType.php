<?php
namespace Fhm\UserBundle\Form\Type\Admin;

use Fhm\GeolocationBundle\Form\Type\Admin\UpdateType as GeolocationType;
use Fhm\MediaBundle\Form\Type\MediaType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class UpdateType extends GeolocationType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->setTranslation('user');
        parent::buildForm($builder, $options);
        $builder
            ->add('username', TextType::class, array('label' => $this->translation . '.admin.update.form.username'))
            ->add('email', 'email', array('label' => $this->translation . '.admin.update.form.email'))
            ->add('first_name', TextType::class, array('label' => $this->translation . '.admin.update.form.first_name', 'required' => false))
            ->add('last_name', TextType::class, array('label' => $this->translation . '.admin.update.form.last_name', 'required' => false))
            ->add('birth_date', 'birthday', array('label' => $this->translation . '.admin.update.form.birth_date', 'required' => false))
            ->add('tel1', TextType::class, array('label' => $this->translation . '.admin.update.form.tel1', 'required' => false))
            ->add('tel2', TextType::class, array('label' => $this->translation . '.admin.update.form.tel2', 'required' => false))
            ->add('enabled', CheckboxType::class, array('label' => $this->translation . '.admin.update.form.enabled', 'required' => false))
            ->add('locked', CheckboxType::class, array('label' => $this->translation . '.admin.update.form.locked', 'required' => false))
            ->add('sign', TextareaType::class, array('label' => $this->translation . '.admin.update.form.sign', 'attr' => array('class' => 'editor'), 'required' => false))
            ->add('sex',ChoiceType::class, array(
                'choices'            => array(
                    'm' => 'fhm.sex.m',
                    'f' => 'fhm.sex.f'
                ),
                'label'              => 'fhm.sex.label',
                'translation_domain' => 'FhmFhmBundle',
                'required'           => false
            ))
            ->add('avatar', MediaType::class, array(
                'label'    => $this->translation . '.admin.update.form.avatar',
                'filter'   => 'image/*',
                'required' => false
            ))
            ->add('social_facebook', TextType::class, array('label' => $this->translation . '.admin.update.form.social.facebook', 'required' => false))
            ->add('social_facebook_id', TextType::class, array('label' => $this->translation . '.admin.update.form.social.facebookId', 'required' => false))
            ->add('social_twitter', TextType::class, array('label' => $this->translation . '.admin.update.form.social.twitter', 'required' => false))
            ->add('social_twitter_id', TextType::class, array('label' => $this->translation . '.admin.update.form.social.twitterId', 'required' => false))
            ->add('social_google', TextType::class, array('label' => $this->translation . '.admin.update.form.social.google', 'required' => false))
            ->add('social_google_id', TextType::class, array('label' => $this->translation . '.admin.update.form.social.googleId', 'required' => false))
            ->add('social_instagram', TextType::class, array('label' => $this->translation . '.admin.update.form.social.instagram', 'required' => false))
            ->add('social_youtube', TextType::class, array('label' => $this->translation . '.admin.update.form.social.youtube', 'required' => false))
            ->add('social_flux', TextType::class, array('label' => $this->translation . '.admin.update.form.social.flux', 'required' => false))
            ->add('social_site', TextType::class, array('label' => $this->translation . '.admin.update.form.social.site', 'required' => false))
            ->remove('name')
            ->remove('description')
            ->remove('active')
            ->remove('share')
            ->remove('global')
            ->remove('grouping');
    }
}