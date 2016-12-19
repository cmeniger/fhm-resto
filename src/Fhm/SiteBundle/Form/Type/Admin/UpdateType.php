<?php
namespace Fhm\SiteBundle\Form\Type\Admin;

use Doctrine\Bundle\MongoDBBundle\Form\Type\DocumentType;
use Fhm\ContactBundle\Repository\ContactRepository;
use Fhm\FhmBundle\Form\Type\Admin\UpdateType as FhmType;
use Fhm\GalleryBundle\Repository\GalleryRepository;
use Fhm\MenuBundle\Repository\MenuRepository;
use Fhm\NewsBundle\Repository\NewsGroupRepository;
use Fhm\PartnerBundle\Repository\PartnerGroupRepository;
use Fhm\SiteBundle\Document\Site;
use Fhm\SliderBundle\Repository\SliderRepository;
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
        $builder->add(
            'title',
            TextType::class,
            array('label' => $options['translation_route'].'.admin.update.form.title', 'required' => false)
        )->add(
            'subtitle',
            TextType::class,
            array('label' => $options['translation_route'].'.admin.update.form.subtitle', 'required' => false)
        )->add(
            'legal_notice',
            TextType::class,
            array(
                'label' => $options['translation_route'].'.admin.update.form.legalnotice',
                'required' => false,
                'attr' => array('class' => 'editor'),
            )
        )->add(
            'menu',
            DocumentType::class,
            array(
                'label' => $options['translation_route'].'.admin.update.form.menu',
                'class' => 'FhmMenuBundle:Menu',
                'query_builder' => function (MenuRepository $dr) use ($options) {
                    return $dr->getFormEnable($options['filter']);
                },
                'required' => false,
            )
        )->add(
            'news',
            DocumentType::class,
            array(
                'label' => $options['translation_route'].'.admin.update.form.news',
                'class' => 'FhmNewsBundle:NewsGroup',
                'query_builder' => function (NewsGroupRepository $dr) use ($options) {
                    return $dr->getFormEnable($options['filter']);
                },
                'required' => false,
            )
        )->add(
            'partner',
            DocumentType::class,
            array(
                'label' => $options['translation_route'].'.admin.update.form.partner',
                'class' => 'FhmPartnerBundle:PartnerGroup',
                'query_builder' => function (PartnerGroupRepository $dr) use ($options) {
                    return $dr->getFormEnable($options['filter']);
                },
                'required' => false,
            )
        )->add(
            'slider',
            DocumentType::class,
            array(
                'label' => $options['translation_route'].'.admin.update.form.slider',
                'class' => 'FhmSliderBundle:Slider',
                'query_builder' => function (SliderRepository $dr) use ($options) {
                    return $dr->getFormEnable($options['filter']);
                },
                'required' => false,
            )
        )->add(
            'gallery',
            DocumentType::class,
            array(
                'label' => $options['translation_route'].'.admin.update.form.gallery',
                'class' => 'FhmGalleryBundle:Gallery',
                'query_builder' => function (GalleryRepository $dr) use ($options) {
                    return $dr->getFormEnable($options['filter']);
                },
                'required' => false,
            )
        )->add(
            'background',
            MediaType::class,
            array(
                'label' => $options['translation_route'].'.admin.update.form.background',
                'filter' => 'image/*',
                'required' => false,
            )
        )->add(
            'logo',
            MediaType::class,
            array(
                'label' => $options['translation_route'].'.admin.update.form.logo',
                'filter' => 'image/*',
                'required' => false,
            )
        )->add(
            'contact',
            DocumentType::class,
            array(
                'label' => $options['translation_route'].'.admin.update.form.contact',
                'class' => 'FhmContactBundle:Contact',
                'query_builder' => function (ContactRepository $dr) use ($options) {
                    return $dr->getFormEnable($options['filter']);
                },
                'required' => false,
            )
        )->add(
            'social_facebook',
            TextType::class,
            array(
                'label' => $options['translation_route'].'.admin.update.form.social.facebook',
                'required' => false,
            )
        )->add(
            'social_facebook_id',
            TextType::class,
            array(
                'label' => $options['translation_route'].'.admin.update.form.social.facebookId',
                'required' => false,
            )
        )->add(
            'social_twitter',
            TextType::class,
            array('label' => $options['translation_route'].'.admin.update.form.social.twitter', 'required' => false)
        )->add(
            'social_twitter_id',
            TextType::class,
            array(
                'label' => $options['translation_route'].'.admin.update.form.social.twitterId',
                'required' => false,
            )
        )->add(
            'social_google',
            TextType::class,
            array('label' => $options['translation_route'].'.admin.update.form.social.google', 'required' => false)
        )->add(
            'social_google_id',
            TextType::class,
            array(
                'label' => $options['translation_route'].'.admin.update.form.social.googleId',
                'required' => false,
            )
        )->add(
            'social_instagram',
            TextType::class,
            array(
                'label' => $options['translation_route'].'.admin.update.form.social.instagram',
                'required' => false,
            )
        )->add(
            'social_youtube',
            TextType::class,
            array('label' => $options['translation_route'].'.admin.update.form.social.youtube', 'required' => false)
        )->add(
            'social_flux',
            TextType::class,
            array('label' => $options['translation_route'].'.admin.update.form.social.flux', 'required' => false)
        )->remove('global')->remove('share');
    }

}