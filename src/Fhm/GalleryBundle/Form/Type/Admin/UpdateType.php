<?php
namespace Fhm\GalleryBundle\Form\Type\Admin;

use Doctrine\Bundle\MongoDBBundle\Form\Type\DocumentType;
use Fhm\FhmBundle\Form\Type\Admin\UpdateType as FhmType;
use Fhm\MediaBundle\Form\Type\MediaType;
use Fhm\GalleryBundle\Repository\GalleryAlbumRepository;
use Fhm\GalleryBundle\Repository\GalleryItemRepository;
use Fhm\GalleryBundle\Repository\GalleryVideoRepository;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class UpdateType
 *
 * @package Fhm\GalleryBundle\Form\Type\Admin
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
            array('label' => $options['translation_route'].'.admin.update.form.title')
        )->add(
            'subtitle',
            TextType::class,
            array('label' => $options['translation_route'].'.admin.update.form.subtitle', 'required' => false)
        )->add(
            'resume',
            TextareaType::class,
            array(
                'label' => $options['translation_route'].'.admin.update.form.resume',
                'attr' => array('class' => 'editor'),
                'required' => false,
            )
        )->add(
            'content',
            TextareaType::class,
            array(
                'label' => $options['translation_route'].'.admin.update.form.content',
                'attr' => array('class' => 'editor'),
                'required' => false,
            )
        )->add(
            'add_global_item',
            CheckboxType::class,
            array(
                'label' => $options['translation_route'].'.admin.update.form.add_global_item',
                'required' => false,
            )
        )->add(
            'add_global_video',
            CheckboxType::class,
            array(
                'label' => $options['translation_route'].'.admin.update.form.add_global_video',
                'required' => false,
            )
        )->add(
            'order_item',
            ChoiceType::class,
            array(
                'label' => $options['translation_route'].'.admin.update.form.order_item',
                'choices' => $this->_sortChoices($options),
            )
        )->add(
            'order_video',
            ChoiceType::class,
            array(
                'label' => $options['translation_route'].'.admin.update.form.order_video',
                'choices' => $this->_sortChoices($options),
            )
        )->add(
            'order',
            IntegerType::class,
            array('label' => $options['translation_route'].'.admin.update.form.order', 'required' => false)
        )->add(
            'image',
            MediaType::class,
            array(
                'label' => $options['translation_route'].'.admin.update.form.image',
                'filter' => 'image/*',
                'required' => false,
            )
        )->add(
            'albums',
            DocumentType::class,
            array(
                'label' => $options['translation_route'].'.admin.update.form.albums',
                'class' => 'FhmGalleryBundle:GalleryAlbum',
                'choice_label' => 'name',
                'query_builder' => function (GalleryAlbumRepository $dr) use ($options) {
                    return $dr->getFormEnable($options['filter']);
                },
                'required' => false,
                'multiple' => true,
                'by_reference' => false,
            )
        )->add(
            'items',
            DocumentType::class,
            array(
                'label' => $options['translation_route'].'.admin.update.form.items',
                'class' => 'FhmGalleryBundle:GalleryItem',
                'choice_label' => 'name',
                'query_builder' => function (GalleryItemRepository $dr) use ($options) {
                    return $dr->getFormEnable($options['filter']);
                },
                'required' => false,
                'multiple' => true,
                'by_reference' => false,
            )
        )->add(
            'videos',
            DocumentType::class,
            array(
                'label' => $options['translation_route'].'.admin.update.form.videos',
                'class' => 'FhmGalleryBundle:GalleryVideo',
                'choice_label' => 'name',
                'query_builder' => function (GalleryVideoRepository $dr) use ($options) {
                    return $dr->getFormEnable($options['filter']);
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
    private function _sortChoices($options)
    {
        return array(
            "title" => $options['translation_route'].'.admin.sort.title.asc',
            "title desc" => $options['translation_route'].'.admin.sort.title.desc',
            "order" => $options['translation_route'].'.admin.sort.order.asc',
            "order desc" => $options['translation_route'].'.admin.sort.order.desc',
            "date_update" => $options['translation_route'].'.admin.sort.update.asc',
            "date_update desc" => $options['translation_route'].'.admin.sort.update.desc',
            "date_update" => $options['translation_route'].'.admin.sort.update.asc',
            "date_update desc" => $options['translation_route'].'.admin.sort.update.desc',
        );
    }

}