<?php
namespace Fhm\FhmBundle\Form\Type\Admin\Site;

use Fhm\FhmBundle\Form\Type\Admin\CreateType as FhmType;
use Fhm\FhmBundle\Manager\TypeManager;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
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
        parent::buildForm($builder, $options);
        $builder->add(
            'title',
            TextType::class,
            array('label' => $options['translation_route'].'.admin.create.form.title', 'required' => false)
        )->add(
            'subtitle',
            TextType::class,
            array('label' => $options['translation_route'].'.admin.create.form.subtitle', 'required' => false)
        )->add(
            'legal_notice',
            TextType::class,
            array(
                'label' => $options['translation_route'].'.admin.create.form.legalnotice',
                'required' => false,
                'attr' => array('class' => 'editor'),
            )
        )->add(
            'menu',
            TypeManager::getType($options['object_manager']->getDBDriver()),
            array(
                'label' => $options['translation_route'].'.admin.create.form.menu',
                'class' => 'FhmFhmBundle:Menu',
                'query_builder' => function () use ($options) {
                    $dr = $options['object_manager']->getCurrentRepository('FhmFhmBundle:Menu');

                    return $dr->getFormEnable();
                },
                'required' => false,
            )
        )->add(
            'news',
            TypeManager::getType($options['object_manager']->getDBDriver()),
            array(
                'label' => $options['translation_route'].'.admin.create.form.news',
                'class' => 'FhmNewsBundle:NewsGroup',
                'query_builder' => function () use ($options) {
                    $dr = $options['object_manager']->getCurrentRepository('FhmNewsBundle:NewsGroup');

                    return $dr->getFormEnable();
                },
                'required' => false,
            )
        )->add(
            'partner',
            TypeManager::getType($options['object_manager']->getDBDriver()),
            array(
                'label' => $options['translation_route'].'.admin.create.form.partner',
                'class' => 'FhmPartnerBundle:PartnerGroup',
                'query_builder' => function () use ($options) {
                    $dr = $options['object_manager']->getCurrentRepository('FhmPartnerBundle:PartnerGroup');

                    return $dr->getFormEnable();
                },
                'required' => false,
            )
        )->add(
            'slider',
            TypeManager::getType($options['object_manager']->getDBDriver()),
            array(
                'label' => $options['translation_route'].'.admin.create.form.slider',
                'class' => 'FhmSliderBundle:Slider',
                'query_builder' => function () use ($options) {
                    $dr = $options['object_manager']->getCurrentRepository('FhmSliderBundle:Slider');

                    return $dr->getFormEnable();
                },
                'required' => false,
            )
        )->add(
            'gallery',
            TypeManager::getType($options['object_manager']->getDBDriver()),
            array(
                'label' => $options['translation_route'].'.admin.create.form.gallery',
                'class' => 'FhmGalleryBundle:Gallery',
                'query_builder' => function () use ($options) {
                    $dr = $options['object_manager']->getCurrentRepository('FhmGalleryBundle:Gallery');

                    return $dr->getFormEnable();
                },
                'required' => false,
            )
        )->add(
            'background',
            TypeManager::getType($options['object_manager']->getDBDriver()),
            array(
                'label' => $options['translation_route'].'.admin.create.form.background',
                'class' => 'FhmMediaBundle:Media',
                'query_builder' => function () use ($options) {
                    $dr = $options['object_manager']->getCurrentRepository('FhmMediaBundle:Media');

                    return $dr->getFormEnable();
                },
                'required' => false,
            )
        )->add(
            'logo',
            TypeManager::getType($options['object_manager']->getDBDriver()),
            array(
                'label' => $options['translation_route'].'.admin.create.form.logo',
                'class' => 'FhmMediaBundle:Media',
                'query_builder' => function () use ($options) {
                    $dr = $options['object_manager']->getCurrentRepository('FhmMediaBundle:Media');

                    return $dr->getFormEnable();
                },
                'required' => false,
            )
        )->add(
            'contact',
            TypeManager::getType($options['object_manager']->getDBDriver()),
            array(
                'label' => $options['translation_route'].'.admin.create.form.contact',
                'class' => 'FhmContactBundle:Contact',
                'query_builder' => function () use ($options) {
                    $dr = $options['object_manager']->getCurrentRepository('FhmContactBundle:Contact');

                    return $dr->getFormEnable();
                },
                'required' => false,
            )
        )->add(
            'social_facebook',
            TextType::class,
            array(
                'label' => $options['translation_route'].'.admin.create.form.social.facebook',
                'attr' => ['placeholder' => $options['translation_route'].'.admin.create.form.social.placeholder'],
                'required' => false,
            )
        )->add(
            'social_facebook_id',
            TextType::class,
            array(
                'label' => $options['translation_route'].'.admin.create.form.social.facebookId',
                'attr' => ['placeholder' => $options['translation_route'].'.admin.create.form.social.facebookId'],
                'required' => false,
            )
        )->add(
            'social_twitter',
            TextType::class,
            array(
                'label' => $options['translation_route'].'.admin.create.form.social.twitter',
                'attr' => ['placeholder' => $options['translation_route'].'.admin.create.form.social.placeholder'],
                'required' => false,
            )
        )->add(
            'social_twitter_id',
            TextType::class,
            array(
                'label' => $options['translation_route'].'.admin.create.form.social.twitterId',
                'attr' => ['placeholder' => $options['translation_route'].'.admin.create.form.social.twitterId'],
                'required' => false,
            )
        )->add(
            'social_google',
            TextType::class,
            array(
                'label' => $options['translation_route'].'.admin.create.form.social.google',
                'attr' => ['placeholder' => $options['translation_route'].'.admin.create.form.social.placeholder'],
                'required' => false,
            )
        )->add(
            'social_google_id',
            TextType::class,
            array(
                'label' => $options['translation_route'].'.admin.create.form.social.googleId',
                'attr' => ['placeholder' => $options['translation_route'].'.admin.create.form.social.googleId'],
                'required' => false,
            )
        )->add(
            'social_instagram',
            TextType::class,
            array(
                'label' => $options['translation_route'].'.admin.create.form.social.instagram',
                'attr' => ['placeholder' => $options['translation_route'].'.admin.create.form.social.placeholder'],
                'required' => false,
            )
        )->add(
            'social_youtube',
            TextType::class,
            array(
                'label' => $options['translation_route'].'.admin.create.form.social.youtube',
                'attr' => ['placeholder' => $options['translation_route'].'.admin.create.form.social.placeholder'],
                'required' => false,
            )
        )->add(
            'social_flux',
            TextType::class,
            array(
                'label' => $options['translation_route'].'.admin.create.form.social.flux',
                'attr' => ['placeholder' => $options['translation_route'].'.admin.create.form.social.placeholder'],
                'required' => false,
            )
        )->remove('global')->remove('share');
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class' => '',
                'translation_domain' => 'FhmFhmSite',
                'cascade_validation' => true,
                'translation_route' => 'site',
                'user_admin' => '',
                'object_manager' => '',
            )
        );
    }
}