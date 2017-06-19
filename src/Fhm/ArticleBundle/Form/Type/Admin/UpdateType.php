<?php

namespace Fhm\ArticleBundle\Form\Type\Admin;

use Fhm\FhmBundle\Form\Type\Admin\UpdateType as FhmType;
use Fhm\FhmBundle\Form\Type\AutocompleteType;
use Fhm\FhmBundle\Manager\TypeManager;
use Fhm\MediaBundle\Form\Type\MediaType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class UpdateType
 *
 * @package Fhm\ArticleBundle\Form\Type\Admin
 */
class UpdateType extends FhmType
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
                array(
                    'label' => $options['translation_route'] . '.admin.update.form.title',
                )
            )
            ->add(
                'subtitle',
                TextType::class,
                array('label' => $options['translation_route'] . '.admin.update.form.subtitle', 'required' => false)
            )
            ->add(
                'resume',
                TextareaType::class,
                array(
                    'label' => $options['translation_route'] . '.admin.update.form.resume',
                    'attr'  => array('class' => 'editor'),
                )
            )
            ->add(
                'content',
                TextareaType::class,
                array(
                    'label' => $options['translation_route'] . '.admin.update.form.content',
                    'attr'  => array('class' => 'editor'),
                )
            )
            ->add(
                'image',
                MediaType::class,
                array(
                    'label'    => $options['translation_route'] . '.admin.update.form.image',
                    'filter'   => 'image/*',
                    'required' => false,
                )
            )
            ->add(
                'gallery',
                TypeManager::getType($options['object_manager']->getDBDriver()),
                array(
                    'label'         => $options['translation_route'] . '.admin.update.form.gallery',
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
                'author',
                TypeManager::getType($options['object_manager']->getDBDriver()),
                array(
                    'label'         => $options['translation_route'] . '.admin.update.form.author',
                    'class'         => 'FhmUserBundle:User',
                    'query_builder' => function () use ($options)
                    {
                        $dr = $options['object_manager']->getCurrentRepository('FhmUserBundle:User');

                        return $dr->getFormEnable();
                    },
                    //                'url' => 'fhm_api_user_autocomplete',
                    'required'      => false,
                )
            )
            ->remove('global')->remove('name')->remove('description');
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class'         => '',
                'translation_domain' => 'FhmArticleBundle',
                'cascade_validation' => true,
                'translation_route'  => 'article',
                'user_admin'         => '',
                'object_manager'     => ''
            )
        );
    }
}