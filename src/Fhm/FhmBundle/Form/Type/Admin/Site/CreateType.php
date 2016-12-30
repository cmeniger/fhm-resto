<?php
namespace Fhm\FhmBundle\Form\Type\Admin\Site;

use Doctrine\Bundle\MongoDBBundle\Form\Type\DocumentType;
use Fhm\ContactBundle\Repository\ContactRepository;
use Fhm\FhmBundle\Form\Type\Admin\CreateType as FhmType;
use Fhm\GalleryBundle\Repository\GalleryRepository;
use Fhm\FhmBundle\Repository\MenuRepository;
use Fhm\NewsBundle\Repository\NewsGroupRepository;
use Fhm\PartnerBundle\Repository\PartnerGroupRepository;
use Fhm\FhmBundle\Document\Site;
use Fhm\SliderBundle\Repository\SliderRepository;
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
            DocumentType::class,
            array(
                'label' => $options['translation_route'].'.admin.create.form.menu',
                'class' => 'FhmFhmBundle:Menu',
                'query_builder' => function (MenuRepository $dr) use ($options) {
                    return $dr->getFormEnable();
                },
                'required' => false,
            )
        )->add(
            'news',
            DocumentType::class,
            array(
                'label' => $options['translation_route'].'.admin.create.form.news',
                'class' => 'FhmNewsBundle:NewsGroup',
                'query_builder' => function (NewsGroupRepository $dr) use ($options) {
                    return $dr->getFormEnable();
                },
                'required' => false,
            )
        )->add(
            'partner',
            DocumentType::class,
            array(
                'label' => $options['translation_route'].'.admin.create.form.partner',
                'class' => 'FhmPartnerBundle:PartnerGroup',
                'query_builder' => function (PartnerGroupRepository $dr) use ($options) {
                    return $dr->getFormEnable();
                },
                'required' => false,
            )
        )->add(
            'slider',
            DocumentType::class,
            array(
                'label' => $options['translation_route'].'.admin.create.form.slider',
                'class' => 'FhmSliderBundle:Slider',
                'query_builder' => function (SliderRepository $dr) use ($options) {
                    return $dr->getFormEnable();
                },
                'required' => false,
            )
        )->add(
            'gallery',
            DocumentType::class,
            array(
                'label' => $options['translation_route'].'.admin.create.form.gallery',
                'class' => 'FhmGalleryBundle:Gallery',
                'query_builder' => function (GalleryRepository $dr) use ($options) {
                    return $dr->getFormEnable();
                },
                'required' => false,
            )
        )->add(
            'background',
            MediaType::class,
            array(
                'label' => $options['translation_route'].'.admin.create.form.background',
                'filter' => 'image/*',
                'required' => false,
            )
        )->add(
            'logo',
            MediaType::class,
            array(
                'label' => $options['translation_route'].'.admin.create.form.logo',
                'filter' => 'image/*',
                'required' => false,
            )
        )->add(
            'contact',
            DocumentType::class,
            array(
                'label' => $options['translation_route'].'.admin.create.form.contact',
                'class' => 'FhmContactBundle:Contact',
                'query_builder' => function (ContactRepository $dr) use ($options) {
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

}