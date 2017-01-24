<?php
namespace Fhm\MediaBundle\Form\Type\Admin;

use Doctrine\Bundle\MongoDBBundle\Form\Type\DocumentType;
use Fhm\FhmBundle\Form\Type\Admin\UpdateType as FhmType;
use Fhm\FhmBundle\Manager\TypeManager;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class UpdateType
 * @package Fhm\MediaBundle\Form\Type\Admin
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
            'file',
            FileType::class,
            array(
                'label' => $options['translation_route'].'.admin.update.form.file',
                'required' => false,
                'attr' => array('class' => 'drop'),
            )
        )->add(
            'tag',
            TextType::class,
            array(
                'label' => $options['translation_route'].'.admin.update.form.tag',
                'mapped' => false,
                'required' => false,
            )
        )->add(
            'private',
            CheckboxType::class,
            array('label' => $options['translation_route'].'.admin.update.form.private', 'required' => false)
        )->add(
            'parent',
            TypeManager::getType($options['object_manager']->getDBDriver()),
            array(
                'label' => $options['translation_route'].'.admin.update.form.parent',
                'class' => 'FhmMediaBundle:MediaTag',
                'choice_label' => 'route',
                'query_builder' => function () use ($options) {
                    $dr = $options['object_manager']->getCurrentRepository('FhmMediaBundle:MediaTag');
                    return $dr->getFormFiltered();
                },
                'mapped' => false,
                'required' => false,
            )
        )->add(
            'tags',
            TypeManager::getType($options['object_manager']->getDBDriver()),
            array(
                'label' => $options['translation_route'].'.admin.update.form.tags',
                'class' => 'FhmMediaBundle:MediaTag',
                'choice_label' => 'route',
                'query_builder' => function () use ($options) {
                    $dr = $options['object_manager']->getCurrentRepository('FhmMediaBundle:MediaTag');
                    return $dr->getFormFiltered();
                },
                'multiple' => true,
                'required' => false,
                'by_reference' => false,
            )
        )->remove('seo_title')->remove('seo_description')->remove('seo_keywords')->remove('languages')->remove(
            'grouping'
        )->remove('share')->remove('global');
    }
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class' => 'Fhm\MediaBundle\Document\Media',
                'translation_domain' => 'FhmMediaBundle',
                'cascade_validation' => true,
                'translation_route' => 'media'
            )
        );
    }
}