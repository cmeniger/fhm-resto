<?php
namespace Fhm\SiteBundle\Form\Type\Admin;

use Doctrine\Bundle\MongoDBBundle\Form\Type\DocumentType;
use Fhm\FhmBundle\Form\Type\Admin\CreateType as FhmType;
use Fhm\SiteBundle\Document\Site;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Fhm\MediaBundle\Form\Type\MediaType;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class CreateType
 * @package Fhm\SiteBundle\Form\Type\Admin
 */
class CreateType extends FhmType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->setTranslation('site');
        parent::buildForm($builder, $options);
        $builder
            ->add(
                'title',
                TextType::class,
                array('label' => $this->translation.'.admin.create.form.title', 'required' => false)
            )
            ->add(
                'subtitle',
                TextType::class,
                array('label' => $this->translation.'.admin.create.form.subtitle', 'required' => false)
            )
            ->add(
                'legal_notice',
                TextType::class,
                array(
                    'label' => $this->translation.'.admin.create.form.legalnotice',
                    'required' => false,
                    'attr' => array('class' => 'editor'),
                )
            )
            ->add(
                'menu',
                DocumentType::class,
                array(
                    'label' => $this->translation.'.admin.create.form.menu',
                    'class' => 'FhmMenuBundle:Menu',
                    'query_builder' => function (\Fhm\MenuBundle\Repository\MenuRepository $dr) {
                        return $dr->getFormEnable();
                    },
                    'required' => false,
                )
            )
            ->add(
                'news',
                DocumentType::class,
                array(
                    'label' => $this->translation.'.admin.create.form.news',
                    'class' => 'FhmNewsBundle:NewsGroup',
                    'query_builder' => function (\Fhm\NewsBundle\Repository\NewsGroupRepository $dr) {
                        return $dr->getFormEnable();
                    },
                    'required' => false,
                )
            )
            ->add(
                'partner',
                DocumentType::class,
                array(
                    'label' => $this->translation.'.admin.create.form.partner',
                    'class' => 'FhmPartnerBundle:PartnerGroup',
                    'query_builder' => function (\Fhm\PartnerBundle\Repository\PartnerGroupRepository $dr) {
                        return $dr->getFormEnable();
                    },
                    'required' => false,
                )
            )
            ->add(
                'slider',
                DocumentType::class,
                array(
                    'label' => $this->translation.'.admin.create.form.slider',
                    'class' => 'FhmSliderBundle:Slider',
                    'query_builder' => function (\Fhm\SliderBundle\Repository\SliderRepository $dr) {
                        return $dr->getFormEnable();
                    },
                    'required' => false,
                )
            )
            ->add(
                'gallery',
                DocumentType::class,
                array(
                    'label' => $this->translation.'.admin.create.form.gallery',
                    'class' => 'FhmGalleryBundle:Gallery',
                    'query_builder' => function (\Fhm\GalleryBundle\Repository\GalleryRepository $dr) {
                        return $dr->getFormEnable();
                    },
                    'required' => false,
                )
            )
            ->add(
                'background',
                MediaType::class,
                array(
                    'label' => $this->translation.'.admin.create.form.background',
                    'filter' => 'image/*',
                    'required' => false,
                )
            )
            ->add(
                'logo',
                MediaType::class,
                array(
                    'label' => $this->translation.'.admin.create.form.logo',
                    'filter' => 'image/*',
                    'required' => false,
                )
            )
            ->add(
                'contact',
                DocumentType::class,
                array(
                    'label' => $this->translation.'.admin.create.form.contact',
                    'class' => 'FhmContactBundle:Contact',
                    'query_builder' => function (\Fhm\ContactBundle\Repository\ContactRepository $dr) {
                        return $dr->getFormEnable();
                    },
                    'required' => false,
                )
            )
            ->add(
                'social_facebook',
                TextType::class,
                array('label' => $this->translation.'.admin.create.form.social.facebook', 'required' => false)
            )
            ->add(
                'social_facebook_id',
                TextType::class,
                array(
                    'label' => $this->translation.'.admin.create.form.social.facebookId',
                    'required' => false,
                )
            )
            ->add(
                'social_twitter',
                TextType::class,
                array('label' => $this->translation.'.admin.create.form.social.twitter', 'required' => false)
            )
            ->add(
                'social_twitter_id',
                TextType::class,
                array(
                    'label' => $this->translation.'.admin.create.form.social.twitterId',
                    'required' => false,
                )
            )
            ->add(
                'social_google',
                TextType::class,
                array('label' => $this->translation.'.admin.create.form.social.google', 'required' => false)
            )
            ->add(
                'social_google_id',
                TextType::class,
                array('label' => $this->translation.'.admin.create.form.social.googleId', 'required' => false)
            )
            ->add(
                'social_instagram',
                TextType::class,
                array(
                    'label' => $this->translation.'.admin.create.form.social.instagram',
                    'required' => false,
                )
            )
            ->add(
                'social_youtube',
                TextType::class,
                array('label' => $this->translation.'.admin.create.form.social.youtube', 'required' => false)
            )
            ->add(
                'social_flux',
                TextType::class,
                array('label' => $this->translation.'.admin.create.form.social.flux', 'required' => false)
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