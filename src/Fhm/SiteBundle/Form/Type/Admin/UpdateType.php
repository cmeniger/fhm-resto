<?php
namespace Fhm\SiteBundle\Form\Type\Admin;

use Doctrine\Bundle\MongoDBBundle\Form\Type\DocumentType;
use Fhm\FhmBundle\Form\Type\Admin\UpdateType as FhmType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Fhm\MediaBundle\Form\Type\MediaType;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class UpdateType
 * @package Fhm\SiteBundle\Form\Type\Admin
 */
class UpdateType extends FhmType
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
                'title',
                TextType::class,
                array('label' => $this->instance.'.admin.update.form.title', 'required' => false)
            )
            ->add(
                'subtitle',
                TextType::class,
                array('label' => $this->instance.'.admin.update.form.subtitle', 'required' => false)
            )
            ->add(
                'legal_notice',
                TextType::class,
                array(
                    'label' => $this->instance.'.admin.update.form.legalnotice',
                    'required' => false,
                    'attr' => array('class' => 'editor'),
                )
            )
            ->add(
                'menu',
                DocumentType::class,
                array(
                    'label' => $this->instance.'.admin.update.form.menu',
                    'class' => 'FhmMenuBundle:Menu',
                    'choice_label' => 'name',
                    'query_builder' => function (\Fhm\MenuBundle\Repository\MenuRepository $dr) {
//                        return $dr->getFormEnable($this->instance->grouping->filtered);
                    },
                    'required' => false,
                )
            )
            ->add(
                'news',
                DocumentType::class,
                array(
                    'label' => $this->instance.'.admin.update.form.news',
                    'class' => 'FhmNewsBundle:NewsGroup',
                    'choice_label' => 'name',
                    'query_builder' => function (\Fhm\NewsBundle\Repository\NewsGroupRepository $dr) {
//                        return $dr->getFormEnable($this->instance->grouping->filtered);
                    },
                    'required' => false,
                )
            )
            ->add(
                'partner',
                DocumentType::class,
                array(
                    'label' => $this->instance.'.admin.update.form.partner',
                    'class' => 'FhmPartnerBundle:PartnerGroup',
                    'choice_label' => 'name',
                    'query_builder' => function (\Fhm\PartnerBundle\Repository\PartnerGroupRepository $dr) {
//                        return $dr->getFormEnable($this->instance->grouping->filtered);
                    },
                    'required' => false,
                )
            )
            ->add(
                'slider',
                DocumentType::class,
                array(
                    'label' => $this->instance.'.admin.update.form.slider',
                    'class' => 'FhmSliderBundle:Slider',
                    'choice_label' => 'name',
                    'query_builder' => function (\Fhm\SliderBundle\Repository\SliderRepository $dr) {
//                        return $dr->getFormEnable($this->instance->grouping->filtered);
                    },
                    'required' => false,
                )
            )
            ->add(
                'gallery',
                DocumentType::class,
                array(
                    'label' => $this->instance.'.admin.update.form.gallery',
                    'class' => 'FhmGalleryBundle:Gallery',
                    'choice_label' => 'name',
                    'query_builder' => function (\Fhm\GalleryBundle\Repository\GalleryRepository $dr) {
//                        return $dr->getFormEnable($this->instance->grouping->filtered);
                    },
                    'required' => false,
                )
            )
            ->add(
                'background',
                MediaType::class,
                array(
                    'label' => $this->instance.'.admin.update.form.background',
                    'filter' => 'image/*',
                    'required' => false,
                )
            )
            ->add(
                'logo',
                MediaType::class,
                array(
                    'label' => $this->instance.'.admin.update.form.logo',
                    'filter' => 'image/*',
                    'required' => false,
                )
            )
            ->add(
                'contact',
                DocumentType::class,
                array(
                    'label' => $this->instance.'.admin.update.form.contact',
                    'class' => 'FhmContactBundle:Contact',
                    'choice_label' => 'name',
                    'query_builder' => function (\Fhm\ContactBundle\Repository\ContactRepository $dr) {
//                        return $dr->getFormEnable($this->instance->grouping->filtered);
                    },
                    'required' => false,
                )
            )
            ->add(
                'social_facebook',
                TextType::class,
                array('label' => $this->instance.'.admin.update.form.social.facebook', 'required' => false)
            )
            ->add(
                'social_facebook_id',
                TextType::class,
                array(
                    'label' => $this->instance.'.admin.update.form.social.facebookId',
                    'required' => false,
                )
            )
            ->add(
                'social_twitter',
                TextType::class,
                array('label' => $this->instance.'.admin.update.form.social.twitter', 'required' => false)
            )
            ->add(
                'social_twitter_id',
                TextType::class,
                array(
                    'label' => $this->instance.'.admin.update.form.social.twitterId',
                    'required' => false,
                )
            )
            ->add(
                'social_google',
                TextType::class,
                array('label' => $this->instance.'.admin.update.form.social.google', 'required' => false)
            )
            ->add(
                'social_google_id',
                TextType::class,
                array('label' => $this->instance.'.admin.update.form.social.googleId', 'required' => false)
            )
            ->add(
                'social_instagram',
                TextType::class,
                array(
                    'label' => $this->instance.'.admin.update.form.social.instagram',
                    'required' => false,
                )
            )
            ->add(
                'social_youtube',
                TextType::class,
                array('label' => $this->instance.'.admin.update.form.social.youtube', 'required' => false)
            )
            ->add(
                'social_flux',
                TextType::class,
                array('label' => $this->instance.'.admin.update.form.social.flux', 'required' => false)
            )
            ->remove('global')
            ->remove('share');
    }
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class' => 'Fhm\SiteBundle\Document\Site',
                'translation_domain' => 'FhmSiteBundle',
                'cascade_validation' => true,
            )
        );
    }
}