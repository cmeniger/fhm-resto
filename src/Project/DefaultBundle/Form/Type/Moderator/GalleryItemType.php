<?php

namespace Project\DefaultBundle\Form\Type\Moderator;

use Fhm\MediaBundle\Form\Type\MediaType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class GalleryItemType
 *
 * @package Project\DefaultBundle\Form\Type\Moderator
 */
class GalleryItemType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'title',
                TextType::class,
                array(
                    'label' => $options['translation_route'] . '.moderator.form.gallery.title'
                )
            )
            ->add(
                'subtitle',
                TextType::class,
                array(
                    'label'    => $options['translation_route'] . '.moderator.form.gallery.subtitle',
                    'required' => false
                )
            )
            ->add(
                'content',
                TextareaType::class,
                array(
                    'label'    => $options['translation_route'] . '.moderator.form.gallery.content',
                    'required' => false,
                )
            )
            ->add(
                'image',
                MediaType::class,
                array(
                    'label'    => $options['translation_route'] . '.moderator.form.gallery.image',
                    'filter'   => 'image/*',
                    'required' => false,
                )
            );
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'ModeratorGalleryItem';
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class'         => '',
                'translation_domain' => 'ProjectDefaultBundle',
                'cascade_validation' => true,
                'translation_route'  => 'project',
                'user_admin'         => '',
                'object_manager'     => ''
            )
        );
    }
}