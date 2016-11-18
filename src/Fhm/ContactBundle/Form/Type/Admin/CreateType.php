<?php
namespace Fhm\ContactBundle\Form\Type\Admin;

use Fhm\GeolocationBundle\Form\Type\Admin\CreateType as GeolocationType;
use Symfony\Component\Form\FormBuilderInterface;

class CreateType extends GeolocationType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);
        $builder
            ->add('order', 'integer', array('label' => $this->instance->translation . '.admin.create.form.order', 'required' => false))
            ->add('phone', 'text', array('label' => $this->instance->translation . '.admin.create.form.phone', 'required' => false))
            ->add('fax', 'text', array('label' => $this->instance->translation . '.admin.create.form.fax', 'required' => false))
            ->add('email', 'email', array('label' => $this->instance->translation . '.admin.create.form.email', 'required' => false))
            ->add('form', 'checkbox', array('label' => $this->instance->translation . '.admin.create.form.form.check', 'required' => false))
            ->add('form_template', 'text', array('label' => $this->instance->translation . '.admin.create.form.form.template', 'required' => false))
            ->add('profile', 'checkbox', array('label' => $this->instance->translation . '.admin.create.form.profile.check', 'required' => false))
            ->add('profile_template', 'text', array('label' => $this->instance->translation . '.admin.create.form.profile.template', 'required' => false))
            ->add('profile_image', 'media', array(
                    'label'    => $this->instance->translation . '.admin.create.form.profile.image',
                    'filter'   => 'image/*',
                    'required' => false
                )
            )
            ->add('social_facebook', 'text', array('label' => $this->instance->translation . '.admin.create.form.social.facebook', 'required' => false))
            ->add('social_twitter', 'text', array('label' => $this->instance->translation . '.admin.create.form.social.twitter', 'required' => false))
            ->add('social_google', 'text', array('label' => $this->instance->translation . '.admin.create.form.social.google', 'required' => false))
            ->add('social_instagram', 'text', array('label' => $this->instance->translation . '.admin.create.form.social.instagram', 'required' => false))
            ->add('social_youtube', 'text', array('label' => $this->instance->translation . '.admin.create.form.social.youtube', 'required' => false))
            ->add('social_flux', 'text', array('label' => $this->instance->translation . '.admin.create.form.social.flux', 'required' => false))
            ->add('social_site', 'text', array('label' => $this->instance->translation . '.admin.create.form.social.site', 'required' => false))
            ->remove('share')
            ->remove('global');
    }
}