<?php
namespace Fhm\SiteBundle\Form\Type\Admin;

use Fhm\FhmBundle\Form\Type\Admin\UpdateType as FhmType;
use Symfony\Component\Form\FormBuilderInterface;

class UpdateType extends FhmType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);
        $builder
            ->add('title', 'text', array('label' => $this->instance->translation . '.admin.update.form.title', 'required' => false))
            ->add('subtitle', 'text', array('label' => $this->instance->translation . '.admin.update.form.subtitle', 'required' => false))
            ->add('legal_notice', 'text', array('label' => $this->instance->translation . '.admin.update.form.legalnotice', 'required' => false,'attr' => array('class' => 'editor')))
            ->add('menu', 'document', array(
                'label'         => $this->instance->translation . '.admin.update.form.menu',
                'class'         => 'FhmMenuBundle:Menu',
                'property'      => 'name',
                'query_builder' => function (\Fhm\MenuBundle\Repository\MenuRepository $dr)
                    {
                        return $dr->getFormEnable($this->instance->grouping->filtered);
                    },
                'required'      => false
            ))
            ->add('news', 'document', array(
                'label'         => $this->instance->translation . '.admin.update.form.news',
                'class'         => 'FhmNewsBundle:NewsGroup',
                'property'      => 'name',
                'query_builder' => function (\Fhm\NewsBundle\Repository\NewsGroupRepository $dr)
                    {
                        return $dr->getFormEnable($this->instance->grouping->filtered);
                    },
                'required'      => false
            ))
            ->add('partner', 'document', array(
                'label'         => $this->instance->translation . '.admin.update.form.partner',
                'class'         => 'FhmPartnerBundle:PartnerGroup',
                'property'      => 'name',
                'query_builder' => function (\Fhm\PartnerBundle\Repository\PartnerGroupRepository $dr)
                    {
                        return $dr->getFormEnable($this->instance->grouping->filtered);
                    },
                'required'      => false
            ))
            ->add('slider', 'document', array(
                'label'         => $this->instance->translation . '.admin.update.form.slider',
                'class'         => 'FhmSliderBundle:Slider',
                'property'      => 'name',
                'query_builder' => function (\Fhm\SliderBundle\Repository\SliderRepository $dr)
                    {
                        return $dr->getFormEnable($this->instance->grouping->filtered);
                    },
                'required'      => false
            ))
            ->add('gallery', 'document', array(
                'label'         => $this->instance->translation . '.admin.update.form.gallery',
                'class'         => 'FhmGalleryBundle:Gallery',
                'property'      => 'name',
                'query_builder' => function (\Fhm\GalleryBundle\Repository\GalleryRepository $dr)
                    {
                        return $dr->getFormEnable($this->instance->grouping->filtered);
                    },
                'required'      => false
            ))

            ->add('background', 'media', array(
                'label'    => $this->instance->translation . '.admin.update.form.background',
                'filter'   => 'image/*',
                'required' => false
            ))

            ->add('logo', 'media', array(
                'label'    => $this->instance->translation . '.admin.update.form.logo',
                'filter'   => 'image/*',
                'required' => false
            ))
            
            ->add('contact', 'document', array(
                'label'         => $this->instance->translation . '.admin.update.form.contact',
                'class'         => 'FhmContactBundle:Contact',
                'property'      => 'name',
                'query_builder' => function (\Fhm\ContactBundle\Repository\ContactRepository $dr)
                {
                    return $dr->getFormEnable($this->instance->grouping->filtered);
                },
                'required'      => false
            ))
            ->add('social_facebook', 'text', array('label' => $this->instance->translation . '.admin.update.form.social.facebook', 'required' => false))
            ->add('social_facebook_id', 'text', array('label' => $this->instance->translation . '.admin.update.form.social.facebookId', 'required' => false))
            ->add('social_twitter', 'text', array('label' => $this->instance->translation . '.admin.update.form.social.twitter', 'required' => false))
            ->add('social_twitter_id', 'text', array('label' => $this->instance->translation . '.admin.update.form.social.twitterId', 'required' => false))
            ->add('social_google', 'text', array('label' => $this->instance->translation . '.admin.update.form.social.google', 'required' => false))
            ->add('social_google_id', 'text', array('label' => $this->instance->translation . '.admin.update.form.social.googleId', 'required' => false))
            ->add('social_instagram', 'text', array('label' => $this->instance->translation . '.admin.update.form.social.instagram', 'required' => false))
            ->add('social_youtube', 'text', array('label' => $this->instance->translation . '.admin.update.form.social.youtube', 'required' => false))
            ->add('social_flux', 'text', array('label' => $this->instance->translation . '.admin.update.form.social.flux', 'required' => false))
            ->remove('global')
            ->remove('share');
    }
}