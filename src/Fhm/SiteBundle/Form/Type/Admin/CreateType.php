<?php
namespace Fhm\SiteBundle\Form\Type\Admin;

<<<<<<< HEAD
use Doctrine\Bundle\MongoDBBundle\Form\Type\DocumentType;
=======
use Doctrine\Bundle\MongoDBBundle\Tests\Fixtures\Form\Document;
>>>>>>> 38cda99b5f12c1d0aa18b720eae0dfb66c70db18
use Fhm\FhmBundle\Form\Type\Admin\CreateType as FhmType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Fhm\MediaBundle\Form\Type\MediaType;

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
        $builder
<<<<<<< HEAD
            ->add('title', TextType::class, array('label' => $this->instance->translation . '.admin.create.form.title', 'required' => false))
            ->add('subtitle', TextType::class, array('label' => $this->instance->translation . '.admin.create.form.subtitle', 'required' => false))
            ->add('legal_notice',TextType::class, array('label' => $this->instance->translation . '.admin.create.form.legalnotice', 'required' => false,'attr' => array('class' => 'editor')))
            ->add('menu', DocumentType::class, array(
                'label'         => $this->instance->translation . '.admin.create.form.menu',
                'class'         => 'FhmMenuBundle:Menu',
                'choice_label'      => 'name',
                'query_builder' => function (\Fhm\MenuBundle\Repository\MenuRepository $dr)
                    {
                        return $dr->getFormEnable($this->instance->grouping->filtered);
                    },
                'required'      => false
            ))
            ->add('news',DocumentType::class, array(
                'label'         => $this->instance->translation . '.admin.create.form.news',
                'class'         => 'FhmNewsBundle:NewsGroup',
                'choice_label'      => 'name',
                'query_builder' => function (\Fhm\NewsBundle\Repository\NewsGroupRepository $dr)
                    {
                        return $dr->getFormEnable($this->instance->grouping->filtered);
                    },
                'required'      => false
            ))
            ->add('partner',DocumentType::class, array(
                'label'         => $this->instance->translation . '.admin.create.form.partner',
                'class'         => 'FhmPartnerBundle:PartnerGroup',
                'choice_label'      => 'name',
                'query_builder' => function (\Fhm\PartnerBundle\Repository\PartnerGroupRepository $dr)
                    {
                        return $dr->getFormEnable($this->instance->grouping->filtered);
                    },
                'required'      => false
            ))
            ->add('slider', DocumentType::class, array(
                'label'         => $this->instance->translation . '.admin.create.form.slider',
                'class'         => 'FhmSliderBundle:Slider',
                'choice_label'      => 'name',
                'query_builder' => function (\Fhm\SliderBundle\Repository\SliderRepository $dr)
                    {
                        return $dr->getFormEnable($this->instance->grouping->filtered);
                    },
                'required'      => false
            ))
            ->add('gallery', DocumentType::class, array(
                'label'         => $this->instance->translation . '.admin.create.form.gallery',
                'class'         => 'FhmGalleryBundle:Gallery',
                'choice_label'      => 'name',
                'query_builder' => function (\Fhm\GalleryBundle\Repository\GalleryRepository $dr)
                    {
                        return $dr->getFormEnable($this->instance->grouping->filtered);
                    },
                'required'      => false
            ))
            ->add('background',MediaType::class, array(
                'label'    => $this->instance->translation . '.admin.create.form.background',
                'filter'   => 'image/*',
                'required' => false
            ))
            ->add('logo', MediaType::class, array(
                'label'    => $this->instance->translation . '.admin.create.form.logo',
                'filter'   => 'image/*',
                'required' => false
            ))
            ->add('contact',DocumentType::class, array(
                'label'         => $this->instance->translation . '.admin.create.form.contact',
                'class'         => 'FhmContactBundle:Contact',
                'choice_label'      => 'name',
                'query_builder' => function (\Fhm\ContactBundle\Repository\ContactRepository $dr)
                {
                    return $dr->getFormEnable($this->instance->grouping->filtered);
                },
                'required'      => false
            ))
            ->add('social_facebook', TextType::class, array('label' => $this->instance->translation . '.admin.create.form.social.facebook', 'required' => false))
            ->add('social_facebook_id', TextType::class, array('label' => $this->instance->translation . '.admin.create.form.social.facebookId', 'required' => false))
            ->add('social_twitter', TextType::class, array('label' => $this->instance->translation . '.admin.create.form.social.twitter', 'required' => false))
            ->add('social_twitter_id', TextType::class, array('label' => $this->instance->translation . '.admin.create.form.social.twitterId', 'required' => false))
            ->add('social_google', TextType::class, array('label' => $this->instance->translation . '.admin.create.form.social.google', 'required' => false))
            ->add('social_google_id',TextType::class, array('label' => $this->instance->translation . '.admin.create.form.social.googleId', 'required' => false))
            ->add('social_instagram',TextType::class, array('label' => $this->instance->translation . '.admin.create.form.social.instagram', 'required' => false))
            ->add('social_youtube', TextType::class, array('label' => $this->instance->translation . '.admin.create.form.social.youtube', 'required' => false))
            ->add('social_flux', TextType::class, array('label' => $this->instance->translation . '.admin.create.form.social.flux', 'required' => false))
=======
            ->add(
                'title',
                TextType::class,
                array('label' => $this->instance->translation.'.admin.create.form.title', 'required' => false)
            )
            ->add(
                'subtitle',
                TextType::class,
                array('label' => $this->instance->translation.'.admin.create.form.subtitle', 'required' => false)
            )
            ->add(
                'legal_notice',
                TextType::class,
                array(
                    'label' => $this->instance->translation.'.admin.create.form.legalnotice',
                    'required' => false,
                    'attr' => array('class' => 'editor'),
                )
            )
            ->add(
                'menu',
                Document::class,
                array(
                    'label' => $this->instance->translation.'.admin.create.form.menu',
                    'class' => 'FhmMenuBundle:Menu',
                    'property' => 'name',
                    'query_builder' => function (\Fhm\MenuBundle\Repository\MenuRepository $dr) {
                        return $dr->getFormEnable($this->instance->grouping->filtered);
                    },
                    'required' => false,
                )
            )
            ->add(
                'news',
                Document::class,
                array(
                    'label' => $this->instance->translation.'.admin.create.form.news',
                    'class' => 'FhmNewsBundle:NewsGroup',
                    'property' => 'name',
                    'query_builder' => function (\Fhm\NewsBundle\Repository\NewsGroupRepository $dr) {
                        return $dr->getFormEnable($this->instance->grouping->filtered);
                    },
                    'required' => false,
                )
            )
            ->add(
                'partner',
                Document::class,
                array(
                    'label' => $this->instance->translation.'.admin.create.form.partner',
                    'class' => 'FhmPartnerBundle:PartnerGroup',
                    'property' => 'name',
                    'query_builder' => function (\Fhm\PartnerBundle\Repository\PartnerGroupRepository $dr) {
                        return $dr->getFormEnable($this->instance->grouping->filtered);
                    },
                    'required' => false,
                )
            )
            ->add(
                'slider',
                Document::class,
                array(
                    'label' => $this->instance->translation.'.admin.create.form.slider',
                    'class' => 'FhmSliderBundle:Slider',
                    'property' => 'name',
                    'query_builder' => function (\Fhm\SliderBundle\Repository\SliderRepository $dr) {
                        return $dr->getFormEnable($this->instance->grouping->filtered);
                    },
                    'required' => false,
                )
            )
            ->add(
                'gallery',
                Document::class,
                array(
                    'label' => $this->instance->translation.'.admin.create.form.gallery',
                    'class' => 'FhmGalleryBundle:Gallery',
                    'property' => 'name',
                    'query_builder' => function (\Fhm\GalleryBundle\Repository\GalleryRepository $dr) {
                        return $dr->getFormEnable($this->instance->grouping->filtered);
                    },
                    'required' => false,
                )
            )
            ->add(
                'background',
                MediaType::class,
                array(
                    'label' => $this->instance->translation.'.admin.create.form.background',
                    'filter' => 'image/*',
                    'required' => false,
                )
            )
            ->add(
                'logo',
                MediaType::class,
                array(
                    'label' => $this->instance->translation.'.admin.create.form.logo',
                    'filter' => 'image/*',
                    'required' => false,
                )
            )
            ->add(
                'contact',
                Document::class,
                array(
                    'label' => $this->instance->translation.'.admin.create.form.contact',
                    'class' => 'FhmContactBundle:Contact',
                    'property' => 'name',
                    'query_builder' => function (\Fhm\ContactBundle\Repository\ContactRepository $dr) {
                        return $dr->getFormEnable($this->instance->grouping->filtered);
                    },
                    'required' => false,
                )
            )
            ->add(
                'social_facebook',
                TextType::class,
                array('label' => $this->instance->translation.'.admin.create.form.social.facebook', 'required' => false)
            )
            ->add(
                'social_facebook_id',
                TextType::class,
                array(
                    'label' => $this->instance->translation.'.admin.create.form.social.facebookId',
                    'required' => false,
                )
            )
            ->add(
                'social_twitter',
                TextType::class,
                array('label' => $this->instance->translation.'.admin.create.form.social.twitter', 'required' => false)
            )
            ->add(
                'social_twitter_id',
                TextType::class,
                array(
                    'label' => $this->instance->translation.'.admin.create.form.social.twitterId',
                    'required' => false,
                )
            )
            ->add(
                'social_google',
                TextType::class,
                array('label' => $this->instance->translation.'.admin.create.form.social.google', 'required' => false)
            )
            ->add(
                'social_google_id',
                'text',
                array('label' => $this->instance->translation.'.admin.create.form.social.googleId', 'required' => false)
            )
            ->add(
                'social_instagram',
                TextType::class,
                array(
                    'label' => $this->instance->translation.'.admin.create.form.social.instagram',
                    'required' => false,
                )
            )
            ->add(
                'social_youtube',
                TextType::class,
                array('label' => $this->instance->translation.'.admin.create.form.social.youtube', 'required' => false)
            )
            ->add(
                'social_flux',
                TextType::class,
                array('label' => $this->instance->translation.'.admin.create.form.social.flux', 'required' => false)
            )
>>>>>>> 38cda99b5f12c1d0aa18b720eae0dfb66c70db18
            ->remove('global')
            ->remove('share');
    }
}