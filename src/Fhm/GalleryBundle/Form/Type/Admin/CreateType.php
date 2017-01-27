<?php
namespace Fhm\GalleryBundle\Form\Type\Admin;

use Doctrine\Bundle\MongoDBBundle\Form\Type\DocumentType;
use Fhm\FhmBundle\Form\Type\Admin\CreateType as FhmType;
use Fhm\FhmBundle\Manager\TypeManager;
use Fhm\GalleryBundle\Repository\GalleryAlbumRepository;
use Fhm\GalleryBundle\Repository\GalleryItemRepository;
use Fhm\GalleryBundle\Repository\GalleryVideoRepository;
use Fhm\MediaBundle\Form\Type\MediaType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class CreateType
 *
 * @package Fhm\GalleryBundle\Form\Type\Admin
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
            array('label' => $options['translation_route'].'.admin.create.form.title')
        )->add(
            'subtitle',
            TextType::class,
            array('label' => $options['translation_route'].'.admin.create.form.subtitle', 'required' => false)
        )->add(
            'resume',
            TextareaType::class,
            array(
                'label' => $options['translation_route'].'.admin.create.form.resume',
                'attr' => array('class' => 'editor'),
                'required' => false,
            )
        )->add(
            'content',
            TextareaType::class,
            array(
                'label' => $options['translation_route'].'.admin.create.form.content',
                'attr' => array('class' => 'editor'),
                'required' => false,
            )
        )->add(
            'add_global_item',
            CheckboxType::class,
            array(
                'label' => $options['translation_route'].'.admin.create.form.add_global_item',
                'required' => false,
            )
        )->add(
            'add_global_video',
            CheckboxType::class,
            array(
                'label' => $options['translation_route'].'.admin.create.form.add_global_video',
                'required' => false,
            )
        )->add(
            'order_item',
            ChoiceType::class,
            array(
                'label' => $options['translation_route'].'.admin.create.form.order_item',
                'choices' => $this->sortChoices($options),
            )
        )->add(
            'order_video',
            ChoiceType::class,
            array(
                'label' => $options['translation_route'].'.admin.create.form.order_video',
                'choices' => $this->sortChoices($options),
            )
        )->add(
            'order',
            IntegerType::class,
            array('label' => $options['translation_route'].'.admin.create.form.order', 'required' => false)
        )->add(
            'image',
            MediaType::class,
            array(
                'label' => $options['translation_route'].'.admin.create.form.image',
                'filter' => 'image/*',
                'required' => false,
            )
        )->add(
            'albums',
            TypeManager::getType($options['object_manager']->getDBDriver()),
            array(
                'label' => $options['translation_route'].'.admin.create.form.albums',
                'class' => 'FhmGalleryBundle:GalleryAlbum',
                'choice_label' => 'name',
                'query_builder' => function () use ($options) {
                    $dr = $options['object_manager']->getCurrentRepository('FhmGalleryBundle:GalleryAlbum');
                    return $dr->getFormEnable();
                },
                'required' => false,
                'multiple' => true,
                'by_reference' => false,
            )
        )->add(
            'items',
            TypeManager::getType($options['object_manager']->getDBDriver()),
            array(
                'label' => $options['translation_route'].'.admin.create.form.items',
                'class' => 'FhmGalleryBundle:GalleryItem',
                'choice_label' => 'name',
                'query_builder' => function () use ($options) {
                    $dr = $options['object_manager']->getCurrentRepository('FhmGalleryBundle:GalleryItem');
                    return $dr->getFormEnable();
                },
                'required' => false,
                'multiple' => true,
                'by_reference' => false,
            )
        )->add(
            'videos',
            TypeManager::getType($options['object_manager']->getDBDriver()),
            array(
                'label' => $options['translation_route'].'.admin.create.form.videos',
                'class' => 'FhmGalleryBundle:GalleryVideo',
                'choice_label' => 'name',
                'query_builder' => function () use ($options) {
                    $dr = $options['object_manager']->getCurrentRepository('FhmGalleryBundle:GalleryVideo');
                    return $dr->getFormEnable();
                },
                'required' => false,
                'multiple' => true,
                'by_reference' => false,
            )
        )->remove('name')->remove('description');
    }

    /**
     * @return array
     */
    private function sortChoices($options)
    {
        return array(
            $options['translation_route'].'.admin.sort.title.asc' => "title",
            $options['translation_route'].'.admin.sort.title.desc' => "title desc",
            $options['translation_route'].'.admin.sort.order.asc' => "order",
            $options['translation_route'].'.admin.sort.order.desc' => "order desc",
            $options['translation_route'].'.admin.sort.create.asc' => "date_create",
            $options['translation_route'].'.admin.sort.create.desc' => "date_create desc",
            $options['translation_route'].'.admin.sort.update.asc' => "date_update",
            $options['translation_route'].'.admin.sort.update.desc' => "date_update desc",
        );
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class' => 'Fhm\GalleryBundle\Document\Gallery',
                'translation_domain' => 'FhmGalleryBundle',
                'cascade_validation' => true,
                'translation_route' => 'gallery',
                'filter' => '',
                'user_admin' => '',
                'object_manager' => ''
            )
        );
    }
}