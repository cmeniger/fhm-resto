<?php
namespace Fhm\ContactBundle\Form\Type\Admin;

use Fhm\GeolocationBundle\Form\Type\Admin\CreateType as GeolocationType;
use Symfony\Component\Form\FormBuilderInterface;

class UpdateType extends GeolocationType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);
        $builder
            ->add('order', 'integer', array('label' => $this->instance->translation . '.admin.update.form.order', 'required' => false))
            ->add('phone', 'text', array('label' => $this->instance->translation . '.admin.update.form.phone', 'required' => false))
            ->add('fax', 'text', array('label' => $this->instance->translation . '.admin.update.form.fax', 'required' => false))
            ->add('email', 'email', array('label' => $this->instance->translation . '.admin.update.form.email', 'required' => false))
            ->add('form', 'checkbox', array('label' => $this->instance->translation . '.admin.update.form.form.check', 'required' => false))
            ->add('form_template', 'text', array('label' => $this->instance->translation . '.admin.update.form.form.template', 'required' => false))
            ->add('profile', 'checkbox', array('label' => $this->instance->translation . '.admin.update.form.profile.check', 'required' => false))
            ->add('profile_template', 'text', array('label' => $this->instance->translation . '.admin.update.form.profile.template', 'required' => false))
            ->add('profile_image', 'media', array(
                    'label'    => $this->instance->translation . '.admin.update.form.profile.image',
                    'filter'   => 'image/*',
                    'required' => false
                )
            )
            ->add('social_facebook', 'text', array('label' => $this->instance->translation . '.admin.update.form.social.facebook', 'required' => false))
            ->add('social_twitter', 'text', array('label' => $this->instance->translation . '.admin.update.form.social.twitter', 'required' => false))
            ->add('social_google', 'text', array('label' => $this->instance->translation . '.admin.update.form.social.google', 'required' => false))
            ->add('social_instagram', 'text', array('label' => $this->instance->translation . '.admin.update.form.social.instagram', 'required' => false))
            ->add('social_youtube', 'text', array('label' => $this->instance->translation . '.admin.update.form.social.youtube', 'required' => false))
            ->add('social_flux', 'text', array('label' => $this->instance->translation . '.admin.update.form.social.flux', 'required' => false))
            ->add('social_site', 'text', array('label' => $this->instance->translation . '.admin.update.form.social.site', 'required' => false))
            ->remove('share')
            ->remove('global');
    }
}

