<?php
namespace Fhm\GalleryBundle\Form\Type\Admin\Item;

use Doctrine\Bundle\MongoDBBundle\Form\Type\DocumentType;
use Fhm\FhmBundle\Form\Type\Admin\CreateType as FhmType;
use Fhm\FhmBundle\Manager\TypeManager;
use Fhm\GalleryBundle\Repository\GalleryRepository;
use Fhm\MediaBundle\Repository\MediaTagRepository;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class MultipleType
 * @package Fhm\GalleryBundle\Form\Type\Admin\Item
 */
class MultipleType extends FhmType
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
            array(
                'label' => $options['translation_route'].'.admin.multiple.form.title',
            )
        )->add(
            'subtitle',
            TextType::class,
            array(
                'label' => $options['translation_route'].'.admin.multiple.form.subtitle',
                'required' => false,
            )
        )->add(
            'content',
            TextareaType::class,
            array(
                'label' => $options['translation_route'].'.admin.multiple.form.content',
                'attr' => array('class' => 'editor'),
                'required' => false,
            )
        )->add(
            'link',
            TextType::class,
            array(
                'label' => $options['translation_route'].'.admin.multiple.form.link',
                'required' => false,
            )
        )->add(
            'galleries',
            TypeManager::getType($options['object_manager']->getDBDriver()),
            array(
                'label' => $options['translation_route'].'.admin.multiple.form.galleries',
                'class' => 'FhmGalleryBundle:Gallery',
                'choice_label' => 'name',
                'query_builder' => function () use ($options) {
                    $dr = $options['object_manager']->getCurrentRepository('FhmGalleryBundle:Gallery');
                    return $dr->getFormEnable();
                },
                'required' => false,
                'multiple' => true,
                'by_reference' => false,
            )
        )->add(
            'file',
            FileType::class,
            array(
                'label' => $options['translation_route'].'.admin.multiple.form.file',
                'mapped' => false,
            )
        )->add(
            'tag',
            TextType::class,
            array(
                'label' => $options['translation_route'].'.admin.multiple.form.tag',
                'required' => false,
                'mapped' => false,
            )
        )->add(
            'parent',
            TypeManager::getType($options['object_manager']->getDBDriver()),
            array(
                'label' => $options['translation_route'].'.admin.multiple.form.parent',
                'class' => 'FhmMediaBundle:MediaTag',
                'choice_label' => 'route',
                'query_builder' => function () use ($options) {
                    $dr = $options['object_manager']->getCurrentRepository('FhmMediaBundle:MediaTag');
                    return $dr->getFormEnable();
                },
                'required' => false,
                'mapped' => false,
            )
        )->add(
            'tags',
            TypeManager::getType($options['object_manager']->getDBDriver()),
            array(
                'label' => $options['translation_route'].'.admin.multiple.form.tags',
                'class' => 'FhmMediaBundle:MediaTag',
                'choice_label' => 'route',
                'query_builder' => function () use ($options) {
                    $dr = $options['object_manager']->getCurrentRepository('FhmMediaBundle:MediaTag');
                    return $dr->getFormEnable();
                },
                'multiple' => true,
                'required' => false,
                'by_reference' => false,
                'mapped' => false,
            )
        )->remove('submitNew')->remove('submitDuplicate')->remove('submitQuit')->remove('submitConfig')->remove(
            'name'
        )->remove('description');
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class' => 'Fhm\GalleryBundle\Document\GalleryItem',
                'translation_domain' => 'FhmGalleryBundle',
                'cascade_validation' => true,
                'translation_route' => 'gallery.item',
                'user_admin' => '',
                'object_manager' => ''
            )
        );
    }
}