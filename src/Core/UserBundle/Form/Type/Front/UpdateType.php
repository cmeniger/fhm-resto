<?php
namespace Core\UserBundle\Form\Type\Front;

use Core\GeolocationBundle\Form\Type\Front\UpdateType as GeolocationType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Security\Core\Validator\Constraints\UserPassword;

class UpdateType extends GeolocationType
{
    protected $root;

    public function __construct($context, $instance)
    {
        $this->root            = $context->getToken()->getUser()->getId();
        $instance              = new \stdClass();
        $instance->translation = 'user';
        $instance->class       = 'Fhm\\UserBundle\\Document\\User';
        $instance->domain      = 'FhmUserBundle';
        parent::__construct($instance, null);
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);
        $builder
            ->add('current_password', 'password', array('label' => $this->instance->translation . '.front.update.form.password', 'mapped' => false, 'constraints' => new UserPassword()))
            ->add('username', 'text', array('label' => $this->instance->translation . '.front.update.form.username'))
            ->add('email', 'email', array('label' => $this->instance->translation . '.front.update.form.email'))
            ->add('first_name', 'text', array('label' => $this->instance->translation . '.front.update.form.first_name', 'required' => false))
            ->add('last_name', 'text', array('label' => $this->instance->translation . '.front.update.form.last_name', 'required' => false))
            ->add('birth_date', 'birthday', array('label' => $this->instance->translation . '.front.update.form.birth_date', 'required' => false))
            ->add('tel1', 'text', array('label' => $this->instance->translation . '.front.update.form.tel1', 'required' => false))
            ->add('tel2', 'text', array('label' => $this->instance->translation . '.front.update.form.tel2', 'required' => false))
            ->add('sign', 'textarea', array('label' => $this->instance->translation . '.front.update.form.sign', 'attr' => array('class' => 'editor'), 'required' => false))
            ->add('sex', 'choice', array(
                'choices'            => array(
                    'm' => 'fhm.sex.m',
                    'f' => 'fhm.sex.f'
                ),
                'label'              => 'fhm.sex.label',
                'translation_domain' => 'FhmFhmBundle',
            ))
            ->add('avatar', 'media', array(
                'label'    => $this->instance->translation . '.front.update.form.avatar',
                'filter'   => 'image/*',
                'root'     => '&user',
                'private'  => false,
                'required' => false
            ))
            ->add('social_facebook', 'text', array('label' => $this->instance->translation . '.front.update.form.social.facebook', 'required' => false))
            ->add('social_facebook_id', 'text', array('label' => $this->instance->translation . '.front.update.form.social.facebookId', 'required' => false))
            ->add('social_twitter', 'text', array('label' => $this->instance->translation . '.front.update.form.social.twitter', 'required' => false))
            ->add('social_twitter_id', 'text', array('label' => $this->instance->translation . '.front.update.form.social.twitterId', 'required' => false))
            ->add('social_google', 'text', array('label' => $this->instance->translation . '.front.update.form.social.google', 'required' => false))
            ->add('social_google_id', 'text', array('label' => $this->instance->translation . '.front.update.form.social.googleId', 'required' => false))
            ->add('social_instagram', 'text', array('label' => $this->instance->translation . '.front.update.form.social.instagram', 'required' => false))
            ->add('social_youtube', 'text', array('label' => $this->instance->translation . '.front.update.form.social.youtube', 'required' => false))
            ->add('social_flux', 'text', array('label' => $this->instance->translation . '.front.update.form.social.flux', 'required' => false))
            ->add('social_site', 'text', array('label' => $this->instance->translation . '.front.update.form.social.site', 'required' => false))
            ->remove('name')
            ->remove('description')
            ->remove('submitNew')
            ->remove('submitDuplicate')
            ->remove('submitQuit');
    }
}