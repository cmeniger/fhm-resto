<?php

namespace Fhm\FhmBundle\Form\Type\Admin\Site;

use Fhm\FhmBundle\Form\Type\Admin\CreateType as FhmType;
use Fhm\FhmBundle\Manager\TypeManager;
use Fhm\MediaBundle\Form\Type\MediaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class CreateType
 *
 * @package Fhm\SiteBundle\Form\Type\Admin
 */
class CreateType extends FhmType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);
        $builder
            ->add(
                'title',
                TextType::class,
                array('label' => $options['translation_route'] . '.admin.create.form.title', 'required' => false)
            )
            ->add(
                'title_card_slider',
                TextType::class,
                array('label' => $options['translation_route'] . '.admin.create.form.title', 'required' => false)
            )
            ->add(
                'title_card_main',
                TextType::class,
                array('label' => $options['translation_route'] . '.admin.create.form.title', 'required' => false)
            )
            ->add(
                'title_card_forward',
                TextType::class,
                array('label' => $options['translation_route'] . '.admin.create.form.title', 'required' => false)
            )
            ->add(
                'title_testimony',
                TextType::class,
                array('label' => $options['translation_route'] . '.admin.create.form.title', 'required' => false)
            )
            ->add(
                'title_news',
                TextType::class,
                array('label' => $options['translation_route'] . '.admin.create.form.title', 'required' => false)
            )
            ->add(
                'title_partner',
                TextType::class,
                array('label' => $options['translation_route'] . '.admin.create.form.title', 'required' => false)
            )
            ->add(
                'subtitle',
                TextType::class,
                array('label' => $options['translation_route'] . '.admin.create.form.subtitle', 'required' => false)
            )
            ->add(
                'subtitle_card_slider',
                TextType::class,
                array('label' => $options['translation_route'] . '.admin.create.form.subtitle', 'required' => false)
            )
            ->add(
                'subtitle_card_main',
                TextType::class,
                array('label' => $options['translation_route'] . '.admin.create.form.subtitle', 'required' => false)
            )
            ->add(
                'subtitle_card_forward',
                TextType::class,
                array('label' => $options['translation_route'] . '.admin.create.form.subtitle', 'required' => false)
            )
            ->add(
                'subtitle_testimony',
                TextType::class,
                array('label' => $options['translation_route'] . '.admin.create.form.subtitle', 'required' => false)
            )
            ->add(
                'subtitle_news',
                TextType::class,
                array('label' => $options['translation_route'] . '.admin.create.form.subtitle', 'required' => false)
            )
            ->add(
                'subtitle_partner',
                TextType::class,
                array('label' => $options['translation_route'] . '.admin.create.form.subtitle', 'required' => false)
            )
            ->add(
                'demo',
                CheckboxType::class,
                array('label' => $options['translation_route'].'.admin.create.form.demo', 'required' => false)
            )
            ->add(
                'show_slider',
                CheckboxType::class,
                array('label' => $options['translation_route'].'.admin.create.form.show', 'required' => false)
            )
            ->add(
                'show_gallery_top',
                CheckboxType::class,
                array('label' => $options['translation_route'].'.admin.create.form.show', 'required' => false)
            )
            ->add(
                'show_gallery_bottom',
                CheckboxType::class,
                array('label' => $options['translation_route'].'.admin.create.form.show', 'required' => false)
            )
            ->add(
                'show_card_slider',
                CheckboxType::class,
                array('label' => $options['translation_route'].'.admin.create.form.show', 'required' => false)
            )
            ->add(
                'show_card_main',
                CheckboxType::class,
                array('label' => $options['translation_route'].'.admin.create.form.show', 'required' => false)
            )
            ->add(
                'show_card_forward',
                CheckboxType::class,
                array('label' => $options['translation_route'].'.admin.create.form.show', 'required' => false)
            )
            ->add(
                'show_testimony',
                CheckboxType::class,
                array('label' => $options['translation_route'].'.admin.create.form.show', 'required' => false)
            )
            ->add(
                'show_contact',
                CheckboxType::class,
                array('label' => $options['translation_route'].'.admin.create.form.show', 'required' => false)
            )
            ->add(
                'show_news',
                CheckboxType::class,
                array('label' => $options['translation_route'].'.admin.create.form.show', 'required' => false)
            )
            ->add(
                'show_partner',
                CheckboxType::class,
                array('label' => $options['translation_route'].'.admin.create.form.show', 'required' => false)
            )
            ->add(
                'menu',
                TypeManager::getType($options['object_manager']->getDBDriver()),
                array(
                    'label'         => $options['translation_route'] . '.admin.create.form.menu',
                    'class'         => 'FhmFhmBundle:Menu',
                    'query_builder' => function () use ($options)
                    {
                        $dr = $options['object_manager']->getCurrentRepository('FhmFhmBundle:Menu');

                        return $dr->getFormEnable();
                    },
                    'required'      => false,
                )
            )
            ->add(
                'menu_home_left',
                TypeManager::getType($options['object_manager']->getDBDriver()),
                array(
                    'label'         => $options['translation_route'] . '.admin.create.form.menu_home_left',
                    'class'         => 'FhmFhmBundle:Menu',
                    'query_builder' => function () use ($options)
                    {
                        $dr = $options['object_manager']->getCurrentRepository('FhmFhmBundle:Menu');

                        return $dr->getFormEnable();
                    },
                    'required'      => false,
                )
            )
            ->add(
                'menu_home_right',
                TypeManager::getType($options['object_manager']->getDBDriver()),
                array(
                    'label'         => $options['translation_route'] . '.admin.create.form.menu_home_right',
                    'class'         => 'FhmFhmBundle:Menu',
                    'query_builder' => function () use ($options)
                    {
                        $dr = $options['object_manager']->getCurrentRepository('FhmFhmBundle:Menu');

                        return $dr->getFormEnable();
                    },
                    'required'      => false,
                )
            )
            ->add(
                'menu_home_side',
                TypeManager::getType($options['object_manager']->getDBDriver()),
                array(
                    'label'         => $options['translation_route'] . '.admin.create.form.menu_home_side',
                    'class'         => 'FhmFhmBundle:Menu',
                    'query_builder' => function () use ($options)
                    {
                        $dr = $options['object_manager']->getCurrentRepository('FhmFhmBundle:Menu');

                        return $dr->getFormEnable();
                    },
                    'required'      => false,
                )
            )
            ->add(
                'menu_footer',
                TypeManager::getType($options['object_manager']->getDBDriver()),
                array(
                    'label'         => $options['translation_route'] . '.admin.create.form.menu_footer',
                    'class'         => 'FhmFhmBundle:Menu',
                    'query_builder' => function () use ($options)
                    {
                        $dr = $options['object_manager']->getCurrentRepository('FhmFhmBundle:Menu');

                        return $dr->getFormEnable();
                    },
                    'required'      => false,
                )
            )
            ->add(
                'logo',
                MediaType::class,
                array(
                    'label'    => $options['translation_route'] . '.admin.create.form.logo',
                    'filter'   => 'image/*',
                    'required' => false,
                )
            )
            ->add(
                'background_top',
                MediaType::class,
                array(
                    'label'    => $options['translation_route'] . '.admin.create.form.background_top',
                    'filter'   => 'image/*',
                    'required' => false,
                )
            )
            ->add(
                'background_card',
                MediaType::class,
                array(
                    'label'    => $options['translation_route'] . '.admin.create.form.background_card',
                    'filter'   => 'image/*',
                    'required' => false,
                )
            )
            ->add(
                'background_testimony',
                MediaType::class,
                array(
                    'label'    => $options['translation_route'] . '.admin.create.form.background_testimony',
                    'filter'   => 'image/*',
                    'required' => false,
                )
            )
            ->add(
                'slider',
                TypeManager::getType($options['object_manager']->getDBDriver()),
                array(
                    'label'         => $options['translation_route'] . '.admin.create.form.slider',
                    'class'         => 'FhmSliderBundle:Slider',
                    'query_builder' => function () use ($options)
                    {
                        $dr = $options['object_manager']->getCurrentRepository('FhmSliderBundle:Slider');

                        return $dr->getFormEnable();
                    },
                    'required'      => false,
                )
            )
            ->add(
                'gallery_top',
                TypeManager::getType($options['object_manager']->getDBDriver()),
                array(
                    'label'         => $options['translation_route'] . '.admin.create.form.gallery_top',
                    'class'         => 'FhmGalleryBundle:Gallery',
                    'query_builder' => function () use ($options)
                    {
                        $dr = $options['object_manager']->getCurrentRepository('FhmGalleryBundle:Gallery');

                        return $dr->getFormEnable();
                    },
                    'required'      => false,
                )
            )
            ->add(
                'gallery_bottom',
                TypeManager::getType($options['object_manager']->getDBDriver()),
                array(
                    'label'         => $options['translation_route'] . '.admin.create.form.gallery_bottom',
                    'class'         => 'FhmGalleryBundle:Gallery',
                    'query_builder' => function () use ($options)
                    {
                        $dr = $options['object_manager']->getCurrentRepository('FhmGalleryBundle:Gallery');

                        return $dr->getFormEnable();
                    },
                    'required'      => false,
                )
            )
            ->add(
                'card_slider',
                TypeManager::getType($options['object_manager']->getDBDriver()),
                array(
                    'label'         => $options['translation_route'] . '.admin.create.form.card',
                    'class'         => 'FhmCardBundle:Card',
                    'query_builder' => function () use ($options)
                    {
                        $dr = $options['object_manager']->getCurrentRepository('FhmCardBundle:Card');

                        return $dr->getFormEnable();
                    },
                    'required'      => false,
                )
            )
            ->add(
                'card_main',
                TypeManager::getType($options['object_manager']->getDBDriver()),
                array(
                    'label'         => $options['translation_route'] . '.admin.create.form.card',
                    'class'         => 'FhmCardBundle:Card',
                    'query_builder' => function () use ($options)
                    {
                        $dr = $options['object_manager']->getCurrentRepository('FhmCardBundle:Card');

                        return $dr->getFormEnable();
                    },
                    'required'      => false,
                )
            )
            ->add(
                'card_forward',
                TypeManager::getType($options['object_manager']->getDBDriver()),
                array(
                    'label'         => $options['translation_route'] . '.admin.create.form.card',
                    'class'         => 'FhmCardBundle:Card',
                    'query_builder' => function () use ($options)
                    {
                        $dr = $options['object_manager']->getCurrentRepository('FhmCardBundle:Card');

                        return $dr->getFormEnable();
                    },
                    'required'      => false,
                )
            )
            ->add(
                'contact',
                TypeManager::getType($options['object_manager']->getDBDriver()),
                array(
                    'label'         => $options['translation_route'] . '.admin.create.form.contact',
                    'class'         => 'FhmContactBundle:Contact',
                    'query_builder' => function () use ($options)
                    {
                        $dr = $options['object_manager']->getCurrentRepository('FhmContactBundle:Contact');

                        return $dr->getFormEnable();
                    },
                    'required'      => false,
                )
            )
            ->add(
                'news',
                TypeManager::getType($options['object_manager']->getDBDriver()),
                array(
                    'label'         => $options['translation_route'] . '.admin.create.form.news',
                    'class'         => 'FhmNewsBundle:NewsGroup',
                    'query_builder' => function () use ($options)
                    {
                        $dr = $options['object_manager']->getCurrentRepository('FhmNewsBundle:NewsGroup');

                        return $dr->getFormEnable();
                    },
                    'required'      => false,
                )
            )
            ->add(
                'partner',
                TypeManager::getType($options['object_manager']->getDBDriver()),
                array(
                    'label'         => $options['translation_route'] . '.admin.create.form.partner',
                    'class'         => 'FhmPartnerBundle:PartnerGroup',
                    'query_builder' => function () use ($options)
                    {
                        $dr = $options['object_manager']->getCurrentRepository('FhmPartnerBundle:PartnerGroup');

                        return $dr->getFormEnable();
                    },
                    'required'      => false,
                )
            )
            ->add(
                'social_facebook',
                TextType::class,
                array(
                    'label'    => $options['translation_route'] . '.admin.create.form.social.facebook',
                    'attr'     => ['placeholder' => $options['translation_route'] . '.admin.create.form.social.placeholder.link'],
                    'required' => false,
                )
            )
            ->add(
                'social_facebook_id',
                TextType::class,
                array(
                    'label'    => $options['translation_route'] . '.admin.create.form.social.facebookId',
                    'attr'     => ['placeholder' => $options['translation_route'] . '.admin.create.form.social.placeholder.id'],
                    'required' => false,
                )
            )
            ->add(
                'social_twitter',
                TextType::class,
                array(
                    'label'    => $options['translation_route'] . '.admin.create.form.social.twitter',
                    'attr'     => ['placeholder' => $options['translation_route'] . '.admin.create.form.social.placeholder.link'],
                    'required' => false,
                )
            )
            ->add(
                'social_twitter_id',
                TextType::class,
                array(
                    'label'    => $options['translation_route'] . '.admin.create.form.social.twitterId',
                    'attr'     => ['placeholder' => $options['translation_route'] . '.admin.create.form.social.placeholder.id'],
                    'required' => false,
                )
            )
            ->add(
                'social_google',
                TextType::class,
                array(
                    'label'    => $options['translation_route'] . '.admin.create.form.social.google',
                    'attr'     => ['placeholder' => $options['translation_route'] . '.admin.create.form.social.placeholder.link'],
                    'required' => false,
                )
            )
            ->add(
                'social_google_id',
                TextType::class,
                array(
                    'label'    => $options['translation_route'] . '.admin.create.form.social.googleId',
                    'attr'     => ['placeholder' => $options['translation_route'] . '.admin.create.form.social.placeholder.id'],
                    'required' => false,
                )
            )
            ->add(
                'social_instagram',
                TextType::class,
                array(
                    'label'    => $options['translation_route'] . '.admin.create.form.social.instagram',
                    'attr'     => ['placeholder' => $options['translation_route'] . '.admin.create.form.social.placeholder.link'],
                    'required' => false,
                )
            )
            ->add(
                'social_youtube',
                TextType::class,
                array(
                    'label'    => $options['translation_route'] . '.admin.create.form.social.youtube',
                    'attr'     => ['placeholder' => $options['translation_route'] . '.admin.create.form.social.placeholder.link'],
                    'required' => false,
                )
            )
            ->add(
                'social_flux',
                TextType::class,
                array(
                    'label'    => $options['translation_route'] . '.admin.create.form.social.flux',
                    'attr'     => ['placeholder' => $options['translation_route'] . '.admin.create.form.social.placeholder.link'],
                    'required' => false,
                )
            )
            ->remove('global')->remove('share');
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class'         => '',
                'translation_domain' => 'FhmFhmSiteBundle',
                'cascade_validation' => true,
                'translation_route'  => 'site',
                'user_admin'         => '',
                'object_manager'     => '',
            )
        );
    }
}