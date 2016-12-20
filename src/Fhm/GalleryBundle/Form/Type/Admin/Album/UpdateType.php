<?php
namespace Fhm\GalleryBundle\Form\Type\Admin\Album;

use Doctrine\Bundle\MongoDBBundle\Form\Type\DocumentType;
use Fhm\FhmBundle\Form\Type\Admin\UpdateType as FhmType;
use Fhm\GalleryBundle\Repository\GalleryRepository;
use Fhm\MediaBundle\Form\Type\MediaType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class UpdateType
 *
 * @package Fhm\GalleryBundle\Form\Type\Admin\Album
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
            'content',
            TextareaType::class,
            array(
                'label' => $options['translation_route'].'.admin.update.form.content',
                'attr' => array('class' => 'editor'),
                'required' => false,
            )
        )->add(
            'add_global',
            CheckboxType::class,
            array('label' => $options['translation_route'].'.admin.update.form.add_global', 'required' => false)
        )->add(
            'sort',
            ChoiceType::class,
            array(
                'label' => $options['translation_route'].'.admin.update.form.sort',
                'choices' => $this->_sortChoices($options),
            )
        )->add(
            'image',
            MediaType::class,
            array(
                'label' => $options['translation_route'].'.admin.update.form.image',
                'filter' => 'image/*',
                'required' => false,
            )
        )->add(
            'galleries',
            DocumentType::class,
            array(
                'label' => $options['translation_route'].'.admin.update.form.galleries',
                'class' => 'FhmGalleryBundle:Gallery',
                'choice_label' => 'name',
                'query_builder' => function (GalleryRepository $dr) use ($options) {
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
            "date_create" => $options['translation_route'].'.admin.sort.create.asc',
            "date_create desc" => $options['translation_route'].'.admin.sort.create.desc',
            "date_update" => $options['translation_route'].'.admin.sort.update.asc',
            "date_update desc" => $options['translation_route'].'.admin.sort.update.desc',
        );
    }

}