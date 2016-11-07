<?php
namespace Fhm\UserBundle\Form\Type\Admin;

use Fhm\GeolocationBundle\Form\Type\Admin\CreateType as GeolocationType;
use Symfony\Component\Form\FormBuilderInterface;

class CreateType extends GeolocationType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);
        $builder
            ->add('username', 'text', array('label' => $this->instance->translation . '.admin.create.form.username'))
            ->add('email', 'email', array('label' => $this->instance->translation . '.admin.create.form.email'))
            ->add('first_name', 'text', array('label' => $this->instance->translation . '.admin.create.form.first_name', 'required' => false))
            ->add('last_name', 'text', array('label' => $this->instance->translation . '.admin.create.form.last_name', 'required' => false))
            ->add('birth_date', 'birthday', array('label' => $this->instance->translation . '.admin.create.form.birth_date', 'required' => false))
            ->add('tel1', 'text', array('label' => $this->instance->translation . '.admin.create.form.tel1', 'required' => false))
            ->add('tel2', 'text', array('label' => $this->instance->translation . '.admin.create.form.tel2', 'required' => false))
            ->add('enabled', 'checkbox', array('label' => $this->instance->translation . '.admin.create.form.enabled', 'required' => false))
            ->add('locked', 'checkbox', array('label' => $this->instance->translation . '.admin.create.form.locked', 'required' => false))
            ->add('sign', 'textarea', array('label' => $this->instance->translation . '.admin.create.form.sign', 'attr' => array('class' => 'editor'), 'required' => false))
            ->add('sex', 'choice', array(
                'choices'            => array(
                    'm' => 'fhm.sex.m',
                    'f' => 'fhm.sex.f'
                ),
                'label'              => 'fhm.sex.label',
                'translation_domain' => 'FhmFhmBundle',
                'required'           => false
            ))
            ->add('avatar', 'media', array(
                'label'    => $this->instance->translation . '.admin.create.form.avatar',
                'filter'   => 'image/*',
                'required' => false
            ))
            ->add('social_facebook', 'text', array('label' => $this->instance->translation . '.admin.create.form.social.facebook', 'required' => false))
            ->add('social_facebook_id', 'text', array('label' => $this->instance->translation . '.admin.create.form.social.facebookId', 'required' => false))
            ->add('social_twitter', 'text', array('label' => $this->instance->translation . '.admin.create.form.social.twitter', 'required' => false))
            ->add('social_twitter_id', 'text', array('label' => $this->instance->translation . '.admin.create.form.social.twitterId', 'required' => false))
            ->add('social_google', 'text', array('label' => $this->instance->translation . '.admin.create.form.social.google', 'required' => false))
            ->add('social_google_id', 'text', array('label' => $this->instance->translation . '.admin.create.form.social.googleId', 'required' => false))
            ->add('social_instagram', 'text', array('label' => $this->instance->translation . '.admin.create.form.social.instagram', 'required' => false))
            ->add('social_youtube', 'text', array('label' => $this->instance->translation . '.admin.create.form.social.youtube', 'required' => false))
            ->add('social_flux', 'text', array('label' => $this->instance->translation . '.admin.create.form.social.flux', 'required' => false))
            ->add('social_site', 'text', array('label' => $this->instance->translation . '.admin.create.form.social.site', 'required' => false))
            ->remove('name')
            ->remove('description')
            ->remove('active')
            ->remove('share')
            ->remove('global')
            ->remove('grouping');
    }
}